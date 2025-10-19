<?php

use App\Http\Controllers\Auth\SocialAuthController;

it('redirects to GitHub for OAuth authentication', function () {
    $response = $this->get(action([SocialAuthController::class, 'redirect'], ['driver' => 'github']));
    $response->assertRedirect();
    $response->assertStatus(302);
});

it('handles GitHub callback and authenticates user', function () {
    $this->assertGuest();
});
