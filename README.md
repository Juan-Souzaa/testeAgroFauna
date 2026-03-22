# Livraria — Sistema de gestão de catálogo

Aplicação web para gestão de um catálogo de livros, com **autenticação**, **ACL** (Spatie Laravel Permission), **CRUD de livros**, **gestão de usuários** (admin), **registro de atividades** (auditoria) e **filtros** na listagem.

## Stack

| Camada | Tecnologia |
|--------|------------|
| Backend | Laravel 11, PHP 8.2+ |
| Frontend | Vue 3, Inertia.js, Tailwind CSS |
| Banco de dados | MySQL 8 |
| ACL | [spatie/laravel-permission](https://github.com/spatie/laravel-permission) |
| Ambiente (recomendado) | Docker via [Laravel Sail](https://laravel.com/docs/sail) (`compose.yaml` na raiz) |

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) (Docker Desktop no Windows/macOS, ou Docker Engine + Compose no Linux)
- Opcional para o primeiro `composer install` sem container: PHP 8.2+ e [Composer](https://getcomposer.org/) instalados localmente

## Instalação com Laravel Sail (Docker)

Na raiz do projeto:

### 1. Variáveis de ambiente

```bash
copy .env.example .env
```

No Windows PowerShell você pode usar `Copy-Item .env.example .env`.

O arquivo [`.env.example`](.env.example) já inclui valores compatíveis com o MySQL do Sail (`DB_HOST=mysql`, usuário `sail`, senha `password`). Ajuste `APP_NAME` se quiser (ex.: `Livraria`).

### 2. Dependências PHP

**Com Composer instalado na máquina:**

```bash
composer install
```

**Só com Docker:** instale as dependências PHP usando um container Composer, conforme a [documentação do Laravel Sail](https://laravel.com/docs/sail#installing-composer-dependencies-for-existing-projects) (imagem e versão de PHP alinhadas ao projeto).

### 3. Chave da aplicação e banco de dados

```bash
./vendor/bin/sail up -d
```

No Windows (CMD): `vendor\bin\sail up -d`

Depois:

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

### 4. Frontend (Vite)

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Para desenvolvimento com recarregamento (HMR), em outro terminal:

```bash
./vendor/bin/sail npm run dev
```

### 5. Acessar a aplicação

- **HTTP:** `http://localhost` (porta definida por `APP_PORT` no `.env`, por padrão `80`)
- **Vite (dev):** `http://localhost:5173` quando `npm run dev` estiver em execução (`VITE_PORT`)

O MySQL fica exposto no host na porta `FORWARD_DB_PORT` (por padrão `3307` no `.env.example`) para ferramentas externas (opcional).

## Credenciais de teste (após `migrate --seed`)

O [UserSeeder](database/seeders/UserSeeder.php) cria dois usuários (senha em ambos: **`password`**):

| Perfil | E-mail | Permissões resumidas |
|--------|--------|----------------------|
| **Administrador** | `admin@livraria.test` | CRUD de livros (incluindo excluir), gestão de usuários, registro de atividades |
| **Editor** | `editor@livraria.test` | Criar e editar livros; não exclui livros nem gerencia usuários |

> O cadastro público está desativado; novos editores são criados pelo admin em **Usuários**.

## Testes automatizados

```bash
./vendor/bin/sail artisan test
```

Ou, com PHP local: `php artisan test`.

## Estrutura de branches (Git Flow)

- `main` — versão estável
- `develop` — integração
- `feature/*` — funcionalidades (ex.: `feature/listagem-livros`)
