<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class UserTest extends TestCase
{
    /* use RefreshDatabase; */

    public function test_set_database_config()
    {
        Artisan::call('migrate:reset');
        Artisan::call('migrate');
        Artisan::call('passport:install');
        Artisan::call('db:seed');

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_user_login()
    {
        $auth_response = $this->post('api/auth/login',[
            'email' => 'emperor@galacticempire.com',
            'password' => '12345678'
        ]);
        $auth_response->assertJsonStructure([
            'status',
            'message',
            'data',
            'token'
        ]);
        $auth_response->assertJsonFragment(['status' => true]);
        $auth_response->assertJsonFragment(['message' => 'User login successfully']);

    }

    public function test_get_user_list()
    {
        $user = User::factory()->create();
        $token = Passport::actingAs($user);
        $response = $this->get('/api/users', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            ['id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at']
        ]);
        $response->assertJsonFragment(['email' => 'emperor@galacticempire.com']);
        $response->assertJsonCount(4);
    }

    public function test_get_user_detail()
    {
        $user = User::factory()->create();
        $token = Passport::actingAs($user);
        $response = $this->get('/api/users/1', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            ['id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at']
        );
        $response->assertJsonFragment(['email' => 'emperor@galacticempire.com']);
    }

    public function test_get_user_non_existing_user_detail()
    {
        $user = User::factory()->create();
        $token = Passport::actingAs($user);
        $response = $this->get('/api/users/167', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ]);
        $response->assertStatus(404);
    }

    public function test_user_register()
    {
        
        $response = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

         $response->assertStatus(201);
         $response->assertJsonFragment(['status' => true]);
         $response->assertJsonFragment(['message' => 'User register successfully']);

    }

    public function test_user_update()
    {
        $user = User::factory()->create();
        $token = Passport::actingAs($user);
        $response = $this->put('/api/users/7', [
            'name' => 'Test User Updated',
            'email' => 'test2@example.com',
            'password' => 'password'
        ],
        [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => true]);
        $response->assertJsonFragment(['message' => 'User updated successfully']);
    }

    public function test_user_logout()
    {
        $user = User::factory()->create();
        $token = Passport::actingAs($user);
        $response = $this->post('/api/auth/logout',[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ]);
        $response->assertJsonFragment(['status' => true]);
        $response->assertJsonFragment(['message' => 'User logout successfully']);
    }
}
