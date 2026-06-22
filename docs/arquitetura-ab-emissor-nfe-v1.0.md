# AB EMISSOR NF-e
## Especificação Técnica, Arquitetura e Plano de Produto
### Versão 1.0.0 â€” Junho 2026 | Nome do Projeto: AB Emissor NF-e

> **Proprietário:** Salta Digital â€” Sergio Figueroa `<sergio@saltadigital.com.br>`  
> **Cliente inicial:** AB Contabilidade â€” Curitiba/PR â€” `https://www.abcont.cnt.br`  
> **Repositório previsto:** GitHub privado â€” `s-america/ab-emissor-nfe`  
> **Modelo:** Monolítico Laravel, multiempresa, com evolução futura para SaaS fiscal  
> **Licença:** Software comercial proprietário. Não é software livre nem open source.  
>
> **Cenário de desenvolvimento:**  
> - **IDE:** Visual Studio Code + Codex  
> - **Máquina local:** Notebook Lenovo â€” Intel Core i5-9300HF â€” Windows 10  
> - **Servidor local existente:** XAMPP â€” PHP 8.2.12 â€” MariaDB 10.4.32  
> - **Pasta local recomendada:** `C:\dev\ab-emissor-nfe\`  
> - **Importante:** o projeto não deve ser criado dentro de `C:\xampp\htdocs\` para não interferir nas aplicações atuais.  
> - **Produção/homologação futura:** Locaweb Linux, PHP 8.3 preferencialmente, MySQL 5.7.32, SSL Let's Encrypt  
> - **Domínio provável:** subdomínio da AB Contabilidade, por exemplo `nfe.abcont.cnt.br` ou `emissor.abcont.cnt.br`  
> - **Controle de versão:** Git + GitHub privado  

---

## ÃNDICE

1. [Visão Geral do Projeto](#1-visão-geral-do-projeto)
2. [Decisão Técnica: Reconstrução](#2-decisão-técnica-reconstrução)
3. [Objetivos do Produto](#3-objetivos-do-produto)
4. [Stack Tecnológico](#4-stack-tecnológico)
5. [Arquitetura do Sistema](#5-arquitetura-do-sistema)
6. [Estrutura de Diretórios](#6-estrutura-de-diretórios)
7. [Modelagem Inicial do Banco de Dados](#7-modelagem-inicial-do-banco-de-dados)
8. [Módulos do Sistema](#8-módulos-do-sistema)
9. [Fluxo Fiscal NF-e](#9-fluxo-fiscal-nf-e)
10. [Roadmap de Desenvolvimento](#10-roadmap-de-desenvolvimento)
11. [Padrões de Código e Nomenclatura](#11-padrões-de-código-e-nomenclatura)
12. [Diretrizes de Segurança](#12-diretrizes-de-segurança)
13. [Custos Estimados](#13-custos-estimados)
14. [Ambiente Local e Deploy](#14-ambiente-local-e-deploy)
15. [Prompts Iniciais para Codex](#15-prompts-iniciais-para-codex)

---

## 1. VISÃƒO GERAL DO PROJETO

O **AB Emissor NF-e** será uma aplicação web desenvolvida em Laravel para emissão auxiliar de Nota Fiscal EletrÃ´nica modelo 55.

O projeto nasce para atender inicialmente os clientes da **AB Contabilidade**, escritório localizado em Curitiba/PR e focado em empresas de pequeno porte. A necessidade surgiu porque a contabilidade começou a assumir empresas que precisam emitir nota fiscal eletrÃ´nica de mercadorias, e não apenas nota fiscal de serviço.

A aplicação permitirá que clientes da contabilidade emitam NF-e dentro de um limite controlado, enquanto a contabilidade terá acesso aos XMLs autorizados, relatórios fiscais, histórico de emissões e informações necessárias para escrituração e conferÃªncia.

Em uma fase futura, o sistema poderá evoluir para um SaaS comercial da **Salta Digital**, atendendo empresas avulsas do varejo e pequenas empresas que não sejam clientes contábeis.

---

## 2. DECISÃƒO TÃ‰CNICA: RECONSTRUÃ‡ÃƒO

O sistema legado **EmissorNFe** não será refatorado diretamente.

A decisão técnica oficial é:

```text
O AB Emissor NF-e será reconstruído do zero.
O legado será usado apenas como referÃªncia funcional, histórica e de aprendizado.
```

Motivos:

- o legado é procedural;
- não possui arquitetura em camadas;
- mistura PHP, HTML, SQL, sessão, regra fiscal e geração XML;
- possui autenticação insegura;
- usa SQL concatenado;
- armazena certificados de forma insegura;
- usa NFePHP antigo;
- não possui multiempresa confiável;
- não possui auditoria fiscal robusta;
- não possui controle transacional de numeração;
- não é base adequada para produto fiscal.

---

## 3. OBJETIVOS DO PRODUTO

### 3.1 Objetivo principal

Criar uma plataforma segura e organizada para emissão de NF-e modelo 55 por clientes da AB Contabilidade, permitindo que a contabilidade acompanhe, audite e baixe XMLs fiscais autorizados.

### 3.2 Objetivos secundários

- Centralizar emissão de NF-e dos clientes pequenos da contabilidade.
- Reduzir retrabalho contábil na coleta de XMLs.
- Controlar certificados digitais A1 por empresa.
- Controlar numeração fiscal por CNPJ, série e ambiente.
- Registrar eventos fiscais como autorização, rejeição, cancelamento, inutilização e carta de correção.
- Criar base técnica para futura comercialização SaaS.
- Criar aplicação Laravel versionada, documentada, segura e testável.

### 3.3 Resultado esperado ao final

Ao final do projeto, espera-se obter:

- aplicação Laravel funcional;
- emissão NF-e modelo 55 em homologação e produção;
- armazenamento seguro de XML autorizado;
- geração de DANFE;
- painel da contabilidade;
- relatórios por empresa/período;
- auditoria de ações;
- base preparada para planos e cobrança futura.

---

## 4. STACK TECNOLÓGICO

| Camada | Tecnologia | Versão/Observação |
|---|---|---|
| Linguagem | PHP | 8.2 local inicialmente; 8.3 recomendado em homologação/produção |
| Framework | Laravel | Versão compatível com ambiente local |
| Banco local | MariaDB | 10.4.32 via XAMPP |
| Banco hospedagem | MySQL | 5.7.32 Locaweb |
| ORM | Eloquent | padrão Laravel |
| Validação | Form Requests | padrão obrigatório |
| Filas | Laravel Queues | banco inicialmente; Redis futuramente |
| Fiscal NF-e | nfephp-org/sped-nfe | fase fiscal |
| PDF/DANFE | mPDF ou pacote DANFE compatível | via Composer |
| Front-end inicial | Blade + Livewire | evitar React no início |
| CSS | Tailwind ou Bootstrap | decidir na fase 1 |
| Versionamento | Git + GitHub | repositório privado |
| IDE | Visual Studio Code + Codex | ferramenta principal |
| Deploy inicial | Locaweb | preferir subdomínio apontando para `/public` |
| Deploy futuro | VPS/cloud | recomendado para operação fiscal madura |

---

## 5. ARQUITETURA DO SISTEMA

### 5.1 Visão macro

```text
┌──────────────────────────────────────────────┐
│ Navegador                                    │
│ Cliente / Contabilidade / Admin Técnico      │
└───────────────────┬──────────────────────────┘
                    │ HTTPS
                    ▼
