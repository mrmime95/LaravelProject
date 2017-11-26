@extends('layouts.app')

@section('content')
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        {{-- You are logged in {!! Auth::user()->email !!}!--}}
@endsection
