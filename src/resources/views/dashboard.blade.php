@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Include WatchDogs list, passing variables --}}
    @include('watchdogs.index', [
        'user' => $user,
        'watchdogs' => $watchdogs
    ])
</div>
@endsection
