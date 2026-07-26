# 01 — Roadmap do Projeto

## Fase 0 — Fundação

- Criar projeto Laravel.
- Criar repositório GitHub.
- Configurar banco local.
- Criar documentação.
- Definir padrões de código.
- Criar modelagem inicial.
- Criar migrations base.
- Validar ambiente local.

## Fase 1 — Núcleo seguro

- Autenticação.
- Usuários.
- Tenants.
- Empresas.
- Papéis e permissões.
- Auditoria inicial.
- Layout principal.

## Fase 1.5 — Governança de clientes e acessos

- Separar os painéis de cliente, contabilidade e Salta Digital.
- Usar “cliente” na interface e manter `tenant` apenas no domínio interno.
- Criar vínculo entre cliente e contabilidade responsável.
- Criar painel administrativo para clientes, usuários, papéis e permissões.
- Desabilitar clientes com movimento fiscal; excluir somente clientes sem movimento.
- Manter histórico, arquivos e dados fiscais após desabilitação.
- Rotacionar e expirar sessões, com rate limit no login.
- Automatizar verificações de segurança, relatórios e alertas.

## Fase 2 — Cadastros fiscais

- Destinatários.
- Produtos.
- Transportadoras.
- Natureza da operação.
- CFOP.
- NCM.
- Regras fiscais básicas.

## Fase 3 — Certificados digitais

- Upload seguro.
- Criptografia.
- Validação.
- Leitura da validade.
- Alerta de vencimento.

## Fase 4 — NF-e em homologação

- Rascunho.
- Itens.
- Totais.
- Reserva de numeração.
- Geração XML.
- Assinatura.
- Transmissão homologação.
- Retorno.
- DANFE.

## Fase 5 — Produção assistida

- Liberação por empresa.
- Emissão produção.
- Painel da contabilidade.
- Exportação XML.
- Relatórios.

## Fase 6 — Operação fiscal

- Cancelamento.
- Inutilização.
- Carta de correção.
- Reprocessamento.
- Logs avançados.
- Monitoramento.

## Fase 7 — SaaS comercial futuro

- Planos.
- Assinaturas.
- Consumo mensal.
- Pagamentos.
- Webhooks.
- Bloqueio server-side.
> Terminologia de interface: o painel administrativo usa **empresas**; `tenant` é apenas interno e “cliente” é usado para destinatários de NF-e.