┌──────────────────────────────────────────────┐
│ Aplicação Laravel                            │
│ Controllers + Requests + Policies            │
│ Services + Actions + Jobs                    │
└───────────────────┬──────────────────────────┘
                    │
        ┌───────────┴────────────┐
        ▼                        ▼
┌───────────────────┐    ┌─────────────────────┐
│ Banco Relacional   │    │ Storage Privado      │
│ MariaDB/MySQL      │    │ XML, DANFE, PFX enc. │
└─────────┬─────────┘    └─────────┬───────────┘
          │                        │
          ▼                        ▼
┌──────────────────────────────────────────────┐
│ Fila / Jobs Laravel                           │
│ Gerar XML, Assinar, Transmitir, Consultar     │
└───────────────────┬──────────────────────────┘
                    │
                    ▼
┌──────────────────────────────────────────────┐
│ SEFAZ / Ambiente Nacional NF-e                │
└──────────────────────────────────────────────┘
```

### 5.2 Arquitetura em camadas

```text
routes/
  └── web.php / api.php
        │
        ▼
app/Http/Controllers/
        │
        ▼
app/Http/Requests/
        │
        ▼
app/Actions/
        │
        ▼
app/Services/
        │
        ├── Fiscal/
        ├── Nfe/
        ├── Certificados/
        └── Auditoria/
        │
        ▼
