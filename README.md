# Livraria — Sistema de Gestão de Livraria

Sistema de gerenciamento de livros desenvolvido com Laravel, Vue.js 3 e Inertia.js, com controle de acesso baseado em roles e ambiente containerizado com **Docker Compose** (stack baseada em [Laravel Sail](https://laravel.com/docs/sail); o arquivo `compose.yaml` fica na raiz do projeto).

---

## Tecnologias utilizadas

- **Backend:** Laravel 11 (PHP no container `laravel.test` do `compose.yaml`, ex.: 8.5)
- **Frontend:** Vue.js 3 + Inertia.js
- **Estilização:** Tailwind CSS
- **Banco de dados:** MySQL
- **ACL:** Spatie Laravel Permission
- **Infraestrutura:** Docker Compose (`compose.yaml` + MySQL)

---

## Funcionalidades

### Autenticação e autorização

- Login de usuários
- Controle de acesso baseado em roles:
  - **Admin**
    - Acesso total ao sistema
    - Gerenciamento de usuários
    - Visualização de logs de atividade
  - **Editor**
    - Pode criar e editar livros
    - Não pode excluir livros nem gerenciar usuários

### Gestão de livros

- Listagem com paginação e filtros de busca
- Criação de livros
- Edição de livros
- Exclusão de livros (apenas Admin)
- Relacionamento com categorias

**Campos:** título, autor, ISBN, preço, categoria, data de publicação.

### Categorias

- Relacionamento **1:N** com livros

### Logs de atividade

- Registro de ações importantes (criação, edição e exclusão de livros; alterações de usuários)
- Visualização disponível apenas para Admin

---

## Executando o projeto (Docker Compose)

### Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) instalado (Docker Desktop no Windows/macOS, ou Docker Engine + Compose no Linux)

---

### 1. Clonar o repositório

```bash
git clone https://github.com/Juan-Souzaa/testeAgroFauna.git
cd testeAgroFauna
```

---

### 2. Instalar dependências (sem Composer local)

**Linux / macOS / Git Bash:**

```bash
docker run --rm \
  -v "$(pwd)":/app \
  -w /app \
  laravelsail/php82-composer:latest \
  composer install
```

**Windows (PowerShell), na pasta do projeto:**

```powershell
docker run --rm -v "${PWD}:/app" -w /app laravelsail/php82-composer:latest composer install
```

Alternativa: instalar [Composer](https://getcomposer.org/) localmente e executar `composer install` na raiz do projeto.

---

### 3. Copiar arquivo de ambiente

**Linux / macOS / Git Bash:**

```bash
cp .env.example .env
```

**Windows (CMD):** `copy .env.example .env`  
**Windows (PowerShell):** `Copy-Item .env.example .env`



Ajuste `APP_NAME` se quiser.

---

### 4. Subir os containers

Na **raiz do projeto** (onde está o `compose.yaml`):

```bash
docker compose up -d
```

Serviços: `laravel.test` (PHP + Node) e `mysql`.

---

### 5. Gerar chave da aplicação

```bash
docker compose exec laravel.test php artisan key:generate
```

---

### 6. Rodar migrations e seeders

```bash
docker compose exec laravel.test php artisan migrate --seed
```

---

### 7. Instalar e compilar o frontend

```bash
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build
```

Para desenvolvimento com recarregamento (HMR), em outro terminal:

```bash
docker compose exec laravel.test npm run dev
```

No PowerShell, para rodar o Vite em segundo plano:

```powershell
docker compose exec -d laravel.test npm run dev
```

---

### 8. Acessar o sistema

- **Aplicação:** [http://localhost](http://localhost) (porta definida por `APP_PORT` no `.env`, por padrão `80`)
- **Vite (dev):** [http://localhost:5173](http://localhost:5173) quando `npm run dev` estiver em execução (`VITE_PORT`)

O MySQL pode ficar exposto no host na porta `FORWARD_DB_PORT` do `.env` (por exemplo `3307` no `.env.example`) para ferramentas externas, se necessário.

---

## Usuários de teste

Após `migrate --seed`, o [UserSeeder](database/seeders/UserSeeder.php) cria os usuários abaixo (senha em ambos: **`password`**):

| Perfil | E-mail | Senha |
|--------|--------|--------|
| Admin | `admin@livraria.test` | `password` |
| Editor | `editor@livraria.test` | `password` |

> O cadastro público está desativado; novos editores são criados pelo admin em **Usuários**.

---

## Segurança

- Autorização com **Policies** 
- Validação de permissões no backend e no frontend
- Rotas protegidas por autenticação

---

## Arquitetura

- Controllers para orquestrar requisições
- Form Requests para validação
- Policies para autorização de recursos
- Classes de serviço/ação para lógica de negócio quando aplicável
- Inertia.js para integração entre backend e frontend

---

## Testes automatizados

```bash
docker compose exec laravel.test php artisan test
```

Com PHP local na máquina (fora do container): `php artisan test`.

---

## Atalho Laravel Sail (opcional)

Em **Linux / macOS** ou **Git Bash (WSL2)** você pode usar `./vendor/bin/sail` no lugar de `docker compose` / `docker compose exec laravel.test` (por exemplo `./vendor/bin/sail up -d`, `./vendor/bin/sail artisan migrate`). No Windows **sem** Bash, use **`docker compose`** como nos passos principais.

---

## Versionamento

O projeto segue o padrão **Git Flow**:

- `main` — versão estável
- `develop` — integração
- `feature/*` — novas funcionalidades

Commits seguem o padrão **Conventional Commits**:

- `feat:` nova funcionalidade
- `fix:` correção
- `chore:` configuração
- `docs:` documentação

---

## Observações

- As dependências PHP não são versionadas (`vendor/`), seguindo boas práticas.
- O ambiente é containerizado com Docker Compose; não é obrigatório instalar PHP, Node ou MySQL localmente para desenvolver.
- No **Windows**, este fluxo usa **`docker compose`** na raiz; **Docker Desktop** precisa estar em execução.

