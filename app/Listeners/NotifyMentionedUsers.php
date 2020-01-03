<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {

        $users = User::whereIn('name', $event->reply->mentionedUsers())
                 ->get()
                 ->each(function($user) use ($event){
                   $user->notify(new YouWereMentioned($event->reply));
                 });


//========METODO 2==========
//        collect($event->reply->mentionedUsers())
//                ->map(function($name){
//                    return User::where('name', $name)->first();
//                })
//                ->filter()
//                ->each(function($user) use ($event){
//                   $user->notify(new YouWereMentioned($event->reply));
//                });

        //dd($matches); //to see matches
//========METODO UNO=========
        //$mentionedUsers = $event->reply->mentionedUsers();

//        foreach ($mentionedUsers as $name) {
//            if ($user = User::whereName('name', $name)->first()) {
//                $user->notify(new YouWereMentioned($event->reply));
//            }
//        }
    }
}
