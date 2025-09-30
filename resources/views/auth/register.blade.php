@extends('layouts.auth')

@section('title', 'Регистрация')

@section('content')
    <x-forms.auth-forms title="Регистрация" action="{{ route('register') }}" method="POST">
        @csrf

        <x-forms.text-input
            name="name"
            value="{{ old('name') }}"
            placeholder="Имя"
            required="true"
            :isError="$errors->has('name')"
        />
        @error('name')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.text-input
            name="email"
            value="{{ old('email') }}"
            type="email"
            placeholder="E-mail"
            required="true"
            :isError="$errors->has('email')"
        />
        @error('email')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.text-input
            name="password"
            type="password"
            placeholder="Пароль"
            required="true"
            :isError="$errors->has('password')"
        />
        @error('password')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.text-input
            name="password_confirmation"
            type="password"
            placeholder="Повторите пароль"
            required="true"
            :isError="$errors->has('password_confirmation')"
        />
        @error('password_confirmation')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.primary-button>
            Регистрация
        </x-forms.primary-button>

        <x-slot:socialAuth>
            <x-forms.social-auth></x-forms.social-auth>
        </x-slot:socialAuth>

        <x-slot:buttons>
            <div class="space-y-3 mt-5">
                <div class="text-xxs md:text-xs">
                    <a href="{{ route('login') }}"
                       class="text-white hover:text-white/70 font-bold">
                        Вход в аккаунт
                    </a>
                </div>
            </div>
        </x-slot:buttons>
    </x-forms.auth-forms>
@endsection
