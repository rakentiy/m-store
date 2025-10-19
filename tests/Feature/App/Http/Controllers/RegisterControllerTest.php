<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\RegisterFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;

it('shows register form', function () {
    $this->get(action([RegisterController::class, 'showRegisterForm']))
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
        action([RegisterController::class, 'register']),
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
