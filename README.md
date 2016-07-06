# as-capacita-phalcon-api


### Postman
1. [Extension](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop)
2. [Collection](https://www.getpostman.com/collections/cf1f830ba892014d8bb8)


### Phalcon API

Clone do Projeto
```bash
$ git clone https://github.com/agenciasys/as-capacita-phalcon-api.git
```

Estrutura do Projeto
```
as-capacita-phalcon-mvc/
    app/
    configs/
    controllers/
    exceptions/
    mappers/
    modules/
        users/
            controllers/
            models/
    responses/
    library/
        agenciasys/
            error/
            exception/
    private/
    public/
    routes/
        collections/
    vendor/
```

Variáveis de Ambiente
> obs.: Criar arquivo `.env` no diretório `app/configs` com o código abaixo

```
DB_USER = "user"
DB_PASS = "passwd"
DB_SCHEMA = "as-capacita-phalcon"
```

Banco de Dados
```sql
-- CREATE DATABASE "as-capacita-phalcon" ---------------
CREATE DATABASE IF NOT EXISTS `as-capacita-phalcon` CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `as-capacita-phalcon`;
-- ---------------------------------------------------------


-- CREATE TABLE "users" ------------------------------------
CREATE TABLE `users` (
    `iUserId` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
    `sName` VarChar( 70 ) NOT NULL,
    `sEmail` VarChar( 70 ) NOT NULL,
    PRIMARY KEY ( `iUserId` ) )
ENGINE = InnoDB;
-- ---------------------------------------------------------
```

Composer
```bash
$ cd /www/as-capacita-phalcon-api/
$ composer install
```