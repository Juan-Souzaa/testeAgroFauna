<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Livro;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroEditDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_convidado_e_redirecionado_ao_editar(): void
    {
        $livro = Livro::factory()->create();

        $response = $this->get(route('livros.edit', $livro));

        $response->assertRedirect(route('login'));
    }

    public function test_convidado_e_redirecionado_ao_atualizar(): void
    {
        $livro = Livro::factory()->create();

        $response = $this->put(route('livros.update', $livro), []);

        $response->assertRedirect(route('login'));
    }

    public function test_convidado_e_redirecionado_ao_excluir(): void
    {
        $livro = Livro::factory()->create();

        $response = $this->delete(route('livros.destroy', $livro));

        $response->assertRedirect(route('login'));
    }

    public function test_sem_permissao_recebe_proibido_no_formulario_de_edicao(): void
    {
        $user = User::factory()->create();
        $livro = Livro::factory()->create();

        $response = $this->actingAs($user)->get(route('livros.edit', $livro));

        $response->assertForbidden();
    }

    public function test_editor_com_permissao_ve_formulario_de_edicao(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $livro = Livro::factory()->create();

        $response = $this->actingAs($user)->get(route('livros.edit', $livro));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Livros/Edit')
            ->where('livro.id', $livro->id)
            ->where('livro.titulo', $livro->titulo));
    }

    public function test_editor_pode_atualizar_livro(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();
        $livro = Livro::factory()->create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Antigo',
        ]);

        $response = $this->actingAs($user)->put(route('livros.update', $livro), [
            'titulo' => 'Novo título',
            'autor' => $livro->autor,
            'isbn' => $livro->isbn,
            'preco' => (string) $livro->preco,
            'categoria_id' => (string) $categoria->id,
            'publicado_em' => null,
        ]);

        $response->assertRedirect(route('livros.index'));
        $response->assertSessionHas('success');
        $this->assertSame('Novo título', $livro->fresh()->titulo);
    }

    public function test_nao_permite_isbn_duplicado_de_outro_livro(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();
        $outro = Livro::factory()->create(['categoria_id' => $categoria->id]);
        $livro = Livro::factory()->create(['categoria_id' => $categoria->id]);

        $response = $this->actingAs($user)->from(route('livros.edit', $livro))->put(
            route('livros.update', $livro),
            [
                'titulo' => $livro->titulo,
                'autor' => $livro->autor,
                'isbn' => $outro->isbn,
                'preco' => (string) $livro->preco,
                'categoria_id' => (string) $categoria->id,
                'publicado_em' => null,
            ],
        );

        $response->assertSessionHasErrors('isbn');
    }

    public function test_editor_nao_pode_excluir(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $livro = Livro::factory()->create();

        $response = $this->actingAs($user)->delete(route('livros.destroy', $livro));

        $response->assertForbidden();
        $this->assertDatabaseHas('livros', ['id' => $livro->id]);
    }

    public function test_admin_pode_excluir(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $livro = Livro::factory()->create();

        $response = $this->actingAs($user)->delete(route('livros.destroy', $livro));

        $response->assertRedirect(route('livros.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('livros', ['id' => $livro->id]);
    }
}
