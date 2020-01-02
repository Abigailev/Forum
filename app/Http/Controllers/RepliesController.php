<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostForm;
use App\Reply;
use App\Thread;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    //protection
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread){
         return $thread->replies()->paginate(1);
    }

    public function store($channelId, Thread $thread, CreatePostForm $form)
    {

            return $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ])->load('owner');

    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
//        if($reply->user_id != auth()->id()){
//            return response([], 403);
//        }

        $reply->delete();

        if(request()->expectsJson()){
           return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply );

        try {
            $this->validateReply();

            //$reply->update(['body' => request('body')]);
            $reply->update(request(['body']));
        } catch(\Exception $e){
            return response(
                'Sorry, your reply could not be saved at this moment.', 422
            );
        }
    }

}
