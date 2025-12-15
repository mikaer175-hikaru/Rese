<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginValidationRequiredMessages(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'password']);

        $this->assertContains('メールアドレスを入力してください', session('errors')->get('email'));
        $this->assertContains('パスワードを入力してください', session('errors')->get('password'));
    }

    public function testLoginInvalidCredentialsMessage(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'no-user@example.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // Fortifyは通常 errors のキーが email になったり、
        // 実装によっては 'auth' 等になるので、プロジェクトに合わせて固定してOK
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
}
