<?php

namespace App\Http\Controllers;


use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\Trending;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;


class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
        //or the reverse would be//$this->middleware('auth')->only(['create','store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson())
        {
            return $threads;
        }

        //$trending = array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));
        //return view('threads.index', compact('threads', 'trending'));

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//eso lo puse en un middleware
//        if(! auth()->user()->confirmed){
//            return redirect('/threads')->with('flash','You must confirm your email address.');
//        }

        $this-> validate($request, [
           'title'=>  'required|spamfree',
           'body' =>  'required|spamfree',
           'channel_id' => 'required|exists:channels,id'
        ]);


        //dd($request->all());
        $thread = Thread::create([
           'user_id' => auth()->id(),
           'channel_id' => request('channel_id'),
           'title' => request('title'),
           'body' => request('body'),
           //'slug' => Str::slug(request('title'))
           //'slug' => request('title')
        ]);

        if(request()->wantsJson()){
            return response($thread, 201);
        }

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {

        if(auth()->check()){
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        //$thread->visits()->record();
        $thread->increment('visits');

        return view('threads.show', compact('thread'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, Thread $thread)
    public function update($channel, Thread $thread)
    {
        //es lo mismo pero a este si le entiendo xd
//        $data = request()->validate([
//           'title' => 'required|spamfree',
//           'body'  => 'required|spamfree'
//        ]);
//        $thread->update($data);
        $this->authorize('update', $thread);

        //se le pone el tap porque el request regresa en booleano y ps truena(en caso de que se pusiera directamente un return)
        $thread->update(request()->validate([
            'title' => 'required|spamfree',
            'body'  => 'required|spamfree'
        ]));

        return $thread;

//        $thread->update(request(['body', 'title']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {

        $this->authorize('update', $thread);
        $thread->delete();

        if(request()->wantsJson()){
            return response([],204);
        }
        return redirect('/threads');

    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
//        $threads = Thread::latest()->filter($filters);
//
//        if ($channel->exists) {
//            $threads->where('channel_id', $channel->id);
//        }
//
//        $threads = $threads->get();
//        return $threads;

        $threads = Thread::filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->latest()->paginate(5);
        //between the $threads and the latest() was a 'with('channel')'
    }

}
