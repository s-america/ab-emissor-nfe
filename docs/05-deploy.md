# 05 — Deploy

## Desenvolvimento local

Pasta recomendada:

```text
C:\dev\ab-emissor-nfe
```

Não usar:

```text
C:\xampp\htdocs\ab-emissor-nfe
```

Execução local:

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

## Banco local

```sql
CREATE DATABASE ab_emissor_nfe
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

## Homologação Locaweb

Preferir subdomínio:

```text
emissor.abcont.cnt.br
```

O document root deve apontar para:

```text
/public
```

## Produção

Para operação fiscal madura, considerar VPS/cloud com:

- PHP-FPM;
- Nginx ou Apache;
- worker de fila;
- storage privado;
- backup automático;
- monitoramento.
