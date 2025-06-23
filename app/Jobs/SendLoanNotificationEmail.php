<?php

namespace App\Jobs;

use App\Models\ToolLoan;
use App\Mail\LoanNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLoanNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(
        public ToolLoan $loan,
        public string $type
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->loan->user->email)
                ->send(new LoanNotificationMail($this->loan, $this->type));

            Log::info('Loan notification email sent successfully', [
                'loan_id' => $this->loan->id,
                'user_email' => $this->loan->user->email,
                'type' => $this->type
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send loan notification email', [
                'loan_id' => $this->loan->id,
                'user_email' => $this->loan->user->email,
                'type' => $this->type,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Loan notification email job failed permanently', [
            'loan_id' => $this->loan->id,
            'user_email' => $this->loan->user->email,
            'type' => $this->type,
            'error' => $exception->getMessage()
        ]);
    }
}
