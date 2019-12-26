@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <h1><a href="{{ route('profile', $thread->creator)  }}">{{$thread -> creator -> name }}</a></h1> posted:
                                <h3>{{$thread -> title}}</h3>
                            </span>

                            @can('update', $thread)
                            <form action="{{ $thread->path() }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button typ="submit" class="btn btn-link "> Delete Thread</button>
                            </form>
                            @endcan

                        </div>
                    </div>

                    <div class="card-body">
                        {{$thread -> body}}
                    </div>
                </div>

                <replies :data="{{ $thread->replies }}"
                         @added="repliesCount++"
                         @removed="repliesCount--"></replies>
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
{{--                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>--}}
{{--                            <button class="btn btn-default">Subscribe</button>--}}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    </thread-view>
@endsection
