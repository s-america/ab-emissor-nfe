# Seguranca

## Multiempresa

- Toda consulta de dado de negocio deve respeitar a empresa ativa.
- Policies devem validar acesso por usuario e empresa.
- Services e Actions nao devem confiar apenas em filtros vindos da tela.
- Testes devem cobrir tentativas de acesso entre empresas.

## Certificados digitais

- Certificados nunca devem ser armazenados em pasta publica.
- Senhas de certificados nao devem ser gravadas em texto puro.
- Acesso a certificados deve ser auditado.
- Rotinas de emissao devem usar storage privado.

## Entrada e persistencia

- Toda entrada de usuario deve passar por Form Request.
- Nunca usar SQL concatenado.
- Preferir Eloquent ou Query Builder.
- SQL cru apenas com bindings.

## LGPD

- Coletar somente dados necessarios.
- Restringir acesso a dados pessoais.
- Registrar acoes sensiveis em auditoria.
- Evitar dados pessoais desnecessarios em logs.

## Deploy seguro

- `APP_DEBUG=false` em producao.
- Credenciais apenas em variaveis de ambiente.
- HTTPS obrigatorio em producao.
- Permissao minima para arquivos e diretorios.
