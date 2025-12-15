<?php

namespace Tests\Feature\Admin;

use App\Mail\AdminNoticeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminNoticeMailTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSendNoticeMailToAllUsers(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $users = User::factory()->count(3)->create(['role' => 'user']);

        $this->actingAs($admin);

        $this->post('/admin/notices/send', [
            'subject' => 'お知らせ',
            'body' => '本文です',
        ])->assertStatus(302); // TODO: URL/レスポンスを合わせる

        foreach ($users as $user) {
            Mail::assertSent(AdminNoticeMail::class, function ($mail) use ($user) {
                return $mail->hasTo($user->email);
            });
        }
    }
}
