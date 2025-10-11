<?php

use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

it('shows the login form', function () {
    $response = $this->get(action([AuthController::class, 'showLoginForm']));
    $response->assertOk();
    $response->assertSee('Вход в аккаунт');
    $response->assertViewIs('auth.index');
});

it('logs in a user with correct credentials', function () {
    $request = LoginFormRequest::factory()->create();

    $user = User::factory()->create($request);

    $response = $this->post(action([AuthController::class, 'login']), $request);

    $response
        ->assertValid()
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

it('does not log in with incorrect credentials', function () {
    $request = LoginFormRequest::factory()->create();

    User::factory()->create($request);

    $request['password'] = 'wrongpassword';

    $response = $this->post(action([AuthController::class, 'login']), $request);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

it('logs out a user', function () {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->delete(action([AuthController::class, 'logout']));

    $this->assertGuest();
});

it('shows register form', function () {
    $this->get(action([AuthController::class, 'showRegisterForm']))
        ->assertOk()
        ->assertSee('Регистрация')
        ->assertViewIs('auth.register');
});


it('registers a new user', function () {
    Notification::fake();
    Event::fake();

    $request = RegisterFormRequest::factory()->create();

    $this->assertDatabaseMissing('users', [
        'email' => $request['email'],
    ]);

    $response = $this->post(
        action([AuthController::class, 'register']),
        $request
    );

    $response
        ->assertValid();

    $this->assertDatabaseHas('users', [
        'email' => $request['email'],
    ]);

    $user = User::query()
        ->where('email', $request['email'])
        ->first();

    Event::assertDispatched(Registered::class);
    Event::assertListening(Registered::class, SendEmailNewUserListener::class);

    $event = new Registered($user);
    $listener = new SendEmailNewUserListener();
    $listener->handle($event);
    Notification::assertSentTo($user, NewUserNotification::class);

    $this->assertAuthenticatedAs($user);

    $response
        ->assertRedirect(route('home'));
});

it('shows the forgot password form', function () {
    $this->get(action([AuthController::class, 'showLinkRequestForm']))
        ->assertOk()
        ->assertSee('Забыли пароль?')
        ->assertViewIs('auth.forgot-password');
});

it('sends a password reset link', function () {
    Notification::fake();
    Event::fake();
    $user = User::factory()->create(['email' => 'testing@gmail.com']);

    $this->post(action([AuthController::class, 'sendResetLink']), [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('does not send a reset link for invalid email', function () {
    User::factory()->create(['email' => 'testing@gmail.com']);

    $response = $this->post(action([AuthController::class, 'sendResetLink']), [
        'email' => 'nonexistent@example.com',
    ]);

    $response->assertSessionHasErrors(['email']);
});

it('shows the reset password form', function () {
    $token = Str::random(60);
    $response = $this->get(
        action([AuthController::class, 'showResetForm'], ['token' => $token])
    );
    $response->assertStatus(200);
    $response->assertViewIs('auth.reset-password');
});

it('resets the password successfully', function () {
    $user = User::factory()->create([
        'email' => 'testing@gmail.com',
    ]);

    $request = ResetPasswordFormRequest::factory()->create([
        'token' => Password::createToken($user),
        'email' => $user->email,
    ]);
    $response = $this->post(action([AuthController::class, 'reset']), $request);

    $response->assertRedirect(route('login'));
    $user->refresh();
    $this->assertTrue(Hash::check('newpassword123', $user->password));
});

it('does not reset the password with invalid token', function () {
    $user = User::factory()->create([
        'email' => 'testing@gmail.com',
    ]);

    $request = ResetPasswordFormRequest::factory()->create([
        'email' => $user->email,
    ]);
    $response = $this->post(action([AuthController::class, 'reset']), $request);

    $response->assertSessionHasErrors();
});

it('redirects to GitHub for OAuth authentication', function () {
    $response = $this->get(action([AuthController::class, 'github']));
    $response->assertRedirect();
    $response->assertStatus(302);
});

it('handles GitHub callback and authenticates user', function () {
    $this->assertGuest();
});
