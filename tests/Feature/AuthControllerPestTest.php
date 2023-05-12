<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\Enums\RootUser;
use Tests\TestCase;
use function Pest\Laravel\postJson;

uses(TestCase::class, RefreshDatabase::class);

it('the user can login', function () {
    // Create a user with a specific email address.
    User::factory()->create([
        'email' => RootUser::EMAIL->value,
        'password' => Hash::make(RootUser::PASS->value),
    ]);

    // Send a request with prepared data.
    $response = postJson(route('auth.login'), [
        'email' => RootUser::EMAIL->value,
        'password' => RootUser::PASS->value,
    ]);

    // Check that response has a "200" status.
    $response->assertStatus(Response::HTTP_OK);

    // Check that response structure.
    $response->assertJsonStructure(['accessToken']);
});

it('the user cannot login using wrong email', function () {
    // Set expect exception.
    $this->expectException(ValidationException::class);

    // Create a user with a specific data.
    User::factory()->create([
        'email' => RootUser::EMAIL->value,
        'password' => Hash::make(RootUser::PASS->value),
    ]);

    // Send a request with prepared data.
    $response = postJson(route('auth.login'), [
        'email' => 'wrong.email@twirelab.com',
        'password' => RootUser::PASS->value,
    ]);

    // Check if the request returns an error for the "email" input.
    $response->assertJsonValidationErrorFor('email');
});

it('the user cannot login using wrong password', function () {
    // Set expect exception.
    $this->expectException(ValidationException::class);

    // Create a user with a specific data.
    User::factory()->create([
        'email' => RootUser::EMAIL->value,
        'password' => Hash::make(RootUser::PASS->value),
    ]);

    // Send a request with prepared data.
    $response = postJson(route('auth.login'), [
        'email' => RootUser::EMAIL->value,
        'password' => 'This1sWrongP@ss',
    ]);

    // Check if the request returns an error for the "email" input.
    $response->assertJsonValidationErrorFor('email');
});

it('the unverified user cannot login', function () {
    // Set expect exception.
    $this->expectException(ValidationException::class);

    // Create a user with a specific data.
    User::factory()->unverified()->create([
        'email' => RootUser::EMAIL->value,
        'password' => Hash::make(RootUser::PASS->value),
    ]);

    // Send a request with prepared data.
    $response = postJson(route('auth.login'), [
        'email' => RootUser::EMAIL->value,
        'password' => RootUser::PASS->value,
    ]);

    // Check if the request returns an error for the "email" input.
    $response->assertJsonValidationErrorFor('email');
});

it('the user can register an account', function () {
    // Send a request with prepared data.
    $response = postJson(route('auth.register'), [
        'name' => RootUser::NAME->value,
        'email' => RootUser::EMAIL->value,
        'password' => RootUser::PASS->value,
    ]);

    // Check that response has a "201" status.
    $response->assertStatus(Response::HTTP_CREATED);

    // Check that user exists in the database.
    $this->assertEquals(true, User::query()->where('email', RootUser::EMAIL->value)->exists());
});

it('the user cannot register with short pass', function () {
    // Set expect exception.
    $this->expectException(ValidationException::class);

    // Send a request with prepared data.
    $response = postJson(route('auth.register'), [
        'name' => RootUser::NAME->value,
        'email' => RootUser::EMAIL->value,
        'password' => 'Short',
    ]);

    // Check if the request returns an error for the "password" input.
    $response->assertJsonValidationErrorFor('password');

    // Check that user does not exist in the database.
    $this->assertEquals(false, User::query()->where('email', RootUser::EMAIL->value)->exists());
});

it('the user cannot register with a non unique email address', function () {
    // Set expect exception.
    $this->expectException(ValidationException::class);

    // Create a user with a specific email address.
    User::factory()->create([
        'email' => RootUser::EMAIL->value,
    ]);

    // Send a request with prepared data.
    $response = postJson(route('auth.register'), [
        'name' => RootUser::NAME->value,
        'email' => RootUser::EMAIL->value,
        'password' => RootUser::PASS->value,
    ]);

    // Check if the request returns an error for the "email" input.
    $response->assertJsonValidationErrorFor('email');
});
