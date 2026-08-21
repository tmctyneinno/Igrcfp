<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExaminerReportUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $assessmentTitle,
        public string $reportName,
    ) {
    }

    public function build()
    {
        return $this->subject('Examiner Report Uploaded - ' . $this->assessmentTitle)
            ->view('emails.examiner-report-uploaded');
    }
}
