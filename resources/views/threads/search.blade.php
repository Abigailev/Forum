@extends('layouts.app')

@section('content')
    <div class="container">

        <ais-index
            app-id="{{config('scout.algolia.id')}}"
            api-key="{{config('scout.algolia.key')}}"
            index-name="threads"
            query="{{request('q')}}"
        >

            <div class="col-md-8 ">
                <ais-state-results>
                    <template scope="{result}">
                        <li>
                            <a :href="result.path">
                                <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                            </a>
                        </li>
                    </template>
                </ais-state-results>
            </div>

            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header">
                        Searched threads
                    </div>

                    <div class="card-body">
                        <ais-search-box>
                            <ais-input placeholder="Find a thread..." :autofocus="true" class="form-control"></ais-input>
                        </ais-search-box>
                    </div>
                </div>

                <div class="card ">
                    <div class="card-header">
                        Filter by channel
                    </div>

                    <div class="card-body">
                        <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
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

        </ais-index>
@endsection
