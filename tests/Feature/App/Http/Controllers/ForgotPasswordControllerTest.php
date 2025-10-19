<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

it('shows the forgot password form', function () {
    $this->get(action([ForgotPasswordController::class, 'showLinkRequestForm']))
        ->assertOk()
        ->assertSee('Забыли пароль?')
        ->assertViewIs('auth.forgot-password');
});

it('sends a password reset link', function () {
    Notification::fake();
    Event::fake();
    $user = UserFactory::new()->create(['email' => 'testing@gmail.com']);

    $this->post(action([ForgotPasswordController::class, 'sendResetLink']), [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('does not send a reset link for invalid email', function () {
    UserFactory::new()->create(['email' => 'testing@gmail.com']);

    $response = $this->post(action([ForgotPasswordController::class, 'sendResetLink']), [
        'email' => 'nonexistent@example.com',
    ]);

    $response->assertSessionHasErrors(['email']);
});
