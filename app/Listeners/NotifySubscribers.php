<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        //POR SI ACASO, ESTE SI SIRVE
//        $thread = $event->reply->thread;
//        $thread->subscriptions
//            ->where('user_id', '!=', $event->reply->user_id)
//            ->each
//            ->notify($event->reply);

        $event->reply->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
