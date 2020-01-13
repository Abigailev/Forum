@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify">
            <div class="col-md-8 ">
                @include('threads._list')

                {{ $threads->render() }}

            </div>


            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header">
                        Search
                    </div>

                    <div class="card-body">
                        <form method="GET" action="/threads/search">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." name="q">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">SEARCH</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if(count($trending))
                <div class="card ">
                    <div class="card-header">
                        Trending threads
                    </div>

                    <div class="card-body">

                        <ul class="list-group">
                            @foreach($trending as $thread)
                                <li>
                                    <a href="{{ url($thread->path) }}"> {{ $thread->title }} </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
@endsection
