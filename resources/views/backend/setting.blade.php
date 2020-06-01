@extends('backend.layouts.app')

@section('content')
    <div class="container">
        @if(Session::has('status'))
            <div class="alert alert-info">
                <span>{{Session::get('status')}}</span>
            </div>
        @endif
        <form action="{{route('admin.setting.store')}}" method="post">
            @csrf
            <div class="form-group">
                <label for=""> URL callback for Telegram Bot</label>
                <div class="input-group">
                    <div class="input-group-btn">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onClick="document.getElementById('url_callback_bot').value='{{url('')}}'">Put Url</a>
                            <a class="dropdown-item" href="#" onClick="event.preventDefault();document.getElementById('setwebhook').submit()">Send Url </a>
                            <a class="dropdown-item" href="#" onClick="event.preventDefault();document.getElementById('getwebhookinfo').submit()">Get Info</a>
                        </div>
                    </div>
                    <input type="url" name='url_callback_bot' value="{{$url_callback_bot ?? ''}}" class="form-control" aria-label="Text input with dropdown button" id="url_callback_bot">
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>

            <form id="setwebhook" action="{{route('admin.setting.setwebhook')}}" method="POST" style="display: none">
                @csrf
                <input type="hidden" name="url" value="{{ $url_callback_bot ?? ''  }}">
            </form>
            <form id="getwebhookinfo" action="{{route('admin.setting.getwebhookinfo')}}" method="POST" style="display: none">
                @csrf
            </form>
    </div>
@endsection
