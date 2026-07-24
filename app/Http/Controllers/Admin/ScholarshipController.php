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

    /**
     * Handles Status Updates ONLY (Status, Notes, Rejection Reason)
     */
    public function updateStatus(Request $request, ScholarshipApplication $application)
    {
        Log::info('Updating scholarship status', [
            'application_id' => $application->id,
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,accepted,rejected',
            'admin_notes' => 'nullable|string',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);
        
        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        // Check if status is actually changing
        if ($oldStatus === $newStatus && $request->admin_notes === $application->admin_notes) {
             return redirect()->route('admin.scholarships.show', $application->id)
                ->with('info', 'No changes detected.');
        }
        
        $updateData = [
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ];
        
        // Handle rejection
        if ($newStatus === 'rejected') {
            $updateData['rejection_reason'] = $request->rejection_reason;
            $updateData['rejected_at'] = now();
        }
        
        // Handle acceptance
        if ($newStatus === 'accepted') {
            $updateData['accepted_at'] = now();
        }
        
        // Update the application
        $application->update($updateData);
        
        // Send notification only if status changed
        if ($oldStatus !== $newStatus) {
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
        }

        $message = 'Application status updated successfully!';
        
        if ($newStatus === 'accepted') {
            $message .= ' A scholarship approval email has been sent.';
        } elseif ($newStatus === 'under_review') {
            $message .= ' A notification email has been sent.';
        } elseif ($newStatus === 'rejected') {
            $message .= ' A rejection email has been sent.';
        }

        return redirect()->route('admin.scholarships.show', $application->id)
            ->with('success', $message);
    }

    /**
     * Handles Granting/Revoking Course Access ONLY
     */
    public function toggleScholarshipAccess(Request $request, ScholarshipApplication $application)
    {
        $validated = $request->validate([
            'grant_scholarship_access' => 'required|boolean',
        ]);

        $shouldGrant = (bool) $request->grant_scholarship_access;
        
        // Check if value is actually different to avoid unnecessary DB writes
        if ($shouldGrant === $application->user_accepted) {
            return redirect()->back()
                ->with('info', 'Access status is already set to ' . ($shouldGrant ? 'Granted' : 'Revoked') . '.');
        }

        // Update Application Record
        $application->update([
            'user_accepted' => $shouldGrant
        ]);

        // Update User Record
        $user = \App\Models\User::where('email', $application->email)->first();
        
        if ($user) {
            $user->update([
                'is_scholarship_applicant' => $shouldGrant
            ]);
             
            Log::info('User scholarship access toggled via separate form', [
                'user_id' => $user->id,
                'email' => $application->email,
                'access_granted' => $shouldGrant
            ]);
        } else {
            Log::warning('User not found for access toggle', ['email' => $application->email]);
        }

        $message = $shouldGrant 
            ? 'Course access has been successfully granted to ' . $application->email 
            : 'Course access has been revoked from ' . $application->email;

        return redirect()->back()
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
            
            // PRIORITY 1: Try Hugging Face (if internet works)
            // PRIORITY 2: Fall back to Local Heuristic (always works)
            try {
                $aiProbability = $this->detectAIWithHuggingFace($text);
            } catch (\Exception $e) {
                Log::warning('Hugging Face unreachable, using local heuristic.');
                $aiProbability = $this->detectAIEnhanced($text);
            }
            
            // If Hugging Face returned 0.5 (default failure), also use heuristic
            if ($aiProbability == 0.5) {
                 $aiProbability = $this->detectAIEnhanced($text);
            }
            
            // SAVE THE RESULT TO DATABASE
            $application->update([
                'ai_detection_score' => $aiProbability,
                'ai_checked_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'ai_probability' => $aiProbability,
                'message' => 'Analysis complete.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('AI Detection Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze content.'
            ], 500);
        }
    }

    /**
     * Detect AI-generated content using Hugging Face's free API
     */
    private function detectAIWithHuggingFace($text)
    {
        // Truncate text to reasonable length (model has limits)
        $truncatedText = substr($text, 0, 512);
        
        try {
            // Using Hugging Face Inference API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('HUGGINGFACE_API_KEY'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api-inference.huggingface.co/models/roberta-base-openai-detector', [
                'inputs' => $truncatedText
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('Hugging Face Raw Response', ['result' => $result]); // Debugging log
                
                // The model returns probabilities for each class
                if (is_array($result) && !empty($result)) {
                    $aiScore = -1; // Initialize as invalid
                    
                    foreach ($result as $item) {
                        if (isset($item['label']) && isset($item['score'])) {
                            $label = strtolower($item['label']);
                            $score = (float) $item['score'];
                            
                            // Direct match for AI indicators
                            if (in_array($label, ['fake', 'ai', 'generated', 'machine'])) {
                                $aiScore = $score;
                                break; 
                            }
                            
                            // Indirect match: If it says "Real" or "Human", AI score is 1 - Real Score
                            if (in_array($label, ['real', 'human', 'original'])) {
                                $aiScore = 1 - $score;
                                break;
                            }
                        }
                    }
                    
                    // If we still haven't found a valid score, default to 0.5
                    if ($aiScore < 0 || $aiScore > 1) {
                        $aiScore = 0.5;
                    }

                    return min(max($aiScore, 0), 1);
                }
                
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
     */
    private function detectAIEnhanced($text)
    {
        $indicators = 0;
        $totalChecks = 10;
        
        // 1. Check for overly formal transition words
        $formalWords = ['furthermore', 'moreover', 'consequently', 'nevertheless', 'additionally', 'subsequently', 'henceforth'];
        $formalCount = 0;
        foreach ($formalWords as $word) {
            if (stripos($text, $word) !== false) {
                $formalCount++;
            }
        }
        if ($formalCount >= 2) $indicators++;
        
        // 2. Check sentence length uniformity
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (count($sentences) > 3) {
            $lengths = array_map('strlen', $sentences);
            $avgLength = array_sum($lengths) / count($lengths);
            $variance = array_sum(array_map(function($l) use ($avgLength) {
                return pow($l - $avgLength, 2);
            }, $lengths)) / count($lengths);
            $stdDev = sqrt($variance);
            
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
        
        // 4. Check for perfect grammar patterns
        $hasTypos = preg_match('/\b(teh|adn|taht|wiht|thier)\b/i', $text);
        if (!$hasTypos && strlen($text) > 200) {
            $indicators++;
        }
        
        // 5. Check for list-like structures
        if (preg_match_all('/^\s*[-•*]\s/m', $text) >= 3) {
            $indicators++;
        }
        
        // 6. Perplexity approximation
        $wordCount = count($words);
        $avgWordLength = array_sum(array_map('strlen', $words)) / max($wordCount, 1);
        if ($avgWordLength > 6) {
            $indicators++;
        }
        
        // 7. Check for hedging language
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
        
        // 10. Vocabulary diversity
        if ($wordCount > 20) {
            $ttr = count($uniqueWords) / $wordCount;
            if ($ttr < 0.4) {
                $indicators++;
            }
        }
        
        $probability = $indicators / $totalChecks;
        
        return min(max($probability, 0), 1);
    }
}