# Test for Konecta

Web application built on react as frontend and plain php as backend

## Installation

-   Clone repository.

```bash
    cd PHPTestKonecta
    composer install
```

## BD Schema

import sql schema to your MySql DB from

```bash
    src/DB/schema/schema.sql
```

## BD Seed

Seed DB with users, products, categories

```bash
    cd src/DB
    php Seed.php
```

## Run Server

```bash
    /
    php -S 127.0.0.1:8000 -t public
```
