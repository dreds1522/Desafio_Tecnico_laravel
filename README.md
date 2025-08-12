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
- MySQL 5.7+/8.0.
- NEWSAPI_KEY (crie sua chave em [newsapi.org](https://newsapi.org))
- Opcional: Node 18+ (apenas se for compilar assets no futuro; este projeto usa CSS inline/Blade e não exige build de front)

## Como rodar (MySQL / XAMPP / laragon ou local)

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

## Comandos úteis (Artisan)

- Criar controller resource: `php artisan make:controller NewsController --resource`
- Criar model + migration: `php artisan make:model Search -m`
- Rodar migrações: `php artisan migrate`
- Resetar (tudo): `php artisan migrate:fresh` (use `--seed` se tiver seeders)
- Limpar caches: `php artisan optimize:clear`
- Regenerar autoload: `composer dump-autoload -o`

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

## Dificuldades encontradas
- Proteção SSL/TLS desabilitada impossibilitando de criar o projeto.
- Solução: No ambiente Xampp/Laragon que usam configurações próprias consegui criar normalmente.

- Nas instrunções foi solicitado a criação do banco de dados no SQL Server, mas tive problemas com a configuração do driver.
- Infelizmente não encontrei solução e para conseguir finalizar o projeto dentro do prazo precisei seguir com o MYSQL.

## Algumas fontes de pesquisa
- https://www.youtube.com/playlist?list=PLwXQLZ3FdTVH5Tb57_-ll_r0VhNz9RrXb
- https://www.youtube.com/watch?v=sRNbGEJmjfo&ab_channel=SatellaSoft
- https://www.youtube.com/watch?v=HZ7pBoeGlgI&t=165s&ab_channel=HertonVilarim
- https://www.youtube.com/watch?v=jmANjyM3PQk&ab_channel=Celke
- chatGPT

## Licença

Este projeto está sob licença MIT.
