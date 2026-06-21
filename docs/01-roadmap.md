# Roadmap

## Fase 0 - Fundacao

- Documentar visao, arquitetura, seguranca, modelagem e regras fiscais iniciais.
- Configurar Laravel para o projeto AB Emissor NF-e.
- Criar migrations base com padrao multiempresa.
- Validar conexao local com banco `ab_emissor_nfe`.
- Definir padroes de codigo e nomenclatura.

## Fase 1 - Base SaaS multiempresa

- Autenticacao.
- Empresas.
- Usuarios e vinculos com empresas.
- Policies e escopo por empresa.
- Auditoria de acoes sensiveis.
- Form Requests para entradas de usuario.

## Fase 2 - Cadastros fiscais

- Clientes/destinatarios.
- Produtos.
- Certificados digitais.
- Parametros fiscais por empresa.
- Validacoes de dados fiscais antes da emissao.

## Fase 3 - NF-e modelo 55

- Modelagem da NF-e.
- Rascunho, validacao, assinatura, transmissao, consulta e cancelamento.
- Integracao com NFePHP somente quando a base estiver preparada.
- Armazenamento seguro de XMLs, protocolos e eventos fiscais.

## Fase 4 - Operacao contabilidade

- Painel da contabilidade.
- Relatorios operacionais.
- Filtros por empresa, periodo, status e risco.
- Exportacoes controladas.

## Fase 5 - SaaS comercial futuro

- Billing.
- Planos e limites.
- Onboarding comercial.
- Controles de inadimplencia e suspensao.
