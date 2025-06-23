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
        public string $type // 'approved', 'delivered', 'overdue', 'returned'
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->loan->user->email)
                ->send(new LoanNotificationMail($this->loan, $this->type));

            Log::info('Loan notification email sent successfully', [
                'loan_id' => $this->loan->id,
                'user_id' => $this->loan->user_id,
                'type' => $this->type
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send loan notification email', [
                'loan_id' => $this->loan->id,
                'user_id' => $this->loan->user_id,
                'type' => $this->type,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
