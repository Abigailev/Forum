@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :thread="{{$thread}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8" v-cloak>
                @include('threads._question')

                <replies :data="{{ $thread->replies }}"
                         @added="repliesCount++"
                         @removed="repliesCount--"></replies>
            </div>
{{--                @foreach ($replies as $reply)--}}
{{--                    @include('threads.reply')--}}
{{--                @endforeach--}}

{{--                {{ $replies->links() }}--}}




{{--                @if (auth()->check())--}}

{{--                    <form method="POST" action="{{$thread->path(). 'replies'}}">--}}
{{--                        {{csrf_field()}}--}}

{{--                        <div class="form-group">--}}
{{--                            <textarea name="body" id="body" class="form-control" placeholder="Wanna leave a comment?" rows="4"></textarea>--}}
{{--                        </div>--}}

{{--                        <button type="submit" class="btn btn-default">Post</button>--}}
{{--                    </form>--}}
{{--                @else--}}
{{--                    <p class="text-center">Please <a href="{{ route('login') }}">sign in </a> to participate on the disscusion</p>--}}
{{--                @endif--}}
{{--            </div>--}}

            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <p>
                            This thread was published {{$thread-> created_at -> diffForHumans()}}
                            by <a href="{{ route('profile', $thread->creator)  }}">{{$thread->creator->name}}</a>
                            and currently has <span v-text="repliesCount"></span>   comments.
                        </p>
                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>
                            <button class="btn btn-default"
                                    v-if="authorize('isAdmin')"
                                    @click="toggleLock"
                                    v-text="'locked'? 'Unlock' : 'Lock'">Lock</button>
                            {{--                            <button class="btn btn-default">Subscribe</button>--}}
                        </p>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-xs" @click="editing = true">Edit</button>
                    </div>

                </div>
            </div>

    </div>
    </div>
    </thread-view>
@endsection
