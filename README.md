# NewsAPI Blog (Laravel + Blade)

Aplicação simples em Laravel + Blade que consome a NewsAPI para buscar notícias, exibe em formato de blog com paginação, permite buscar por título/palavra-chave e registra as pesquisas no banco.

> **Nota:** O enunciado original citava SQL Server, mas este projeto está configurado com MySQL para facilitar a execução local. Se precisar de SQL Server, veja a seção "Usando SQL Server (opcional)".

## Stack

- **Laravel** (10/11 — última estável)
- **Blade** (frontend simples e responsivo)
- **MySQL** (padrão deste repo)
- **HTTP Client do Laravel** (para NewsAPI)

## Funcionalidades

- ✅ Busca de notícias por título/palavra-chave (q)
- ✅ Exibição em grade tipo blog (título, fonte, data, descrição)
- ✅ Paginação com links de navegação
- ✅ Registro das pesquisas realizadas no banco
- ✅ Design responsivo simples (CSS inline no layout)
- ✅ Tradução do paginator para PT-BR (Anterior/Próximo) e possibilidade de personalização do template

## Pré-requisitos

- PHP 8.2+ (com ext-json, ext-mbstring, ext-curl)
- Composer 2+
- MySQL 5.7+/8.0 (ou MariaDB equivalente)
- NEWSAPI_KEY (crie sua chave em [newsapi.org](https://newsapi.org))
- Opcional: Node 18+ (apenas se for compilar assets no futuro; este projeto usa CSS inline/Blade e não exige build de front)

## Como rodar (MySQL / XAMPP ou local)

### 1) Clonar e instalar dependências

```bash
git clone <seu-fork-ou-repo> newsapi-blog
cd newsapi-blog
composer install
```

### 2) Configurar o .env

Crie o arquivo .env a partir do exemplo e configure a base de dados e a chave da NewsAPI:

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env`:

```env
APP_NAME="NewsAPI Blog"
APP_ENV=local
APP_KEY=base64:gerado-pelo-comando
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Banco: MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=newsapi_blog
DB_USERNAME=root
DB_PASSWORD=

# NewsAPI
NEWSAPI_KEY=coloque_sua_chave_aqui
NEWSAPI_BASE=https://newsapi.org/v2
```

⚠️ **Importante:** não comite sua `NEWSAPI_KEY`.

### 3) Criar o banco

Crie o banco `newsapi_blog` no MySQL (via phpMyAdmin/Workbench/CLI) com utf8mb4:

```sql
CREATE DATABASE newsapi_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4) Rodar migrações

```bash
php artisan migrate
```

### 5) Subir o servidor

```bash
php artisan serve
```

Acesse: http://127.0.0.1:8000

## Rotas principais

- `GET /` → tela de busca e listagem de resultados (query param q)
- `GET /buscas` → histórico de pesquisas registradas no banco

## Estrutura do projeto (principais arquivos)

```
app/
├─ Http/Controllers/NewsController.php       # lógica de busca, paginação e gravação de pesquisas
├─ Models/Search.php                          # modelo das buscas
├─ Services/NewsApiService.php                # integração com a NewsAPI
config/
└─ services.php                               # credenciais/base URL da NewsAPI
resources/views/
├─ layouts/app.blade.php                      # layout base + estilos
├─ home.blade.php                             # tela principal (busca + grade de artigos)
└─ searches/index.blade.php                   # listagem de pesquisas salvas
```

## NewsAPI — Service

`app/Services/NewsApiService.php` encapsula a chamada HTTP:

- Endpoint: `/v2/everything`
- Parâmetros: q, sortBy=publishedAt, page, pageSize, apiKey, etc.
- Retorna status, totalResults, articles[]

Config em `config/services.php`:

```php
'newsapi' => [
    'key' => env('NEWSAPI_KEY'),
    'base_url' => env('NEWSAPI_BASE', 'https://newsapi.org/v2'),
],
```

Uso (injeção no controller):

```php
public function index(Request $request, \App\Services\NewsApiService $news) {
    $data = $news->search($request->query('q', ''), $page, $per);
}
```

## Modelo e migração (registro de buscas)

Search salva termo, total de resultados e metadados básicos:

```php
Schema::create('searches', function (Blueprint $table) {
    $table->id();
    $table->string('term');
    $table->unsignedInteger('total_results')->nullable();
    $table->ipAddress('ip')->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamps();
});
```

Criação via Artisan:

```bash
php artisan make:model Search -m
```

## Paginação (links e tradução)

Links padrão: `{{ $paginator->links() }}`

PT-BR dos botões Anterior/Próximo:

`config/app.php` → `locale = pt_BR`

`resources/lang/pt_BR/pagination.php`:

```php
return [
    'previous' => '&laquo; Anterior',
    'next' => 'Próximo &raquo;',
];
```

### Traduzir o resumo "Showing X to Y of Z results":

Publique os templates:
```bash
php artisan vendor:publish --tag=laravel-pagination
```

Edite `resources/views/vendor/pagination/tailwind.blade.php` e troque o `<p>` por algo como:

```html
<p class="text-sm leading-5 text-gray-700">
  Exibindo <span class="font-medium">{{ $paginator->firstItem() }}</span>
  a <span class="font-medium">{{ $paginator->lastItem() }}</span>
  de <span class="font-medium">{{ $paginator->total() }}</span> resultados
</p>
```

Ajustar o tamanho das setas via CSS (exemplo no `<style>` do layout):

```css
.pager nav svg{ width:14px; height:14px; }
.pager nav a, .pager nav span{ padding:6px 10px; }
```

## Validação e cache (recomendado)

### Validação simples para q:

```php
$request->validate(['q' => 'required|string|min:2|max:120']);
```

### Cache para evitar rate-limit e acelerar:

```php
use Illuminate\Support\Facades\Cache;
$key = "news:{".$q."}:{$page}:{$per}";
$data = Cache::remember($key, now()->addMinutes(5), fn() => $news->search($q, $page, $per));
```

Configure o driver de cache em `config/cache.php` conforme seu ambiente (file/redis/memcached).

## Comandos úteis (Artisan)

- Criar controller resource: `php artisan make:controller NewsController --resource`
- Criar model + migration: `php artisan make:model Search -m`
- Rodar migrações: `php artisan migrate`
- Resetar (tudo): `php artisan migrate:fresh` (use `--seed` se tiver seeders)
- Limpar caches: `php artisan optimize:clear`
- Regenerar autoload: `composer dump-autoload -o`

## Testes (opcional)

- Unit para NewsApiService usando `Http::fake()`
- Feature para NewsController validando resposta da view, paginação e gravação em searches

Executar:

```bash
php artisan test
```

## Usando SQL Server (opcional)

Se o avaliador exigir estritamente SQL Server:

1. Troque o `.env` para `DB_CONNECTION=sqlsrv` e configure host/porta/usuário
2. Garanta que as extensões `pdo_sqlsrv`/`sqlsrv` estão habilitadas no PHP
3. Rode `php artisan migrate` normalmente

**Caso use Windows:** instale ODBC Driver 18 + Microsoft Drivers for PHP for SQL Server.  
**Em Linux:** use `pecl install sqlsrv pdo_sqlsrv`.

## Troubleshooting

### Class App\Services\NewsApiService does not exist

- Verifique o caminho: `app/Services/NewsApiService.php` e o namespace `namespace App\Services;`
- Rode `composer dump-autoload -o` e `php artisan optimize:clear`

### MySQL: Access denied

- Confirme `DB_USERNAME`/`DB_PASSWORD` no `.env` e se o usuário tem permissão no banco

### Unknown database

- Crie o banco e confira o nome no `.env`

### Paginação muito grande

- Ajuste CSS no layout ou personalize `vendor/pagination/tailwind.blade.php`

## Licença

Este projeto está sob licença MIT.
