# Requisitos

## Funcionais iniciais

- Permitir autenticacao de usuarios.
- Permitir cadastro e manutencao de empresas.
- Permitir vinculo de usuarios a uma ou mais empresas.
- Permitir cadastro de clientes/destinatarios.
- Permitir cadastro de produtos.
- Permitir cadastro seguro de certificados digitais fora de pasta publica.
- Permitir criacao futura de NF-e modelo 55.
- Permitir registro de eventos fiscais.
- Permitir consultas e relatorios operacionais.
- Permitir painel da contabilidade para acompanhamento de clientes.
- Permitir auditoria de acoes sensiveis.

## Nao funcionais

- PHP 8.2/8.3.
- Laravel atual do projeto.
- MariaDB/MySQL com `utf8mb4` e `utf8mb4_unicode_ci`.
- Projeto fora de `/xampp/htdocs`.
- Seguranca por empresa desde a modelagem.
- Entradas validadas por Form Requests.
- Regras de negocio em Services ou Actions.
- Operacoes demoradas em Jobs.

## Fora do escopo imediato

- Implementacao de NFePHP.
- Telas complexas.
- Billing em producao.
- Reaproveitamento tecnico do legado.
