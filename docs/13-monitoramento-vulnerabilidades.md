# Monitoramento contínuo de vulnerabilidades

Na interface administrativa, os tenants são sempre apresentados como **empresas**.

## Objetivo

Detectar falhas de configuração, dependências vulneráveis, segredos expostos, erros de aplicação e indisponibilidade antes que afetem os clientes.

## Camadas

1. `security:check` diário no servidor, com relatório privado e webhook de alerta.
2. `composer audit` no CI e no processo de atualização de dependências.
3. Dependabot/Renovate para abrir atualizações de segurança.
4. SAST para PHP/Laravel e análise de segredos no repositório.
5. DAST e teste de autenticação/autorização no ambiente de homologação.
6. Monitoramento de disponibilidade, filas, erros 5xx, armazenamento e validade de certificados.

## Tratamento do incidente

Cada alerta deve gerar registro com data, ambiente, severidade, evidência, responsável, contenção, correção e validação posterior. Falhas críticas devem bloquear deploy e notificar a Salta Digital por webhook e e-mail operacional.

O relatório não deve conter senha, token, conteúdo de certificado, chave privada ou dados fiscais completos.
