# Deploying to a production environment

- [Deploying to a production environment](#deploying-to-a-production-environment)
  - [A quick note](#a-quick-note)
  - [Deploying the API server](#deploying-the-api-server)
    - [Requirements](#requirements)
    - [Database setup](#database-setup)
    - [API deployment](#api-deployment)
    - [Security concerns](#security-concerns)
  - [cron job](#cron-job)
  - [Web client](#web-client)
    - [Requirements](#requirements-1)
    - [Build](#build)

## A quick note

The project is made of three separated components that work together: an API, a web interface and a cron job. The API is the core of the project and the only vital part, the web interface is used to allow easy access to the data and to export xlsx files and the cron job is used to regularly update the data.

## Deploying the API server

### Requirements

- Any web server
- **PHP >= 7.4.2** with the following extension installed and enabled:
  - curl ([Client URL Library](https://www.php.net/manual/en/book.curl))
  - mbstring
  - PDO and pdo_mysql ([PDO Drivers](https://www.php.net/manual/en/ref.pdo-mysql.php))
  - yaml ([YAML Data Serialization](https://www.php.net/manual/en/book.yaml.php))
- **Composer >= 1.9.3** (https://getcomposer.org/download/)
- **MySQL >= 5.7.24**

To check the installed PHP version on your server, run:

```sh
# Linux
curl --head http://adress.com

# Windows
telnet your.webserver.com 80
HEAD / HTTP/1.0
```

you should have a similar output:

```sh
HTTP/1.1 200 OK
Date: Tue, 09 Jun 2020 13:28:14 GMT
Server: Apache/2.4.35 (Win64) OpenSSL/1.1.1d PHP/7.4.2
Content-Type: text/html;charset=UTF-8
```

To check the current MySQL version, you first need to login to the MySQL server and after that issue a standard SELECT statement:

```sh
mysql --host=localhost --user=myname --password mydb
```

```sql
SELECT VERSION();
```

### Database setup

In the `MySQL/` folder, you'll find the database dump ([dump-crouzet_pricing-202006091544.sql](./MySQL/dump-crouzet_pricing-202006091544.sql)). This dump is holding the table structure, the relations and the triggers necessary to ensure smooth operation of the server API.

All you need to do to set the database is to import these dumps using your favorite method. For the sake of this document we'll describe importing with MySLQ CLI. First, login to MySQL.

```bash
mysql --host=localhost --user=myname --password
# Enter password: *****
# Welcome to the MySQL monitor.  Commands end with ; or \g.
# Your MySQL connection id is 812
# Server version: 5.7.24 MySQL Community Server (GPL)
#
# Copyright (c) 2000, 2018, Oracle and/or its affiliates. All rights reserved.
#
# Oracle is a registered trademark of Oracle Corporation and/or its
# affiliates. Other names may be trademarks of their respective
# owners.
#
# Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.
```

once you're logged-in, start by creating a new database

```sql
CREATE DATABASE new_db_name CHARACTER SET utf8 COLLATE utf8_unicode_ci;
/* Query OK, 1 row affected (0.00 sec) */
```

exit the MySQL client (`exit;`), then run

```sh
mysql –u username –p new_db_name < dump-crouzet_pricing-202006091544.sql
# Enter password: *****
```

if everything went fine, there should be no output. To check that the import worked, log back in and show all the tables

```sql
USE new_db_name;
/* Database changed */
SHOW TABLES;
/*+---------------------------+
* | Tables_new_db_name        |
* +---------------------------+
* | prices_history            |
* | product_match             |
* | products                  |
* | stock_history             |
* +---------------------------+
* 4 rows in set (0.00 sec) */
```

🎉 MySQL is ready to rock.

### API deployment

The API is written in PHP. All you need to do is to copy the `src/api/` folder to the server and install the production dependencies.

First, copy the `src/api` files to the server. Make sure that composer is installed on the server (`composer -v`), if it is not, see: https://getcomposer.org/download/.

Then, on the server, run:

```sh
composer install
```

Depending on where you put the `index.php`, you may need to change the third line to the correct location of `vendor/autoload.php`.

```php
<?php

require __DIR__ . '/../../vendor/autoload.php'; // <- change this line
```

check the API deployment by opening your server adress (eg. http://localhost/api), you should see the documentation starting with `code: 0`

```JSON
{
  "code": 0,
  "message": "Well, here is the documentation.",
  "body": {
    ...
}
```

If everything was fine, you need to rename `db.ini.sample` to `db.ini` and fill in the values and `netcomponents.ini.sample` to `netcomponents.ini` and change the values.

🎉 The API is ready to rock.

### Security concerns

1. We strongly encourage you to create a specific MySQL user with only the `insert`, `select` and `update` permissions on your database.
2. You should protect at all costs the `db.ini` and `netcomponents.ini` files as they contains all the necessary access code to your server and the NetComponents API.

## cron job

The cron job is used to update on a regular basis the stock informations. A basic cron job is provided in [updatestock.sh](./../src/cron/updatestock.sh). You want to run the PHP script `tasks/UpdateStock.php`.

## Web client

### Requirements

To build the web client from source, you will need:

- NodeJS (https://nodejs.org/en/download/)
- Yarn (https://yarnpkg.com/getting-started/install)

### Build

First, install the dependencies:

```sh
yarn install
```

Before building the project, you want to change the content of `dbconfig.json` to reflect your current database setup. Then build the project

```sh
yarn run build
```

the output folder is `dist/web/`. Production bundle is optimized to the last bit, so you don't need to worry about the size.

Once the project has been built, simply copy the `dist/web/` folder to your server.

🎉 The web interface is ready to rock.

**Congratulations, you've successfully deployed the project!**
