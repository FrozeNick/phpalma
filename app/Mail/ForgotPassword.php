<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param  \App\User  $order
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@alma.com', 'Alma Support')
                    ->subject('Reset your password on Alma!')
                    ->view('emails.users.resetPassword')
                    ->with([
                        'user' => $this->user,
                        'code' => $this->user->generateFpCode()
                    ]);
    }
}