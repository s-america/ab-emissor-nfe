# Fase 2 - Cadastros Fiscais

## Status

Concluida em julho de 2026.

## Objetivo

Criar a primeira camada de cadastros fiscais para preparar a emissao futura de NF-e modelo 55 sem implementar NFePHP ainda.

## Entregas

- Modelagem incremental de destinatarios com endereco, indicador de IE e dados fiscais basicos.
- Modelagem incremental de produtos com NCM, CEST, CFOP padrao, origem, CST/CSOSN, PIS e COFINS.
- Tabelas novas para transportadoras, naturezas de operacao, CFOP e NCM.
- CRUD simples para destinatarios.
- CRUD simples para produtos.
- CRUD simples para transportadoras.
- CRUD simples para naturezas de operacao.
- Gestao manual dos catalogos compartilhados de CFOP e NCM por perfil administrativo.
- Validacao de entrada por Form Requests.
- Persistencia por Actions.
- Auditoria de criacao e atualizacao.
- Resolucao de empresa ativa por tenant do usuario autenticado.
- Validacao de CFOP e NCM ativos nos produtos.
- Compatibilidade entre o tipo da natureza de operacao e o primeiro digito do CFOP.

## Limites

- Nao ha emissao de NF-e nesta fase.
- Nao ha NFePHP nesta fase.
- CFOP e NCM foram modelados, mas ainda nao recebem carga oficial automatica.
- O cadastro manual de CFOP e NCM nao substitui a futura carga e atualizacao por fonte oficial.

## Regras

- XML autorizado continuara sendo a fonte da verdade fiscal na fase de emissao.
- Dados de produtos e destinatarios sao cadastros auxiliares.
- Toda gravacao passa por Form Request, Action e auditoria.
