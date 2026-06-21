# Arquitetura Inicial

## Base

Aplicacao Laravel organizada em camadas, com controllers magros e regras de negocio fora da camada HTTP.

## Camadas

- `Models`: entidades Eloquent, relacionamentos, casts e escopos simples.
- `Controllers`: entrada HTTP, autorizacao e delegacao para Form Requests, Services ou Actions.
- `Services`: regras de negocio com maior duracao ou coordenacao de fluxos.
- `Actions`: operacoes objetivas e reutilizaveis, com uma responsabilidade clara.
- `Policies`: autorizacao por usuario, empresa e permissao.
- `Form Requests`: validacao e normalizacao de entrada do usuario.
- `Jobs`: operacoes demoradas ou externas, como emissao, consulta, download e processamento de XML.
- `Enums`: estados e codigos internos controlados.
- `Value Objects`: CNPJ, CPF, chave de acesso, valores monetarios e outros conceitos com invariantes.

## Multiempresa

Toda entidade de negocio devera ter vinculo claro com empresa quando aplicavel. A aplicacao deve impedir vazamento de dados entre empresas por escopos, policies e testes.

## Fiscal

Regras fiscais devem ficar isoladas em Services, Actions, Enums e Value Objects, evitando logica fiscal em controllers ou views.

## Integracoes

NFePHP nao sera implementado nesta etapa. A arquitetura deve preparar o terreno para incluir integracao fiscal em Jobs e Services dedicados posteriormente.
