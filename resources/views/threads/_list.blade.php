@forelse($threads as $thread)
    <div class="card">
        <div class="card-header">

            <div class="flex">
                <h4 class="flex">
                    <a href="{{ $thread ->path() }}">
                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong> {{$thread -> title}} </strong>
                        @else
                            {{$thread -> title}}
                        @endif
                    </a>
                </h4>

                <h5>Posted by: <a href=" {{ route('profile', $thread->creator) }} ">{{ $thread->creator->name }}</a> </h5>
            </div>

            <a href="{{ $thread->path() }}">
                {{ $thread->replies_count }} replies
            </a>
        </div>
        <div class="card-body">
            <div class="body">{{$thread -> body}}</div>
        </div>

        <div class="card-footer">
           {{ $thread->visits}} Visits
        </div>

    </div>
    </div>
    </br>
@empty
    <p>No results by now</p>


@endforelse
