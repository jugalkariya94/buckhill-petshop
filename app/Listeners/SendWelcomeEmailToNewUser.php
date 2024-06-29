<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class SendWelcomeEmailToNewUser
 * @package App\Listeners
 * Listener that sends a welcome email to a new user.
 *
 */
class SendWelcomeEmailToNewUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        //
        // Send a welcome email to the user
        // TODO: Implement the logic to send the email in future
        // For now, I'll keep in empty
        // Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}
