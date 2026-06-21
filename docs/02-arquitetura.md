# 02 — Arquitetura

## Modelo

Aplicação monolítica Laravel, modularizada por domínio.

## Camadas

- Routes
- Controllers
- Form Requests
- Policies
- Actions
- Services
- Jobs
- Models
- Storage
- Database

## Princípios

- Controllers magros.
- Services para regra de negócio.
- Actions para casos de uso.
- Form Requests para validação.
- Jobs para tarefas longas.
- Policies para autorização.
- Eloquent/Query Builder para acesso a dados.
- SQL cru somente com bindings.

## Diagrama

```text
Navegador
   |
   v
Laravel Routes
   |
   v
Controllers
   |
   v
Form Requests / Policies
   |
   v
Actions
   |
   v
Services
   |
   +--> Models / Database
   +--> Jobs / Queue
   +--> Storage privado
   +--> SEFAZ
```
