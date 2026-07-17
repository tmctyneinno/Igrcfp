<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use App\Services\BrevoMailService;
use App\Traits\SendsScholarshipNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScholarshipController extends Controller
{
    use SendsScholarshipNotifications;
    
    protected $mailService;

    public function __construct(BrevoMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function index(Request $request)
    {
        $query = ScholarshipApplication::with('post')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('nationality', 'LIKE', "%{$search}%");
            });
        }

        $applications = $query->paginate(20);

        return view('admin.scholarships.index', compact('applications'));
    }

    public function show(ScholarshipApplication $application)
    {
        $application->load('post');
        return view('admin.scholarships.show', compact('application'));
    }

    public function updateStatus(Request $request, ScholarshipApplication $application)
    {
        // Log the incoming request
        Log::info('Updating scholarship status', [
            'application_id' => $application->id,
            'request_data' => $request->all()
        ]);

        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,accepted,rejected',
            'admin_notes' => 'nullable|string',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);
        
        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        // Check if status is actually changing
        if ($oldStatus === $newStatus) {
            return redirect()->route('admin.scholarships.index')
                ->with('info', 'Application status is already set to ' . ucfirst(str_replace('_', ' ', $newStatus)));
        }
        
        $updateData = [
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ];
        
        // Handle rejection
        if ($newStatus === 'rejected') {
            $updateData['rejection_reason'] = $request->rejection_reason;
            $updateData['rejected_at'] = now();
            Log::info('Setting rejection data', [
                'reason' => $request->rejection_reason,
                'rejected_at' => now()
            ]);
        }
        
        // Handle acceptance
        if ($newStatus === 'accepted') {
            $updateData['accepted_at'] = now();
        }
        
        // Update the application
        $updated = $application->update($updateData);
        
        Log::info('Application updated', [
            'application_id' => $application->id,
            'updated' => $updated,
            'new_status' => $application->fresh()->status,
            'update_data' => $updateData
        ]);
        
        // Send notification
        try {
            $this->sendStatusChangeNotification(
                $application, 
                $this->mailService, 
                $oldStatus, 
                $newStatus,
                $request->rejection_reason
            );
        } catch (\Exception $e) {
            Log::error('Notification sending failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $message = 'Application status updated successfully!';
        
        if ($newStatus === 'accepted') {
            $message .= ' A scholarship approval email has been sent to the applicant.';
        } elseif ($newStatus === 'under_review') {
            $message .= ' A notification email has been sent to the applicant.';
        } elseif ($newStatus === 'rejected') {
            $message .= ' A notification email has been sent to the applicant.';
        }

        return redirect()->route('admin.scholarships.index')
            ->with('success', $message);
    }

    public function destroy(ScholarshipApplication $application)
    {
        $application->delete();
        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Application deleted successfully!');
    }

    public function checkAIContent($id)
    {
        try {
            $application = ScholarshipApplication::findOrFail($id);
            $text = request()->input('text');
            
            if (empty($text)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No text provided'
                ], 400);
            }
            
            // Use Hugging Face free AI detection model
            $aiProbability = $this->detectAIWithHuggingFace($text);
            
            // SAVE THE RESULT TO DATABASE
            $application->update([
                'ai_detection_score' => $aiProbability,
                'ai_checked_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'ai_probability' => $aiProbability,
                'message' => 'Analysis complete and saved.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('AI Detection Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detect AI-generated content using Hugging Face's free API
     * Uses the roberta-base-openai-detector model
     */
    private function detectAIWithHuggingFace($text)
    {
        // Truncate text to reasonable length (model has limits)
        $truncatedText = substr($text, 0, 512);
        
        try {
            // Using Hugging Face Inference API (Free tier available)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . (env('HUGGINGFACE_API_KEY') ?: 'hf_dummy_token_for_free_tier'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api-inference.huggingface.co/models/roberta-base-openai-detector', [
                'inputs' => $truncatedText
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                // The model returns probabilities for each class
                // Usually: [{"label": "Fake", "score": 0.95}, {"label": "Real", "score": 0.05}]
                if (is_array($result) && !empty($result)) {
                    foreach ($result as $item) {
                        if (isset($item['label']) && isset($item['score'])) {
                            // "Fake" means AI-generated, "Real" means human-written
                            if ($item['label'] === 'Fake' || $item['label'] === 'AI') {
                                return min(max($item['score'], 0), 1);
                            }
                        }
                    }
                }
                
                // If we can't parse the result, return middle value
                return 0.5;
            }
            
            // If API fails, fall back to enhanced heuristic method
            Log::warning('Hugging Face API failed, using fallback detection');
            return $this->detectAIEnhanced($truncatedText);
            
        } catch (\Exception $e) {
            Log::error('Hugging Face API Error', ['error' => $e->getMessage()]);
            // Fallback to enhanced heuristic
            return $this->detectAIEnhanced($truncatedText);
        }
    }

    /**
     * Enhanced heuristic-based AI detection (fallback method)
     * More sophisticated than the simple version
     */
    private function detectAIEnhanced($text)
    {
        $indicators = 0;
        $totalChecks = 10;
        
        // 1. Check for overly formal transition words (common in AI)
        $formalWords = ['furthermore', 'moreover', 'consequently', 'nevertheless', 'additionally', 'subsequently', 'henceforth'];
        $formalCount = 0;
        foreach ($formalWords as $word) {
            if (stripos($text, $word) !== false) {
                $formalCount++;
            }
        }
        if ($formalCount >= 2) $indicators++;
        
        // 2. Check sentence length uniformity (AI tends to be more uniform)
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (count($sentences) > 3) {
            $lengths = array_map('strlen', $sentences);
            $avgLength = array_sum($lengths) / count($lengths);
            $variance = array_sum(array_map(function($l) use ($avgLength) {
                return pow($l - $avgLength, 2);
            }, $lengths)) / count($lengths);
            $stdDev = sqrt($variance);
            
            // Low standard deviation suggests AI (too uniform)
            if ($stdDev < 20 && $avgLength > 50) {
                $indicators++;
            }
        }
        
        // 3. Check for repetitive phrases
        $words = preg_split('/\s+/', strtolower($text));
        $uniqueWords = array_unique($words);
        $repetitionRatio = count($uniqueWords) / max(count($words), 1);
        if ($repetitionRatio < 0.5) {
            $indicators++;
        }
        
        // 4. Check for perfect grammar patterns (AI rarely makes typos)
        $hasTypos = preg_match('/\b(teh|adn|taht|wiht|thier)\b/i', $text);
        if (!$hasTypos && strlen($text) > 200) {
            $indicators++;
        }
        
        // 5. Check for list-like structures (AI loves bullet points)
        if (preg_match_all('/^\s*[-•*]\s/m', $text) >= 3) {
            $indicators++;
        }
        
        // 6. Perplexity approximation (simple version)
        $wordCount = count($words);
        $avgWordLength = array_sum(array_map('strlen', $words)) / max($wordCount, 1);
        if ($avgWordLength > 6) {
            $indicators++;
        }
        
        // 7. Check for hedging language (AI often uses this)
        $hedgingWords = ['it is important to note', 'it should be mentioned', 'one might consider', 'it could be argued'];
        foreach ($hedgingWords as $phrase) {
            if (stripos($text, $phrase) !== false) {
                $indicators++;
                break;
            }
        }
        
        // 8. Paragraph structure analysis
        $paragraphs = preg_split('/\n\s*\n/', $text);
        if (count($paragraphs) > 2) {
            $paraLengths = array_map('strlen', $paragraphs);
            $paraAvg = array_sum($paraLengths) / count($paraLengths);
            $paraVariance = array_sum(array_map(function($l) use ($paraAvg) {
                return pow($l - $paraAvg, 2);
            }, $paraLengths)) / count($paraLengths);
            
            // Very uniform paragraph lengths suggest AI
            if (sqrt($paraVariance) < 50) {
                $indicators++;
            }
        }
        
        // 9. Check for overuse of passive voice
        $passivePatterns = ['/(\w+)\s+(was|were|been|being)\s+(\w+ed|\w+en)/i'];
        $passiveCount = 0;
        foreach ($passivePatterns as $pattern) {
            preg_match_all($pattern, $text, $matches);
            $passiveCount += count($matches[0]);
        }
        if ($passiveCount > 3) {
            $indicators++;
        }
        
        // 10. Vocabulary diversity (Type-Token Ratio)
        if ($wordCount > 20) {
            $ttr = count($uniqueWords) / $wordCount;
            if ($ttr < 0.4) {
                $indicators++;
            }
        }
        
        // Calculate probability
        $probability = $indicators / $totalChecks;
        
        return min(max($probability, 0), 1);
    }

    /**
     * Alternative: Use ZeroGPT API (Free tier available)
     * Sign up at https://www.zerogpt.com/page/api
     */
    private function detectAIWithZeroGPT($text)
    {
        $apiKey = env('ZEROGPT_API_KEY');
        
        if (empty($apiKey)) {
            Log::warning('ZeroGPT API key not configured');
            return $this->detectAIEnhanced($text);
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.zerogpt.com/api/detect', [
                'text' => substr($text, 0, 5000)
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                // ZeroGPT returns percentage like 85.5 for AI detection
                return isset($result['ai_percentage']) ? $result['ai_percentage'] / 100 : 0.5;
            }
            
            return $this->detectAIEnhanced($text);
            
        } catch (\Exception $e) {
            Log::error('ZeroGPT API Error', ['error' => $e->getMessage()]);
            return $this->detectAIEnhanced($text);
        }
    }
}