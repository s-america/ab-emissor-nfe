# 07 — Modelagem Inicial do Banco de Dados

## Banco

```sql
CREATE DATABASE ab_emissor_nfe
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

## Prefixos

| Prefixo | Finalidade |
|---|---|
| SIS_ | Sistema |
| CAD_ | Cadastros |
| FIS_ | Fiscal |
| LOG_ | Logs |
| FIN_ | Financeiro |
| CFG_ | Configurações |

## Tabelas

```text
SIS_Tenants
SIS_Usuarios
SIS_Papeis
SIS_Permissoes
SIS_UsuarioPapeis
SIS_TenantUsuarios

CAD_Empresas
CAD_Destinatarios
CAD_Produtos
CAD_Transportadoras

FIS_CertificadosDigitais
FIS_NfeSeries
FIS_NfeDocumentos
FIS_NfeItens
FIS_NfeEventos
FIS_NfeArquivosXml
FIS_NfeNumeracoes

LOG_Auditorias
LOG_EventosSistema

FIN_Planos
FIN_Assinaturas
FIN_ConsumosMensais
FIN_Faturas
FIN_Pagamentos

CFG_Configuracoes
```

## Regras

- PK sempre `id`.
- FK no formato `TabelaSingular_Id`.
- Campos em PascalCase.
- Timestamps Laravel: `created_at`, `updated_at`.
- Engine InnoDB.
- Não usar `max(id)+1` para numeração fiscal.
- XML autorizado é a fonte da verdade.
