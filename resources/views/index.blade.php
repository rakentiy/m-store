@extends('layouts.app')

@section('content')
    @auth()
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            @method('DELETE')
            <button type="submit">Выйти</button>
        </form>
    @endauth
    @guest
        <a href="{{ route('login') }}">Login</a>
    @endguest
@endsection
