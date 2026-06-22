# 07 â€” Modelagem Inicial do Banco de Dados

## Banco

```sql
CREATE DATABASE ab_emissor_nfe
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

## Prefixos

| Prefixo | Finalidade |
|---|---|
| sis_ | Sistema |
| cad_ | Cadastros |
| fis_ | Fiscal |
| log_ | Logs |
| FIN_ | Financeiro |
| CFG_ | ConfiguraÃ§Ãµes |

## Tabelas

```text
sis_tenants
sis_usuarios
sis_papeis
sis_permissoes
sis_usuario_papeis
sis_tenant_usuarios

cad_empresas
cad_destinatarios
cad_produtos
cad_transportadoras

fis_certificados_digitais
fis_NfeSeries
fis_nfe_documentos
fis_NfeItens
fis_nfe_eventos
fis_NfeArquivosXml
fis_NfeNumeracoes

log_auditorias
log_eventos_sistema

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
- NÃ£o usar `max(id)+1` para numeraÃ§Ã£o fiscal.
- XML autorizado Ã© a fonte da verdade.

