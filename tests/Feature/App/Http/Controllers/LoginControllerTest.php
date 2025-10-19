<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\LoginFormRequest;
use Database\Factories\UserFactory;

it('shows the login form', function () {
    $response = $this->get(action([LoginController::class, 'showLoginForm']));
    $response->assertOk();
    $response->assertSee('Вход в аккаунт');
    $response->assertViewIs('auth.login');
});

it('logs in a user with correct credentials', function () {
    $request = LoginFormRequest::factory()->create();

    $user = UserFactory::new()->create($request);

    $response = $this->post(action([LoginController::class, 'login']), $request);

    $response
        ->assertValid()
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

it('does not log in with incorrect credentials', function () {
    $request = LoginFormRequest::factory()->create();

    UserFactory::new()->create($request);

    $request['password'] = 'wrongpassword';

    $response = $this->post(action([LoginController::class, 'login']), $request);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

it('logs out a user', function () {
    $user = UserFactory::new()->create();

    $this
        ->actingAs($user)
        ->delete(action([LoginController::class, 'logout']));

    $this->assertGuest();
});
