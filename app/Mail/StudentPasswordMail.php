<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;

class StudentPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $plainPassword;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, $plainPassword, $email)
    {
        $this->student = $student;
        $this->password = $plainPassword;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Student Account Credentials')
                    ->view('emails.student_password')
                    ->with([
                        'student' => $this->student,
                        'password' => $this->password,
                        'email' => $this->email,
                    ]);
    }
}
