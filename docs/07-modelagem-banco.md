# Modelagem de Banco

## Convencoes

- Tabelas proprias usam prefixo `ABE_`.
- Padrao de tabela: `PREFIX_NomePlural`.
- PK sempre `id`.
- FK sempre `TabelaSingular_Id`.
- Timestamps padrao Laravel: `created_at` e `updated_at`.
- Charset `utf8mb4`.
- Collation `utf8mb4_unicode_ci`.

## Tabelas base propostas

- `ABE_Usuarios`: usuarios autenticaveis.
- `ABE_Empresas`: empresas emissoras ou acompanhadas pela contabilidade.
- `ABE_EmpresaUsuarios`: vinculo multiempresa entre empresa e usuario.
- `ABE_ClientesDestinatarios`: clientes e destinatarios de NF-e.
- `ABE_Produtos`: produtos comercializados pelas empresas.
- `ABE_CertificadosDigitais`: metadados e caminho seguro dos certificados.
- `ABE_Nfes`: cabecalho operacional da NF-e.
- `ABE_EventosFiscais`: eventos vinculados a NF-e.
- `ABE_Auditorias`: trilha de auditoria.

## Observacoes fiscais

O XML autorizado sera a fonte da verdade fiscal. A tabela `ABE_Nfes` deve armazenar dados operacionais e referencias para XML, protocolo e status, sem substituir o conteudo fiscal autorizado.

## Privacidade

Dados pessoais e fiscais devem ser armazenados apenas quando necessarios para a finalidade do sistema, respeitando LGPD e principios de minimizacao.
