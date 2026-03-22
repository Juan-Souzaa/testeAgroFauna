<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_editor_nao_acessa_a_listagem_de_usuarios(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $response = $this->actingAs($editor)->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_admin_ve_listagem_de_usuarios(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Admin/Users/Index'));
    }

    public function test_admin_atualiza_papel_de_outro_usuario(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $outro = User::factory()->create();
        $outro->assignRole('editor');

        $response = $this->actingAs($admin)->patch(route('admin.users.role', $outro), [
            'role' => 'admin',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertTrue($outro->fresh()->hasRole('admin'));
    }

    public function test_admin_nao_remove_ultimo_administrador_a_si_proprio(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->from(route('admin.users.index'))->patch(
            route('admin.users.role', $admin),
            ['role' => 'editor'],
        );

        $response->assertSessionHasErrors('role');
        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }

    public function test_admin_cria_editor(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->from(route('admin.users.index'))->post(route('admin.users.store'), [
            'name' => 'Novo Editor',
            'email' => 'novo.editor@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $criado = User::query()->where('email', 'novo.editor@example.com')->first();
        $this->assertNotNull($criado);
        $this->assertTrue($criado->hasRole('editor'));
    }

    public function test_editor_nao_pode_criar_usuario(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $response = $this->actingAs($editor)->post(route('admin.users.store'), [
            'name' => 'Tentativa',
            'email' => 'tentativa@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertForbidden();
        $this->assertNull(User::query()->where('email', 'tentativa@example.com')->first());
    }
}
