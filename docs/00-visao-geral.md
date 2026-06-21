# AB Emissor NF-e - Visao Geral

## Proposito

O AB Emissor NF-e e uma plataforma Laravel para emissao auxiliar de NF-e modelo 55 para clientes da AB Contabilidade, com possibilidade futura de evolucao para um SaaS comercial operado pela Salta Digital.

## Diretriz principal

O projeto sera reconstruido do zero. O legado EmissorNFe nao sera reaproveitado como base tecnica, nao sera copiado e nao definira a arquitetura interna.

O legado podera ser consultado apenas como referencia funcional para entender fluxos, campos, regras de negocio ja utilizadas e expectativas dos usuarios.

## Escopo inicial

- Preparar documentacao, organizacao do projeto e migrations base.
- Definir uma base segura, multiempresa e preparada para regras fiscais brasileiras.
- Manter compatibilidade com PHP 8.2/8.3, Laravel e MariaDB/MySQL.
- Nao implementar NFePHP nesta etapa.
- Nao criar telas complexas nesta etapa.

## Ambiente local

- Windows 10.
- XAMPP com PHP 8.2.12.
- MariaDB 10.4.32.
- Projeto fora de `/xampp/htdocs`.
- Banco local: `ab_emissor_nfe`.
- Charset: `utf8mb4`.
- Collation: `utf8mb4_unicode_ci`.

## Repositorio

O GitHub sera usado com o usuario `s-america`.

## Fonte fiscal da verdade

O XML autorizado da NF-e sera tratado como fonte da verdade fiscal. Dados derivados em telas, relatorios ou dashboards nao substituem o XML autorizado e seus protocolos.
