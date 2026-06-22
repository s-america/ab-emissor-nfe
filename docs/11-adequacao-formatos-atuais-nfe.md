# 11 - Adequacao aos Formatos Atuais da NF-e

## Objetivo

Registrar a preparacao estrutural do AB Emissor NF-e para os formatos atuais da NF-e modelo 55, sem implementar nesta etapa geracao de XML, assinatura, transmissao SEFAZ ou DANFE.

## Credenciamento SEFAZ

Cada empresa emitente deve estar credenciada na SEFAZ da UF correspondente para emitir NF-e modelo 55. O sistema deve separar ambiente de homologacao e producao por empresa, serie e certificado.

Antes de liberar producao, a empresa deve ter:

- CNPJ ativo e apto para emissao.
- Inscricao estadual quando exigida pela UF e pela operacao.
- Credenciamento NF-e na SEFAZ autorizadora.
- Certificado digital valido.
- Parametros fiscais revisados pela contabilidade.

## Certificado digital A1/A3

O certificado A1 e o caminho inicial recomendado para operacao web, por permitir automacao server-side em jobs. O arquivo e sua senha nunca devem ficar em pasta publica.

O certificado A3 exige interacao com dispositivo fisico ou middleware local, portanto deve ser tratado como possibilidade futura, com arquitetura separada e sem assumir disponibilidade em servidor Locaweb.

Regras de seguranca:

- PFX/A1 em storage privado.
- Senha criptografada.
- Auditoria de uso do certificado.
- Alerta de validade.
- Substituicao segura sem apagar historico fiscal.

## Processamento da NF-e

Fluxo previsto:

1. Criacao de rascunho.
2. Validacao cadastral e fiscal.
3. Reserva transacional de numero e serie.
4. Geracao do XML.
5. Assinatura digital.
6. Transmissao para SEFAZ.
7. Consulta de retorno quando aplicavel.
8. Registro de protocolo, status e XML autorizado.
9. Disponibilizacao de XML e DANFE.

Esta etapa prepara apenas estrutura de dados para esse fluxo.

## Status fiscais

- `autorizada`: a SEFAZ autorizou o uso da NF-e. O XML autorizado com protocolo passa a ser a fonte da verdade fiscal.
- `rejeitada`: a SEFAZ recusou o XML por erro de validacao, cadastro, regra fiscal ou schema. Pode ser corrigida e transmitida novamente sem validade fiscal anterior.
- `denegada`: a SEFAZ denegou a NF-e por irregularidade fiscal do emitente ou destinatario. O numero fica inutilizado para nova emissao e o evento deve ser preservado.

## Guarda XML/DANFE

O XML autorizado e o protocolo devem ser preservados em storage privado e auditavel. O DANFE e representacao auxiliar impressa ou em PDF, nao substitui o XML autorizado.

Diretrizes:

- XML autorizado como fonte da verdade.
- DANFE pode ser regenerado a partir do XML autorizado.
- Downloads devem gerar auditoria.
- Retencao deve respeitar obrigacoes fiscais e LGPD.

## Contingencia

### FS-DA

Formulario de Seguranca para Documento Auxiliar. Deve ser tratado como contingencia excepcional, com controle de justificativa, data/hora de entrada em contingencia e transmissao posterior.

### SVC

SEFAZ Virtual de Contingencia. Deve usar `tp_emis` apropriado, ambiente autorizador correto e controle de transmissao posterior quando necessario.

### EPEC

Evento Previo de Emissao em Contingencia. Deve armazenar protocolo EPEC, XML de contingencia e status de transmissao posterior da NF-e.

Campos preparados em `fis_nfe_documentos`:

- `tipo_emissao`
- `tp_emis`
- `dh_cont`
- `x_just`
- `protocolo_epec`
- `xml_contingencia_path`
- `transmitida_pos_contingencia_at`

## Cancelamento

Cancelamento deve ser evento fiscal vinculado a NF-e autorizada, com justificativa, protocolo, XML de envio e XML de retorno. O sistema deve respeitar prazo e regras da UF.

## Carta de correcao

Carta de correcao eletronica deve ser registrada como evento fiscal. Nao pode corrigir valores fiscais determinantes, dados cadastrais que mudem a identidade das partes, data de emissao ou saida, nem outros campos vedados pela legislacao.

## CFOP entrada/saida

CFOP deve ser tratado como codigo fiscal essencial:

- CFOP iniciado por 1, 2 ou 3 representa entrada.
- CFOP iniciado por 5, 6 ou 7 representa saida.
- A escolha depende de UF, natureza de operacao, destinatario, produto e regra tributaria.

O sistema deve permitir cadastro auxiliar de CFOP e natureza de operacao, mas a validacao fiscal final deve ocorrer antes da transmissao.

## Reforma Tributaria - NT 2025.002

A modelagem fiscal deve estar preparada para os grupos e campos introduzidos pela Reforma Tributaria do consumo, especialmente IBS, CBS e IS.

Preparacao estrutural:

- `fis_nfe_item_impostos` usa grupo flexivel para ICMS, IPI, PIS, COFINS, IBS, CBS e IS.
- Campo `detalhes` em JSON permite preservar atributos novos enquanto a camada fiscal amadurece.
- Regras definitivas devem ser implementadas em Services/Actions fiscais quando os schemas e validadores forem incorporados.

Esta etapa nao implementa calculo de IBS, CBS ou IS. Ela apenas evita uma modelagem rigida que impediria aderencia a NT 2025.002.
