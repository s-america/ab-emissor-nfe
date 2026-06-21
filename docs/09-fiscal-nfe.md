# Fiscal NF-e Modelo 55

## Escopo

O AB Emissor NF-e sera preparado para emissao auxiliar de NF-e modelo 55.

NFePHP nao sera implementado nesta etapa. A integracao sera planejada para fase posterior, apos a fundacao multiempresa, seguranca e modelagem base.

## Fonte da verdade

O XML autorizado e seu protocolo serao a fonte da verdade fiscal.

Dados em tabelas, telas e relatorios serao indices, resumos operacionais ou copias controladas, mas nao substituem o XML autorizado.

## Ciclo previsto

- Rascunho.
- Validacao interna.
- Assinatura.
- Transmissao.
- Consulta.
- Autorizacao.
- Armazenamento do XML autorizado.
- Eventos fiscais como cancelamento, carta de correcao e inutilizacao, quando aplicavel.

## Eventos fiscais

Eventos devem ser registrados com:

- Empresa.
- NF-e vinculada.
- Tipo de evento.
- Status.
- Protocolo quando houver.
- XML de envio e retorno quando houver.
- Usuario ou Job responsavel.

## Cuidados

- Validar dados antes da transmissao.
- Evitar operacoes fiscais sincronas longas em requisicoes HTTP.
- Usar Jobs para comunicacao com SEFAZ.
- Armazenar XMLs em local privado.
- Preservar historico de status e eventos.
