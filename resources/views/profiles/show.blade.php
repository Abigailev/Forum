@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="page-header">

                    <avatar-form :user="{{$profileUser}}"></avatar-form>

                    <h1>
                        <img src="{{asset($profileUser->avatar())}}" width="50" height="50"> {{ $profileUser->name }}
                    </h1>

                    @can('update', $profileUser)
                        <form method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="file" name="avatar">
                            <button type="submit" class="btn btn-primary">Add Avatar</button>
                        </form>
                    @endcan

                    <small> Has been here since {{ $profileUser->created_at->diffForHumans() }}</small>
                </div>

                @foreach($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>
                    @foreach($activity as $record)
                        @if(view()->exists("profiles.activities.{$record->type}"))
                    @include ("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @endforeach

            </div>

        </div>
    </div>

@endsection

