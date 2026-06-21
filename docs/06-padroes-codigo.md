# Padroes de Codigo

## PHP

Todos os arquivos PHP proprios devem:

- Usar UTF-8 sem BOM.
- Iniciar com o cabecalho padrao do projeto.
- Declarar `declare(strict_types=1);`.
- Evitar logica de negocio em controllers.

## Nomenclatura

- Classes: `PascalCase`.
- Metodos e variaveis: `camelCase`.
- Constantes: `UPPER_SNAKE`.
- Views Blade e assets: `kebab-case`.
- Tabelas: `PREFIX_NomePlural`.
- Prefixo inicial do projeto: `ABE_`.
- Chave primaria: sempre `id`.
- Chaves estrangeiras: `TabelaSingular_Id`.
- Timestamps: `created_at` e `updated_at`.

Exemplos:

- Tabela: `ABE_Empresas`.
- Tabela: `ABE_Usuarios`.
- FK: `Empresa_Id`.
- FK: `Usuario_Id`.

## Banco e queries

- Charset: `utf8mb4`.
- Collation: `utf8mb4_unicode_ci`.
- Nunca usar SQL concatenado.
- Preferir Eloquent ou Query Builder.
- SQL cru apenas com bindings.

## Entrada de usuario

Toda entrada de usuario deve passar por Form Request.

## Organizacao de regras

- Controllers devem ser magros.
- Regras de negocio ficam em Services ou Actions.
- Operacoes demoradas ficam em Jobs.

## Cabecalho padrao

```php
<?php
/**
 * PROJECT: AB Emissor
 * TYPE: [Controller | Model | Service | Action | Request | Job | Middleware | Core]
 * FILE: [caminho relativo do arquivo]
 *
 * @package ABEmissor\[Controllers|Models|Services|Actions|Requests|Jobs|Middleware|Core]
 * @author  Sergio Figueroa <sergio@saltadigital.com.br>
 * @since   1.0.0
 * @version 1.0.0
 * @license Software comercial proprietario. Este produto nao e software livre nem open source.
 *          Seu uso, copia, distribuicao, modificacao ou comercializacao dependem de autorizacao expressa da Salta Digital.
 *          O sistema pode utilizar bibliotecas e tecnologias open source de terceiros, respeitando suas respectivas licencas.
 * @copyright (c) 2026 Salta Digital
 *
 * @see /docs/[documento-relevante].md
 * @deprecated false
 */

declare(strict_types=1);
```
