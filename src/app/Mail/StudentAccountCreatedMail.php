<?php
// app/Mail/StudentAccountCreatedMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $username,
        public string $temporaryPassword
    ) {}

    public function build()
    {
        return $this->subject('Your Student Portal Account')
            ->view('emails.student-account-created');
    }
}