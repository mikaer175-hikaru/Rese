<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterSendsVerifyEmail(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])->assertStatus(302);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function testUnverifiedUserRedirectsToVerificationNoticeWhenLogin(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        // Fortifyの設定・実装次第で遷移先は変わるので、
        // あなたのプロジェクトの「認証誘導画面」のURLに合わせてここを確定させてOK
        $response->assertStatus(302);
        $response->assertRedirect('/email/verify');
    }

    public function testResendVerificationEmail(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertStatus(302);

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
