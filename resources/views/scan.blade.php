@extends('layouts.app')

@section ('content')

{{--    <div id="app">--}}
{{--        <my-search />--}}
{{--    </div>--}}
{{--    <script src="{{ mix('js/app.js') }}"></script>--}}


{{--    <ais-instant-search--}}
{{--        :search-client="searchClient"--}}
{{--        index-name="contacts"--}}
{{--    >--}}
{{--        <ais-search-box placeholder="Search contacts..."></ais-search-box>--}}
{{--        <ais-hits></ais-hits>--}}
{{--    </ais-instant-search>--}}

{{--    <template>--}}
{{--        <ais-instant-search index-name="demo_ecommerce" :search-client="searchClient">--}}
{{--            <div class="left-panel">--}}
{{--                <ais-clear-refinements />--}}
{{--                <h2>Brands</h2>--}}
{{--                <ais-refinement-list attribute="brand" searchable />--}}
{{--                <ais-configure :hitsPerPage="8" />--}}
{{--            </div>--}}
{{--            <div class="right-panel">--}}
{{--                <ais-search-box />--}}
{{--                <ais-hits>--}}
{{--                    <div slot="item" slot-scope="{ item }">--}}
{{--                        <h2>{{ item.name }}</h2>--}}
{{--                    </div>--}}
{{--                </ais-hits>--}}
{{--                <ais-pagination />--}}
{{--            </div>--}}
{{--        </ais-instant-search>--}}
{{--    </template>--}}

    <ais-index
        app-id="{{config('scout.algolia.id')}}"
        api-key="{{config('scout.algolia.key')}}"
        index-name="threads"
    >
        <ais-search-box></ais-search-box>

        <ais-refinement-list attribut-name="channel.name"></ais-refinement-list>

        <ais-results>
            <template scope="{ result }">
                <h2>
                    <a href="result.path">
                        <ais-highlight :result="result" attriute-name="title"></ais-highlight>
                    </a>
                </h2>
            </template>
        </ais-results>
    </ais-index>
@endsection