app/Models/
        │
        ▼
database/
storage/
```

### 5.3 Regra arquitetural

Controllers serão magros.

Controllers podem:

- receber request;
- aplicar autorização;
- chamar uma Action ou Service;
- retornar view, redirect ou JSON.

Controllers não podem:

- montar XML;
- assinar certificado;
- transmitir SEFAZ;
- calcular regra fiscal complexa;
- consultar banco com SQL concatenado;
- manipular diretamente arquivos fiscais sensíveis.

---

## 6. ESTRUTURA DE DIRETÓRIOS

Estrutura inicial esperada:

```text
ab-emissor-nfe/
│
├── app/
│   ├── Actions/
│   │   ├── Empresas/
│   │   ├── Nfe/
│   │   └── Certificados/
│   │
│   ├── Enums/
│   │   ├── AmbienteFiscal.php
│   │   ├── StatusNfe.php
│   │   └── RegimeTributario.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Jobs/
│   │   └── Nfe/
│   │
│   ├── Models/
│   │   ├── Tenant.php
│   │   ├── Usuario.php
│   │   ├── Empresa.php
│   │   ├── Destinatario.php
│   │   ├── Produto.php
│   │   ├── CertificadoDigital.php
│   │   ├── NfeDocumento.php
│   │   └── Auditoria.php
│   │
│   ├── Policies/
│   │
│   ├── Services/
│   │   ├── Auditoria/
│   │   ├── Certificados/
│   │   ├── Fiscal/
│   │   └── Nfe/
│   │
│   ├── Support/
│   │   ├── Fiscal/
│   │   ├── Formatadores/
│   │   └── Sanitizacao/
│   │
│   └── ValueObjects/
│       ├── Cnpj.php
│       ├── ChaveAcessoNfe.php
│       └── Dinheiro.php
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── docs/
│   ├── arquitetura-ab-emissor-nfe-v1.0.md
│   ├── 00-visao-geral.md
│   ├── 01-roadmap.md
│   ├── 02-arquitetura.md
│   ├── 03-requisitos.md
│   ├── 04-riscos.md
│   ├── 05-deploy.md
│   ├── 06-padroes-codigo.md
│   ├── 07-modelagem-banco.md
│   ├── 08-seguranca.md
│   └── 09-fiscal-nfe.md
│
├── public/
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── dashboard/
│   │   ├── empresas/
│   │   ├── destinatarios/
│   │   ├── produtos/
│   │   ├── certificados/
│   │   ├── nfe/
│   │   ├── relatorios/
│   │   └── auditoria/
│   │
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── storage/
│   └── app/
│       └── private/
│           ├── certificados/
│           └── fiscal/
│
├── tests/
│
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── artisan
└── README.md
```

---

## 7. MODELAGEM INICIAL DO BANCO DE DADOS

### 7.1 Prefixos

| Prefixo | Finalidade |
|---|---|
| `sis_` | Estrutura geral do sistema |
| `cad_` | Cadastros principais |
| `fis_` | Núcleo fiscal |
| `log_` | Logs e auditoria |
| `FIN_` | Financeiro e cobrança futura |
| `CFG_` | Configurações |

### 7.2 Tabelas iniciais

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

### 7.3 Regras

- Todas as tabelas devem usar InnoDB.
- Charset: `utf8mb4`.
- Collation: `utf8mb4_unicode_ci`.
- Primary key: sempre `id`.
- Foreign keys: `TabelaSingular_Id`.
- Timestamps Laravel: `created_at`, `updated_at`.
- Não usar `max(id)+1` para numeração fiscal.
- Não armazenar PDF como fonte primária fiscal.
- XML autorizado é a fonte da verdade.

---

## 8. MÓDULOS DO SISTEMA

### 8.1 Autenticação

- login;
- logout;
- recuperação de senha;
- bloqueio por tentativas;
- sessão segura.

### 8.2 Empresas

- cadastro de empresas emitentes;
- CNPJ;
- inscrição estadual;
- regime tributário;
- ambiente fiscal;
- status;
- limite mensal.

### 8.3 Usuários e permissões

- administrador técnico;
- administrador da contabilidade;
- operador da contabilidade;
- cliente emitente;
- suporte.

### 8.4 Destinatários

- clientes da empresa emitente;
- CPF/CNPJ;
- endereço;
- inscrição estadual;
- indicador de IE.

### 8.5 Produtos

- descrição;
- NCM;
- CFOP padrão;
- unidade;
- valor;
- origem;
- CST/CSOSN;
- PIS/COFINS;
- dados fiscais.

### 8.6 Certificados Digitais

- upload A1;
- validação;
- criptografia;
- leitura de validade;
- alerta de vencimento;
- substituição segura.

### 8.7 NF-e

- rascunho;
- itens;
- validação;
- reserva de numeração;
- geração XML;
- assinatura;
- transmissão;
- retorno;
- rejeição;
- autorização;
- DANFE;
- cancelamento;
- inutilização futura;
- carta de correção futura.

### 8.8 Painel da Contabilidade

- empresas clientes;
- notas por período;
- XMLs em lote;
- rejeições;
- certificados vencendo;
- consumo mensal;
- relatórios.

### 8.9 Auditoria

- acesso;
- criação/alteração/exclusão;
- emissão;
- transmissão;
- download de XML;
- uso de certificado;
- eventos fiscais.

### 8.10 Billing futuro

- planos;
- assinaturas;
- faturas;
- pagamentos;
- webhooks;
- bloqueio server-side;
- limite mensal.

---

## 9. FLUXO FISCAL NF-e

```text
Rascunho
   │
   ▼
