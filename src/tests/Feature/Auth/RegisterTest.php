<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterValidationRequiredMessages(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'password']);

        $errors = session('errors')->getBag('default')->toArray();

        $this->assertContains('お名前を入力してください', $errors['name']);
        $this->assertContains('メールアドレスを入力してください', $errors['email']);
        $this->assertContains('パスワードを入力してください', $errors['password']);
    }

    public function testRegisterValidationPasswordMinMessage(): void
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);

        $this->assertContains(
            'パスワードは8文字以上で入力してください',
            session('errors')->get('password')
        );
    }

    public function testRegisterValidationPasswordConfirmationMessage(): void
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => 'abcdefgh',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password_confirmation']);

        $this->assertContains(
            'パスワードと一致しません',
            session('errors')->get('password_confirmation')
        );
    }
}
