@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Create new Threads</div>

                        <div class="panel-body">
                            <form method="POST" action="/threads">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="channel_id">Choose a channel:</label>
                                    <select name="channel_id" id="channel_id" class="form-control" required>
                                              <option value="">Select one channel</option>
                                        @foreach($channels as $channel)
                                            <option value="{{$channel->id}}"{{ old ('channel_id') == $channel->id ? 'selected' : ''}}>
                                                {{$channel->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                     <label for="title">TITLE:</label>
                                     <input type="text" class="form-control" id="title" name="title" placeholder="title" value="{{old('title')}}" required>
                                </div>

                                <div class="form-group">
                                     <label for="title">BODY:</label>
                                     <wysiwyg name="body" :value="form.body"></wysiwyg>
{{--                                     <textarea name="body" id="body" class="form-control" rows="8" required>{{old('body')}}</textarea>--}}
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Publish</button>
                                </div>

                                @if (count($errors))
                                    <ul class="alert alert-danger">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
