<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $plainPassword;

    public function __construct($student, $plainPassword, $email)
    {
        $this->student = $student;
        $this->student = $email;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Your Student Account Credentials')
                    ->view('emails.student_password');
    }
}