Validação cadastral e fiscal
   │
   ▼
Reserva transacional de número
   │
   ▼
Geração do XML
   │
   ▼
Assinatura com certificado A1
   │
   ▼
Envio para fila de transmissão
   │
   ▼
Transmissão SEFAZ
   │
   ▼
Consulta de recibo / retorno
   │
   ├── Rejeitada
   │       └── registrar motivo, permitir correção
   │
   └── Autorizada
           ├── armazenar XML autorizado
           ├── registrar protocolo
           ├── gerar DANFE
           └── disponibilizar para cliente e contabilidade
```

### 9.1 Status previstos

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

---

## 10. ROADMAP DE DESENVOLVIMENTO

### Fase 0 â€” Fundação do projeto

- Criar projeto Laravel.
- Criar repositório GitHub.
- Configurar `.env`.
- Configurar banco local.
- Criar documentação inicial.
- Criar padrões de código.
- Criar migrations base.
- Definir estrutura de pastas.

### Fase 1 â€” Núcleo seguro

- Autenticação.
- Usuários.
- Empresas.
- Tenants.
- Papéis e permissões.
- Auditoria inicial.
- Layout principal.
- Dashboard básico.

### Fase 2 â€” Cadastros fiscais

- Destinatários.
- Produtos.
- Transportadoras.
- Naturezas de operação.
- CFOP.
- NCM.
- Regras fiscais básicas.

### Fase 3 â€” Certificados digitais

- Upload seguro.
- Criptografia.
- Validação do PFX.
- Leitura da validade.
- Alerta de vencimento.

### Fase 4 â€” Emissão NF-e homologação

- Criação de rascunho.
- Itens.
- Totais.
- Geração XML.
- Assinatura.
- Transmissão em homologação.
- Consulta retorno.
- DANFE.

### Fase 5 â€” Produção assistida

- Liberação por empresa.
- Emissão em produção.
- Exportação XML.
- Painel da contabilidade.
- Relatórios.

### Fase 6 â€” Operação fiscal

- Cancelamento.
- Inutilização.
- Carta de correção.
- Reprocessamento.
- Logs avançados.
- Monitoramento.

### Fase 7 â€” Billing futuro

- Planos.
- Assinaturas.
- Consumo.
- Pagamentos.
- Webhooks.
- Suspensão server-side.

---

## 11. PADRÃ•ES DE CÓDIGO E NOMENCLATURA

### 11.1 Cabeçalho padrão

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

### 11.2 Nomenclatura

| Elemento | Regra | Exemplo |
|---|---|---|
| Variáveis | camelCase | `$nomeEmpresa`, `$ultimoNsu` |
| Métodos | camelCase | `sincronizarEmpresa()`, `gerarDanfe()` |
| Classes | PascalCase | `EmpresaController`, `NfeEmissaoService` |
| Constantes | UPPER_SNAKE | `STATUS_ATIVO`, `AMBIENTE_HOMOLOGACAO` |
| Tabelas | `prefix_nome_plural` | `cad_empresas`, `log_eventos_sistema` |
| PK | `id` | `id` |
| FK | `TabelaSingular_Id` | `Empresas_Id`, `tenant_id` |
| Views Blade | kebab-case | `editar-empresa.blade.php` |
| Assets | kebab-case | `dashboard.js`, `app.css` |

---

## 12. DIRETRIZES DE SEGURANÃ‡A

- Nunca usar SQL concatenado.
- Usar Eloquent ou Query Builder.
- SQL cru somente com bindings.
- Toda entrada deve passar por Form Request.
- Senhas devem ser hasheadas.
- Certificados nunca ficam em `public`.
- PFX deve ser armazenado criptografado.
- Senha do certificado deve ser criptografada.
- `.env` nunca será versionado.
- XML autorizado deve ficar em storage privado.
- Download de XML deve ser auditado.
- Toda operação fiscal deve gerar log.
- Bloqueio financeiro futuro deve ser server-side.
- `APP_DEBUG=false` em produção.
- Sessão deve ser segura.
- CSRF obrigatório.

---

## 13. CUSTOS ESTIMADOS

| Item | Necessário agora? | Estimativa |
|---|---:|---:|
| Laravel | Sim | R$ 0 |
| GitHub privado | Sim | R$ 0 no plano básico |
| VS Code | Sim | R$ 0 |
| Composer | Sim | R$ 0 |
| MariaDB local | Sim | R$ 0 |
| NFePHP | Fase fiscal | R$ 0 |
| mPDF | Fase DANFE | R$ 0 |
| Locaweb atual | Homologação | custo já contratado |
| Subdomínio | Homologação | R$ 0 se já incluso |
| Certificado A1 cliente | Por empresa | R$ 150 a R$ 350/ano |
| VPS/cloud futura | Recomendado | R$ 40 a R$ 250/mÃªs |
| Storage externo | Futuro | R$ 5 a R$ 50/mÃªs |
| Serviço SMTP | Futuro | R$ 0 a R$ 100/mÃªs |
| Monitoramento | Futuro | R$ 0 a R$ 150/mÃªs |
| Billing/PagBank | Fase futura | taxas por transação |

### 13.1 Leitura financeira

Para começar localmente:

```text
Custo adicional imediato: R$ 0
```

Para homologar na Locaweb:

```text
Custo adicional provável: R$ 0 a R$ 50
```

Para produção fiscal profissional:

```text
Custo mensal provável: R$ 80 a R$ 400
```

Para SaaS comercial:

```text
Custo mensal provável: R$ 250 a R$ 1.000+
```

---

## 14. AMBIENTE LOCAL E DEPLOY

### 14.1 Ambiente local

O projeto deve ser criado fora do XAMPP:

```text
C:\dev\ab-emissor-nfe
```

Não usar:

```text
C:\xampp\htdocs\ab-emissor-nfe
```

Motivo:

- evitar interferÃªncia nas aplicações atuais;
- evitar mistura de projetos;
- evitar dependÃªncia do Apache do XAMPP;
- usar Laravel pelo servidor interno.

Comando de execução local:

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

URL local:

```text
http://127.0.0.1:8001
```

### 14.2 Banco local

```sql
CREATE DATABASE ab_emissor_nfe
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

