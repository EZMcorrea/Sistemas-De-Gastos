# Planilha de Gastos (Laravel)

Projeto convertido para Laravel com opção de usar PostgreSQL (produção) ou SQLite (desenvolvimento).

## Requisitos

- PHP 8.1+
- Composer
- (Opcional) PostgreSQL
- (Opcional) Google Chrome + ChromeDriver para testes de UI (Dusk)

## Rodando localmente

1. Instale dependências:

```bash
composer install
```

2. Configure `.env` (exemplo para SQLite — recomendado para desenvolvimento rápido):

```env
DB_CONNECTION=sqlite
# Para usar arquivo sqlite local, crie o arquivo e aponte aqui:
# DB_DATABASE=/absolute/path/to/database.sqlite
```

3. Execute migrações e seeders:

```bash
php artisan migrate --seed
```

4. Inicie o servidor (use porta 8081 se 8000 estiver ocupada):

```bash
php artisan serve --port=8081
```

Acesse: http://127.0.0.1:8081

## Testes

- PHPUnit (Feature/Unit):

```bash
php artisan test
```

- Laravel Dusk (UI tests):

O Dusk foi adicionado ao projeto, mas o download automático do ChromeDriver falhou no ambiente atual por causa de verificação SSL no cURL.

Opções para usar Dusk:

1. Baixe manualmente o ChromeDriver compatível com sua versão do Chrome:
   - https://googlechromelabs.github.io/chrome-for-testing/
   - Coloque o binário em um local, por exemplo `C:\chromedriver\chromedriver.exe` (Windows) ou `/usr/local/bin/chromedriver` (Unix).

2. Configure a variável de ambiente para apontar ao binário (PowerShell exemplo):

```powershell
$env:DUSK_CHROME_DRIVER_PATH = 'C:\chromedriver\chromedriver.exe'
php artisan dusk
```

3. Ou corrija/carrege o CA para permitir download automático (se preferir que `php artisan dusk:install` baixe o driver).

Após isso, execute:

```bash
php artisan dusk
```

## CI

Adicionei um workflow básico em `.github/workflows/phpunit.yml` que executa os testes PHPUnit em pushes/PRs.

## Observações

- O app foi configurado para rodar na porta `8081` conforme solicitado.
- Se quiser que eu tente baixar o ChromeDriver novamente, posso tentar (mas o erro atual aponta para problema de CA/SSL no ambiente). Posso também ajudar com o download manual e configuração da variável `DUSK_CHROME_DRIVER_PATH`.

## Comandos úteis

```bash
# migrar + seed
php artisan migrate --seed

# rodar testes
php artisan test

# iniciar servidor em porta alternativa
php artisan serve --port=8081
```

## Docker (desenvolvimento)

Para facilitar execução local e testes, há um `Dockerfile` e `docker-compose.yml` de desenvolvimento.

1. Build e subir contêineres:

```bash
docker-compose up --build -d
```

2. O container da aplicação expõe a porta `8000` (acessível em http://127.0.0.1:8000). O serviço cria o banco Postgres (`db`) com usuário `laravel` / senha `secret` e executa migrations/seeders automaticamente durante o startup.

3. Parar e remover containers:

```bash
docker-compose down -v
```

Observação: o `docker-compose` foi pensado para desenvolvimento rápido; ajuste variáveis de ambiente em `docker-compose.yml` conforme necessário para produção.

