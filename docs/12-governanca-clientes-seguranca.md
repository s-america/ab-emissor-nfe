# Governanca de empresas e seguranca de acesso

## Terminologia de interface

O painel administrativo usa **empresa**. A palavra **cliente** fica reservada aos destinatarios de NF-e e a referencias comerciais genericas. `tenant` permanece somente no dominio tecnico e no banco.

O painel de empresas possui escopo administrativo separado:

- **Empresa emitente:** acessa somente as empresas e dados fiscais vinculados ao proprio contexto.
- **Administrador da contabilidade:** acessa as empresas administradas pela sua contabilidade e os dados autorizados dessas empresas.
- **Super administrador Salta Digital:** acessa todas as empresas, usuarios administrativos, papeis e permissoes.

Cada empresa administrada pode possuir uma `contabilidade_tenant_id`. Essa relacao impede que uma contabilidade enxergue ou altere empresas administradas por outra.

## Sessao e autenticacao

- Login sem sessao persistente por lembrar-me.
- Regeneracao do identificador apos login.
- Rotacao periodica do identificador da sessao.
- Limite absoluto e limite de inatividade.
- Rate limit por e-mail e IP.
- Logout invalida a sessao e o token CSRF.
- Usuarios sem empresa ativa nao entram no painel operacional.
- Super administradores entram exclusivamente no painel administrativo.

## Ciclo de vida de empresas

O super administrador pode criar, atualizar, visualizar e desabilitar empresas.

Uma empresa com documentos NF-e, eventos fiscais ou certificado digital nao pode ser excluida. Nesse caso, a operacao apenas marca `ativo = false`, preservando dados, arquivos e historico.

A exclusao fisica e permitida somente quando nao ha movimento fiscal. Ela ocorre em transacao e nao deve ser usada como rotina de retencao fiscal.

## Usuarios, papeis e permissoes

Os papeis possuem escopo explicito (`cliente`, `contabilidade` ou `salta_admin`). Usuarios administrativos sao desabilitados, nao removidos fisicamente, para preservar auditoria e rastreabilidade.

Permissoes futuras devem ser associadas a papeis por `sis_papel_permissoes`; nao se deve autorizar acoes sensiveis apenas por campos ocultos ou links do front-end.

O primeiro super administrador deve ser criado pelo comando interativo:

```powershell
php artisan access:create-super-admin admin@salta.digital
```

A senha nao deve ser passada na linha de comando nem versionada.

## Seguranca continua

O comando `php artisan security:check` verifica configuracoes essenciais, grava um relatorio privado em `storage/app/private/security` e pode publicar falhas em `SECURITY_ALERT_WEBHOOK_URL`. O agendador executa a rotina diariamente as 03:00.

Isso nao substitui analise de dependencias. O pipeline deve executar `composer audit`, atualizacoes de seguranca, analise estatica, varredura de segredos e DAST antes de homologacao e producao.
