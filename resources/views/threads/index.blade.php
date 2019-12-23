@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                @forelse($threads as $thread)
                <div class="card">
                    <div class="card-header">

                        <div class="level">
                            <h4 class="flex">
                                <a href="{{ $thread ->path() }}">
                                    {{$thread -> title}}
                                </a>
                            </h4>
                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} replies
                            </a>
                        </div>

                    </div>
                        <div class="card-body">
                                <div class="body">{{$thread -> body}}</div>
                        </div>
                    </div>
                </div>
                @empty
                <p>No results by now</p>


                @endforelse
            </div>
        </div>
    </div>
@endsection
