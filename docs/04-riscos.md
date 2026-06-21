# Riscos

## Fiscal

- Mudancas em regras da SEFAZ, schemas, certificados e ambientes autorizadores.
- Divergencia entre dados exibidos e XML autorizado.
- Cancelamentos, inutilizacoes e cartas de correcao tratados sem rastreabilidade.

Mitigacao: tratar XML autorizado como fonte da verdade, versionar regras fiscais internas e manter eventos fiscais auditaveis.

## Seguranca

- Vazamento entre empresas.
- Exposicao de certificados digitais.
- Armazenamento de XMLs em caminho publico.
- Logs contendo dados sensiveis.

Mitigacao: policies, escopos por empresa, storage privado, criptografia quando aplicavel e auditoria.

## Operacional

- Ambiente local diferente do deploy futuro.
- Dependencia de fila para operacoes fiscais.
- Timeouts em chamadas externas.

Mitigacao: Jobs, retries, logs estruturados, documentacao de deploy e validacao de ambiente.

## Produto

- Escopo crescer antes da base multiempresa estar segura.
- Billing futuro influenciar prematuramente a modelagem.

Mitigacao: separar billing como modulo futuro e priorizar fundacao fiscal segura.
