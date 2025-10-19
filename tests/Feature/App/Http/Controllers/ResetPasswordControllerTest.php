<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Requests\ResetPasswordFormRequest;
use Database\Factories\UserFactory;

it('shows the reset password form', function () {
    $token = Str::random(60);
    $response = $this->get(
        action([ResetPasswordController::class, 'showResetForm'], ['token' => $token])
    );
    $response->assertStatus(200);
    $response->assertViewIs('auth.reset-password');
});

it('resets the password successfully', function () {
    $user = UserFactory::new()->create([
        'email' => 'testing@gmail.com',
    ]);

    $request = ResetPasswordFormRequest::factory()->create([
        'token' => Password::createToken($user),
        'email' => $user->email,
    ]);
    $response = $this->post(action([ResetPasswordController::class, 'reset']), $request);

    $response->assertRedirect(route('login'));
    $user->refresh();
    $this->assertTrue(Hash::check('newpassword123', $user->password));
});

it('does not reset the password with invalid token', function () {
    $user = UserFactory::new()->create([
        'email' => 'testing@gmail.com',
    ]);

    $request = ResetPasswordFormRequest::factory()->create([
        'email' => $user->email,
    ]);
    $response = $this->post(action([ResetPasswordController::class, 'reset']), $request);

    $response->assertSessionHasErrors();
});
