<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Livro;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroListingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_convidado_e_redirecionado_da_listagem(): void
    {
        $response = $this->get(route('livros.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_utilizador_sem_permissao_recebe_proibido(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('livros.index'));

        $response->assertForbidden();
    }

    public function test_utilizador_com_permissao_ve_a_listagem(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();
        Livro::factory()->create([
            'titulo' => 'Obra visível',
            'categoria_id' => $categoria->id,
        ]);

        $response = $this->actingAs($user)->get(route('livros.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Livros/Index')
            ->has('livros.data', 1)
            ->where('livros.data.0.titulo', 'Obra visível')
            ->where('porPaginaOpcoes', [6, 12, 24, 48])
            ->where('busca', ''));
    }

    public function test_per_page_na_query_limita_itens_por_pagina(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();
        Livro::factory()->count(10)->create(['categoria_id' => $categoria->id]);

        $response = $this->actingAs($user)->get(route('livros.index', ['per_page' => 6]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->where('livros.per_page', 6)
            ->has('livros.data', 6));
    }

    public function test_per_page_invalido_cai_no_padrao(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();
        Livro::factory()->create(['categoria_id' => $categoria->id]);

        $response = $this->actingAs($user)->get(route('livros.index', ['per_page' => 999]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->where('livros.per_page', 12));
    }

    public function test_busca_filtra_por_titulo_autor_ou_isbn(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();

        Livro::factory()->create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Alpha único',
            'autor' => 'Autor X',
            'isbn' => '9781234567890',
        ]);
        Livro::factory()->create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Outro título',
            'autor' => 'Beta Autor',
            'isbn' => '9789999999999',
        ]);
        Livro::factory()->create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Gamma',
            'autor' => 'Outro',
            'isbn' => '9781111111111',
        ]);

        $response = $this->actingAs($user)->get(route('livros.index', ['busca' => 'Beta']));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->has('livros.data', 1)
            ->where('livros.data.0.titulo', 'Outro título')
            ->where('busca', 'Beta'));

        $rIsbn = $this->actingAs($user)->get(route('livros.index', ['busca' => '9781111']));
        $rIsbn->assertInertia(fn ($page) => $page
            ->has('livros.data', 1)
            ->where('livros.data.0.isbn', '9781111111111'));
    }

    public function test_busca_vazia_retorna_listagem_completa(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();
        Livro::factory()->count(3)->create(['categoria_id' => $categoria->id]);

        $response = $this->actingAs($user)->get(route('livros.index', ['busca' => '   ']));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->has('livros.data', 3)
            ->where('busca', ''));
    }

    public function test_busca_com_per_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();
        Livro::factory()->count(8)->create([
            'categoria_id' => $categoria->id,
            'autor' => 'Autor Comum',
        ]);

        $response = $this->actingAs($user)->get(route('livros.index', [
            'busca' => 'Comum',
            'per_page' => 6,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->where('livros.per_page', 6)
            ->has('livros.data', 6)
            ->where('busca', 'Comum'));
    }
}
