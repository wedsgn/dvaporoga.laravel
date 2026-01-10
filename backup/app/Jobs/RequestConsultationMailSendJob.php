<?php

namespace App\Jobs;

use App\Mail\RequestConsultationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RequestConsultationMailSendJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * @var array
   */
  protected $details;

  /**
   * Create a new job instance.
   *
   * @param array $details
   */
  public function __construct(array $details)
  {
    $this->details = $details;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $to = config('mail.to.address');
    if (empty($to)) {
      \Log::error('MAIL_TO_ADDRESS is empty in config.');
      return;
    }

    Mail::to($to)->queue(new RequestConsultationMail($this->details));
  }
}
