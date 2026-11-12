<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Super Administrator']
        );

        $this->user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.test.' . uniqid() . '@sekolah.test',
            'password' => Hash::make('password-lama123'),
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_login_page_renders_lupa_password_button(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee(route('password.request'));
        $response->assertSee('Lupa Password?');
    }

    public function test_forgot_password_page_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Reset Password');
        $response->assertSee('Kirim Link Reset Password');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => $this->user->email,
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo(
            $this->user,
            ResetPasswordNotification::class
        );
    }

    public function test_cannot_request_reset_link_with_invalid_email(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'tidak.ada.' . uniqid() . '@sekolah.test',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $token = Password::createToken($this->user);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $this->user->email,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Atur Ulang Password');
        $response->assertSee('Simpan Password Baru');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $token = Password::createToken($this->user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'password-baru456',
            'password_confirmation' => 'password-baru456',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');

        $this->user->refresh();
        $this->assertTrue(Hash::check('password-baru456', $this->user->password));

        // Verifikasi audit log tercatat
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'password_reset',
            'auditable_id' => $this->user->id,
        ]);
    }

    public function test_admin_cli_command_can_reset_password(): void
    {
        $this->artisan('user:reset-password', ['email' => $this->user->email])
            ->expectsQuestion('Masukkan password baru (minimal 8 karakter)', 'admin-baru-secret')
            ->expectsQuestion('Konfirmasi password baru', 'admin-baru-secret')
            ->expectsOutputToContain('Berhasil!')
            ->assertExitCode(0);

        $this->user->refresh();
        $this->assertTrue(Hash::check('admin-baru-secret', $this->user->password));
    }

    public function test_reset_password_notification_implements_should_queue(): void
    {
        $this->assertTrue(
            is_subclass_of(ResetPasswordNotification::class, \Illuminate\Contracts\Queue\ShouldQueue::class),
            'ResetPasswordNotification must implement ShouldQueue for asynchronous, non-blocking email delivery.'
        );
    }

    public function test_reset_password_link_can_be_requested_via_json(): void
    {
        Notification::fake();

        $response = $this->postJson(route('password.email'), [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure(['success', 'message']);

        Notification::assertSentTo(
            $this->user,
            ResetPasswordNotification::class
        );
    }

    public function test_cannot_request_reset_link_with_invalid_email_via_json(): void
    {
        $response = $this->postJson(route('password.email'), [
            'email' => 'invalid.user.' . uniqid() . '@sekolah.test',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure(['success', 'message', 'errors']);
    }
}