### 14.3 Deploy futuro

PreferÃªncia:

```text
emissor.abcont.cnt.br
```

O document root deve apontar para:

```text
/ab-emissor-nfe/public
```

Nunca apontar o domínio para a raiz do projeto Laravel.

---

## 15. PROMPTS INICIAIS PARA CODEX

### PROMPT FASE 0-A â€” Documentação e estrutura inicial

```text
VocÃª é um engenheiro de software sÃªnior especialista em Laravel, PHP 8.2/8.3, arquitetura SaaS multiempresa, NF-e modelo 55, NFePHP, segurança web, LGPD e sistemas fiscais brasileiros.

Estamos iniciando um novo projeto chamado AB Emissor NF-e.

Contexto:
- Projeto Laravel novo.
- Não é refatoração do legado EmissorNFe.
- O legado será usado apenas como referÃªncia funcional.
- O sistema será uma plataforma para emissão auxiliar de NF-e modelo 55 para clientes da AB Contabilidade, com possibilidade futura de SaaS comercial pela Salta Digital.
- O ambiente local usa Windows 10, XAMPP com PHP 8.2.12 e MariaDB 10.4.32.
- O projeto não deve ficar em /xampp/htdocs.
- O banco local será ab_emissor_nfe.
- O deploy futuro poderá ser em subdomínio da AB Contabilidade na Locaweb.
- A aplicação deve ser segura, multiempresa e preparada para regras fiscais.
- O GitHub será usado com o usuário s-america.

Tarefa:
1. Criar a pasta docs caso não exista.
2. Criar ou atualizar os arquivos de documentação.
3. Registrar a decisão de reconstrução do zero.
4. Registrar padrões de código.
5. Registrar modelagem inicial.
6. Registrar roadmap.
7. Não implementar NFePHP ainda.
8. Não criar telas complexas ainda.
9. Preparar documentação, estrutura e migrations base.

Antes de alterar qualquer coisa estrutural, explique brevemente o plano de arquivos que será criado.
```

---

## APÃŠNDICE A â€” DECISÃ•ES OFICIAIS

| Decisão | Status |
|---|---|
| Reconstruir do zero | Aprovado |
| Usar Laravel | Aprovado |
| Não alterar XAMPP atual | Aprovado |
| Usar MariaDB local existente | Aprovado |
| GitHub `s-america` | Aprovado |
| Começar por documentação | Aprovado |
| Legado apenas como referÃªncia | Aprovado |
| Produto proprietário | Aprovado |
| NF-e modelo 55 primeiro | Aprovado |
| SaaS comercial somente fase futura | Aprovado |

