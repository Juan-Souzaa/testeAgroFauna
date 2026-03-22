<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_editor_nao_acessa_registro_de_atividades(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $response = $this->actingAs($editor)->get(route('admin.logs.index'));

        $response->assertForbidden();
    }

    public function test_admin_ve_registro_de_atividades(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.logs.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Admin/Logs/Index'));
    }

    public function test_criar_livro_regista_auditoria(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        $categoria = \App\Models\Categoria::factory()->create();

        $this->post(route('livros.store'), [
            'titulo' => 'Obra Teste',
            'autor' => 'Autor X',
            'isbn' => '9789001234567',
            'preco' => '42.50',
            'categoria_id' => (string) $categoria->id,
            'publicado_em' => '2024-01-15',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => AuditLog::LIVRO_CREATED,
        ]);
    }
}
