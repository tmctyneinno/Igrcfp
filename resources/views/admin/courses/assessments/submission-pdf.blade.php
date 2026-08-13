<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" text/html; charset=utf-8"/>
    <title>Submission Review - {{ $assessment->title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; font-size: 12px; }
        .header { border-bottom: 2px solid #0A1F44; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #0A1F44; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .section-title { background-color: #f3f4f6; padding: 8px; border-left: 4px solid #0A1F44; margin-top: 20px; margin-bottom: 10px; font-weight: bold; color: #0A1F44; }
        
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; padding: 4px; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; width: 120px; color: #555; }
        
        .question-box { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 4px; page-break-inside: avoid; }
        .question-text { font-weight: bold; margin-bottom: 5px; }
        .answer-box { background-color: #f9fafb; padding: 8px; border: 1px solid #eee; margin-top: 5px; white-space: pre-wrap; }
        
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; color: white; }
        .bg-success { background-color: #10b981; }
        .bg-danger { background-color: #ef4444; }
        .bg-primary { background-color: #3b82f6; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; height: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Assessment Submission Review</h1>
        <p>{{ $assessment->course->title }} | {{ $assessment->title }}</p>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell"><span class="label">Student:</span> {{ $submission->user->name }}</div>
            <div class="info-cell"><span class="label">Email:</span> {{ $submission->user->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><span class="label">Submitted:</span> {{ $submission->submitted_at?->format('M d, Y H:i') }}</div>
            <div class="info-cell"><span class="label">Status:</span> {{ ucfirst($submission->status) }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><span class="label">Score:</span> 
                @if($submission->percentage !== null)
                    {{ number_format($submission->percentage, 1) }}% 
                    ({{ number_format(($submission->percentage / 100) * ($assessment->total_marks ?? 100), 1) }}/{{ $assessment->total_marks }} pts)
                @else
                    Pending Grading
                @endif
            </div>
            <div class="info-cell"><span class="label">Graded By:</span> {{ $submission->grader->name ?? 'Not Graded' }}</div>
        </div>
    </div>

    @if($submission->feedback)
        <div style="background-color: #eff6ff; padding: 10px; border: 1px solid #bfdbfe; margin-bottom: 20px;">
            <strong>Examiner Feedback:</strong><br>
            {{ $submission->feedback }}
        </div>
    @endif

    <!-- Part A: MCQ -->
    @if($mcqQuestions->isNotEmpty())
        <div class="section-title">Part A: Quiz Responses ({{ $mcqQuestions->count() }})</div>
        @foreach($mcqQuestions as $item)
            @php
                $q = $item['question'];
                $r = $item['response'] ?? [];
                $isCorrect = is_array($r) ? ($r['correct'] ?? false) : (is_object($r) ? ($r->correct ?? false) : false);

                // Normalize answer
                $rawAnswer = null;
                if (is_array($r)) {
                    $rawAnswer = $r['answer'] ?? $r['response'] ?? $r['answers'] ?? null;
                } elseif (is_object($r)) {
                    $rawAnswer = $r->answer ?? $r->response ?? $r->answers ?? null;
                }

                $answerText = null;
                $options = $q->options ?? null;
                if (is_string($options)) {
                    $decoded = json_decode($options, true);
                    if (json_last_error() === JSON_ERROR_NONE) $options = $decoded;
                }

                if (!is_null($rawAnswer) && $rawAnswer !== '') {
                    if (is_array($options) && count($options) > 0) {
                        if (array_values($options) === $options) {
                            if (is_numeric($rawAnswer) && isset($options[(int)$rawAnswer])) {
                                $answerText = $options[(int)$rawAnswer];
                            } else {
                                foreach ($options as $opt) {
                                    if ((string)$opt === (string)$rawAnswer) { $answerText = $opt; break; }
                                    if (is_array($opt)) {
                                        if ((isset($opt['id']) && (string)$opt['id'] === (string)$rawAnswer) || (isset($opt['value']) && (string)$opt['value'] === (string)$rawAnswer)) {
                                            $answerText = $opt['label'] ?? $opt['text'] ?? $opt['value'] ?? null; break;
                                        }
                                    }
                                }
                            }
                        } else {
                            if (isset($options[$rawAnswer])) $answerText = $options[$rawAnswer];
                        }
                    }

                    if (!$answerText) {
                        if (is_array($rawAnswer)) {
                            $answerText = implode(', ', array_map('strval', $rawAnswer));
                        } else {
                            $answerText = (string) $rawAnswer;
                        }
                    }
                }

                $displayAnswer = $answerText ?? 'No answer provided';
            @endphp
            <div class="question-box">
                <div class="question-text">Q{{ $loop->iteration }}: {{ $q->question_text }}</div>
                <div>
                    <strong>Answer:</strong> {{ $displayAnswer }} 
                    <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }}">
                        {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                    </span>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Part B: Essays -->
    @if($essayQuestions->isNotEmpty())
        <div class="section-title">Part B: Essay Responses ({{ $essayQuestions->count() }})</div>
        @foreach($essayQuestions as $item)
            @php
                $q = $item['question'];
                $r = $item['response'] ?? [];
                $answerText = $r['answer'] ?? 'No answer provided.';
            @endphp
            <div class="question-box">
                <div class="question-text">Q{{ $loop->iteration }}: {{ $q->question_text }} <span style="font-weight:normal; font-size:10px; color:#666;">({{ $q->points }} pts)</span></div>
                <div class="answer-box">{!! $answerText !!}</div>
                
                @if(!empty($q->explanation))
                    <div style="margin-top: 5px; font-size: 10px; color: #0369a1;">
                        <strong>Model Answer:</strong> {{ $q->explanation }}
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Part C: Projects -->
    @if($projectQuestions->isNotEmpty() || $uploadedFiles->isNotEmpty())
        <div class="section-title">Part C: Project / Case Study</div>
        
        @if($uploadedFiles->isNotEmpty())
            <div style="margin-bottom: 10px;">
                <strong>Uploaded Files:</strong>
                <ul>
                    @foreach($uploadedFiles as $file)
                        <li>{{ $file['name'] ?? 'Document' }} ({{ round(($file['size'] ?? 0)/1024, 2) }} KB)</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @foreach($projectQuestions as $item)
            <div class="question-box">
                <div class="question-text">{{ $item['question']->question_text }}</div>
                <div class="answer-box">{{ $item['response']['answer'] ?? 'No text response.' }}</div>
            </div>
        @endforeach
    @endif

    <div class="footer">
        Generated on {{ now()->format('M d, Y H:i') }} | IGRCFP Administration System
    </div>

</body>
</html>