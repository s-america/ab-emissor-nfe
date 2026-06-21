# 09 — Fiscal NF-e

## Escopo inicial

NF-e modelo 55.

## Fora do escopo inicial

- NFC-e;
- NFS-e;
- CT-e;
- MDF-e.

## Fluxo

```text
Rascunho
  -> validação
  -> reserva de número
  -> geração XML
  -> assinatura
  -> transmissão
  -> consulta retorno
  -> autorização ou rejeição
  -> armazenamento XML autorizado
  -> DANFE
```

## Numeração

Nunca usar:

```text
max(id)+1
```

Usar tabela própria de série e reserva transacional de número.

## Status

```text
rascunho
validando
numero_reservado
xml_gerado
assinado
em_fila
transmitindo
autorizado
rejeitado
denegado
cancelado
inutilizado
erro
```

## Eventos futuros

- cancelamento;
- inutilização;
- carta de correção;
- consulta;
- manifestação, se aplicável ao escopo futuro.
