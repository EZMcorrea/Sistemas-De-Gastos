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

- Laravel Dusk (UI tests):

  O Dusk foi adicionado ao projeto, mas o download automático do ChromeDriver falhou no ambiente atual por causa de verificação SSL no cURL. A seguir há instruções para configurar localmente e um script auxiliar recomendado.

  Opções para usar Dusk:

  1) Manual (recomendado no Windows):
     - Baixe o Chrome for Testing compatível com sua versão do Chrome: https://googlechromelabs.github.io/chrome-for-testing/
     - Extraia o binário e coloque em um local, por exemplo `C:\chromedriver\chromedriver.exe`.
     - Atualize `.env` ou exporte a variável de ambiente `DUSK_CHROME_DRIVER_PATH` apontando para o binário:

     PowerShell (temporário):
     ```powershell
     $env:DUSK_CHROME_DRIVER_PATH = 'C:\chromedriver\chromedriver.exe'
     php artisan dusk
     ```

     Ou permanentemente em Windows (PowerShell):
     ```powershell
     [Environment]::SetEnvironmentVariable('DUSK_CHROME_DRIVER_PATH', 'C:\chromedriver\chromedriver.exe', 'User')
     ```

  2) Script auxiliar (linha de comando):
     - Se preferir, eu posso adicionar um script para baixar automaticamente o ChromeDriver compatível (mas o download pode falhar se o CA local estiver quebrado). Se quiser, eu posso tentar executar o download aqui ou adicionar o script para você executar localmente.

  3) Corrigir CA/SSL: se preferir o `php artisan dusk:install` baixar automaticamente, corrija o CA do sistema para permitir conexões TLS válidas — isso depende do seu SO.

  Após apontar `DUSK_CHROME_DRIVER_PATH` para o binário, execute:

  ```bash
  php artisan dusk
  ```

  Observação: adicionei em `.env.example` a variável `DUSK_CHROME_DRIVER_PATH` como placeholder para facilitar configuração.

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

