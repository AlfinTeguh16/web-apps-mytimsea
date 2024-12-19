<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class SendOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $mailData;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param array $mailData
     */
    public function __construct($email, $mailData)
    {
        $this->email = $email;
        $this->mailData = $mailData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            \Log::info("Sending OTP to {$this->email} with data: " . json_encode($this->mailData));
            Mail::to($this->email)->send(new OtpMail($this->mailData));
            \Log::info("OTP email successfully sent to {$this->email}");
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP to {$this->email}: " . $e->getMessage());
        }
    }
}
