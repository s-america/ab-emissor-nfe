# 08 — Segurança

## Regras obrigatórias

- Nunca armazenar senha em texto puro.
- Nunca armazenar certificado em `public`.
- Nunca versionar `.env`.
- Nunca usar SQL concatenado.
- Nunca usar bloqueio financeiro apenas por frontend.
- Nunca deixar `APP_DEBUG=true` em produção.
- Nunca expor stack trace ao usuário.

## Certificados digitais

- Armazenar PFX em storage privado.
- Criptografar arquivo.
- Criptografar senha separadamente.
- Auditar uso do certificado.
- Alertar vencimento.
- Validar extensão, MIME e conteúdo.

## XML fiscal

- XML autorizado é documento principal.
- Armazenar em pasta privada.
- Auditar download.
- Permitir exportação por período.
- DANFE é derivado e pode ser regenerado.

## LGPD

- Registrar finalidade de tratamento.
- Restringir acesso por perfil.
- Auditar operações.
- Permitir retenção conforme obrigação fiscal.
