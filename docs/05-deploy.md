# Deploy

## Premissas

- Deploy futuro podera ocorrer em subdominio da AB Contabilidade na Locaweb.
- A aplicacao deve permanecer fora de diretorios publicos do servidor.
- Apenas `public/` deve ser exposto pelo web server.
- Certificados, XMLs e arquivos temporarios sensiveis devem ficar em storage privado.

## Banco

- Banco local: `ab_emissor_nfe`.
- Charset: `utf8mb4`.
- Collation: `utf8mb4_unicode_ci`.
- Nunca usar SQL concatenado.
- SQL cru somente com bindings.

## Variaveis de ambiente

Cada ambiente devera ter `.env` proprio, sem commit de credenciais reais.

Variaveis importantes:

- `APP_ENV`
- `APP_DEBUG`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `QUEUE_CONNECTION`
- `CACHE_STORE`
- `SESSION_DRIVER`

## Processo esperado

- Instalar dependencias PHP com Composer.
- Configurar `.env`.
- Rodar migrations.
- Rodar cache de configuracao em producao.
- Configurar cron do Laravel Scheduler quando necessario.
- Configurar worker de fila quando houver Jobs fiscais.
