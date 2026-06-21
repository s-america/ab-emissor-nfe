# 06 — Padrões de Código e Nomenclatura

## Cabeçalho padrão

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
 * @license Software comercial proprietário. Este produto não é software livre nem open source.
 *          Seu uso, cópia, distribuição, modificação ou comercialização dependem de autorização expressa da Salta Digital.
 *          O sistema pode utilizar bibliotecas e tecnologias open source de terceiros, respeitando suas respectivas licenças.
 * @copyright (c) 2026 Salta Digital
 *
 * @see /docs/[documento-relevante].md
 * @deprecated false
 */

declare(strict_types=1);
```

## Nomenclatura

| Elemento | Regra | Exemplo |
|---|---|---|
| Variáveis | camelCase | `$nomeEmpresa`, `$ultimoNsu` |
| Métodos | camelCase | `sincronizarEmpresa()`, `gerarDanfe()` |
| Classes | PascalCase | `EmpresaController`, `NfeEmissaoService` |
| Constantes | UPPER_SNAKE | `STATUS_ATIVO` |
| Tabelas | `PREFIX_NomePlural` | `CAD_Empresas` |
| PKs | `id` | `id` |
| FKs | `TabelaSingular_Id` | `Empresas_Id` |
| Views | kebab-case | `editar-empresa.blade.php` |
| Assets | kebab-case | `dashboard.js` |

## Codificação

- UTF-8 sem BOM.
- `declare(strict_types=1)` em arquivos PHP próprios.
- HTML com `<meta charset="UTF-8">`.
- Banco `utf8mb4`.
- Collation `utf8mb4_unicode_ci`.

## Segurança de SQL

Nunca concatenar SQL.

Preferir Eloquent:

```php
Empresa::query()
    ->where('Tenants_Id', $tenantId)
    ->where('Cnpj', $cnpj)
    ->first();
```

SQL cru somente com bindings:

```php
DB::selectOne(
    'SELECT * FROM CAD_Empresas WHERE Tenants_Id = :tenantId AND Cnpj = :cnpj',
    [
        'tenantId' => $tenantId,
        'cnpj' => $cnpj,
    ]
);
```

## Validação

Toda entrada de usuário deve passar por Form Request.
