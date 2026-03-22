<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Livro;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_convidado_e_redirecionado_ao_tentar_aceder_ao_formulario_de_criacao(): void
    {
        $response = $this->get(route('livros.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_convidado_e_redirecionado_ao_tentar_submeter_livro(): void
    {
        $response = $this->post(route('livros.store'), []);

        $response->assertRedirect(route('login'));
    }

    public function test_utilizador_sem_permissao_recebe_proibido_no_formulario(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('livros.create'));

        $response->assertForbidden();
    }

    public function test_utilizador_sem_permissao_recebe_proibido_ao_submeter(): void
    {
        $user = User::factory()->create();
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($user)->post(route('livros.store'), [
            'titulo' => 'Teste',
            'autor' => 'Autor',
            'isbn' => '9781234567890',
            'preco' => '29.90',
            'categoria_id' => (string) $categoria->id,
        ]);

        $response->assertForbidden();
    }

    public function test_utilizador_com_permissao_ve_o_formulario(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');

        $response = $this->actingAs($user)->get(route('livros.create'));

        $response->assertOk();
    }

    public function test_utilizador_com_permissao_pode_cadastrar_livro_e_e_redirecionado_a_listagem_com_mensagem(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();

        $payload = [
            'titulo' => 'Livro de teste',
            'autor' => 'Autor Teste',
            'isbn' => '9781234567890',
            'preco' => '42.50',
            'categoria_id' => (string) $categoria->id,
            'publicado_em' => '2024-06-15',
        ];

        $response = $this->actingAs($user)->post(route('livros.store'), $payload);

        $response->assertRedirect(route('livros.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('livros', [
            'titulo' => 'Livro de teste',
            'autor' => 'Autor Teste',
            'isbn' => '9781234567890',
            'categoria_id' => $categoria->id,
        ]);

        $livro = Livro::query()->where('isbn', '9781234567890')->first();
        $this->assertNotNull($livro);
        $this->assertSame('42.50', $livro->preco);
    }

    public function test_cadastro_falha_com_dados_invalidos(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');

        $response = $this->actingAs($user)->from(route('livros.create'))->post(route('livros.store'), [
            'titulo' => '',
            'autor' => '',
            'isbn' => '',
            'preco' => '-1',
            'categoria_id' => '999999',
        ]);

        $response->assertSessionHasErrors(['titulo', 'autor', 'isbn', 'preco', 'categoria_id']);
        $this->assertDatabaseCount('livros', 0);
    }

    public function test_isbn_duplicado_e_rejeitado(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $categoria = Categoria::factory()->create();

        Livro::factory()->create([
            'isbn' => '9780000000001',
            'categoria_id' => $categoria->id,
        ]);

        $response = $this->actingAs($user)->from(route('livros.create'))->post(route('livros.store'), [
            'titulo' => 'Outro título',
            'autor' => 'Outro autor',
            'isbn' => '9780000000001',
            'preco' => '10.00',
            'categoria_id' => (string) $categoria->id,
        ]);

        $response->assertSessionHasErrors('isbn');
        $this->assertDatabaseCount('livros', 1);
    }
}
