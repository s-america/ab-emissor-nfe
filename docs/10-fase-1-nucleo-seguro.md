# Fase 1 - Nucleo Seguro

## Objetivo

Implementar a primeira camada operacional segura do AB Emissor NF-e: autenticacao, usuarios, tenants, empresas, papeis, permissoes, auditoria inicial, layout principal e dashboard basico.

## Entregas desta fase

- Login e logout com Form Request.
- Bloqueio de login para usuario inativo.
- Rate limit simples contra tentativa repetida de login.
- Dashboard protegido por autenticacao.
- Models centrais para usuarios, tenants, empresas, papeis, permissoes e auditoria.
- Service de auditoria inicial.
- Policy inicial para acesso a empresas por tenant.
- Migrations alinhadas com os prefixos de dominio da arquitetura v1.0:
  - `sis_` para nucleo do sistema.
  - `cad_` para cadastros.
  - `fis_` para nucleo fiscal.
  - `log_` para auditoria.

## Limites

- Nao ha NFePHP nesta fase.
- Nao ha telas complexas de cadastro nesta fase.
- Nao ha billing nesta fase.
- As permissoes foram modeladas, mas a tela de administracao de papeis e permissoes fica para fase posterior.

## Usuario local inicial

O seeder cria um usuario tecnico local:

```text
E-mail: admin@abemissor.local
Senha: password
```

Este usuario deve ser substituido por credenciais reais antes de qualquer homologacao externa.
