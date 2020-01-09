<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestRepliesController extends Controller
{
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        //usa este si no quieres usar los policies de laravel, solo en el test pon el 401 tmn en vez del 403
        //abort_if($reply->thread->user_id != auth()->id(), 401);

        //$reply->thread->update(['best_reply_id' => $reply->id]);
        $reply->thread->markBestReply($reply);
    }

}
