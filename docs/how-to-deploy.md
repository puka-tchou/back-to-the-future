# Deploying to a production environment

## A quick note

The project is made of three separate components that work together: an API, a web interface, and a cron job. The API is the core of the project and the only vital part, the web interface is used to allow easy access to the data and to export the data as Excel (`.xlsx`) files and the cron job is to regularly update the data.

## Pre-requirements

### Developpement tools

In order to clone the source code and deploy the project, you need some development tools:

- **Git** (https://git-scm.com/)
- **Yarn** (https://yarnpkg.com/)
- **Composer** (https://getcomposer.org/)
- The **MySQL client** (https://dev.mysql.com/downloads/)
- **NodeJS LTS** (https://nodejs.org/en/download/)
- A nice **text editor** (why not [micro](https://micro-editor.github.io/index.html)?)

### Clone the project

To deploy the project, you need its source files. The easiest way to get the source files is to clone the master with git and then checkout the latest tag. To do so, simply follow these steps:

1. clone the repo

```git
git clone https://gitlab.com/gaspacchio/back-to-the-future.git
```

2. move to the folder you just cloned

```console
cd back-to-the-future
```

3. change your branch to be on 'deploy'

```git
git checkout deploy
```

That’s it, you are ready to deploy!

## Deploying the API to the server

### Requirements

- **Any web server** that supports PHP (Apache works just fine)
- **PHP 7.4.2^** with the following extension installed and enabled:
  - curl ([Client URL Library](https://www.php.net/manual/en/book.curl))
  - mbstring
  - PDO and pdo_mysql ([PDO Drivers](https://www.php.net/manual/en/ref.pdo-mysql.php))
  - yaml ([YAML Data Serialization](https://www.php.net/manual/en/book.yaml.php))
- **Composer 1.9.3^** (https://getcomposer.org/download/)
- **MySQL 5.7.24^** (https://dev.mysql.com/downloads/mysql/)

### Checking requirements

To get the PHP version that is installed on your server, you can use the following commands:

```console
# On a Linux-based OS
curl --head http://adress.com

# on a Windows OS
telnet your.webserver.com 80
HEAD / HTTP/1.0
```

These commands should give you an output similar to what’s below. You can see the PHP version at the very end of the third line (here we are running PHP 7.4.2 on an Apache 2.3.45 server).

```console
HTTP/1.1 200 OK
Date: Tue, 09 Jun 2020 13:28:14 GMT
Server: Apache/2.4.35 (Win64) OpenSSL/1.1.1d PHP/7.4.2
Content-Type: text/html;charset=UTF-8
```

To get the version of MySQL that is installed on your server, you will need to log in to your MySQL server and then issue a `SELECT` statement:

```console
λ mysql --host=localhost --user=root --password
Enter password: *****
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 16
Server version: 5.7.24 MySQL Community Server (GPL)

Copyright (c) 2000, 2018, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> SELECT VERSION();
+-----------+
| VERSION() |
+-----------+
| 5.7.24    |
+-----------+
1 row in set (0.00 sec)

mysql> EXIT;
```

_Friendly reminder: semicolons are mandatory in SQL_

Finally, you can check that you have the required PHP extensions thanks to Composer:

```console
λ composer check-platform-reqs
The "symfony/thanks" plugin was skipped because it requires a Plugin API version ("^1.0") that does not match your Composer installation ("2.0.0"). You may need to run composer update with the "--no-plugins" option.
ext-dom        20031129    success
ext-filter     7.4.2       success
ext-json       7.4.2       success
ext-libxml     7.4.2       success
ext-mbstring   7.4.2       success
ext-pdo        7.4.2       success
ext-pdo_mysql  7.4.2       success
ext-phar       7.4.2       success
ext-simplexml  7.4.2       success
ext-tokenizer  7.4.2       success
ext-xml        7.4.2       success
ext-xmlwriter  7.4.2       success
ext-yaml       2.0.4       success
php            7.4.2       success
```

**Now that we have validated our setup, we can start to deploy the database.**

### Database setup

In the [`_MySQL/`](_MySQL/) folder, you will find the database dump ([dump_v1.4.0.sql](./_MySQL/dump_v1.4.0.sql)). This dump is holding the table structure, the relations, and the triggers necessary to ensure the smooth operation of the server API.

All you need to do to set the database up is to import this dump using your favorite method. For the sake of this document, we'll walk you through the steps of importing with the MySQL CLI.

First, log in to your MySQL server. Please, note that `localhost` and `root` are placeholder values and that you will need to use the address and username that are specific to your server:

```console
mysql --host=localhost --user=root --password
```

once you're successfully logged in, create a new database. Once again, note that `new_db_name` is a placeholder value and that you should name it properly.

```sql
CREATE DATABASE new_db_name CHARACTER SET utf8 COLLATE utf8_unicode_ci;
/* Query OK, 1 row affected (0.00 sec) */
```

exit the MySQL client (`exit;`), then run the import command. The path to the dump is relative to your current working directory and you may need to adapt it (eg. change it to `docs/_MySQL/dump_v1.4.0.sql`).

```console
mysql –u username –p new_db_name < dump_v1.4.0.sql
# Enter password: *****
```

if everything went fine, there should be no output. To check that the import worked, log back into your MySQL server (`mysql --host=localhost --user=root --password`) and `SHOW` all the tables. The output should be similar to the one pictured below:

```sql
USE new_db_name;
/* Database changed */
SHOW TABLES;
/*+---------------------------+
* | Tables_new_db_name        |
* +---------------------------+
* | products                  |
* | stock_history             |
* +---------------------------+
* 2 rows in set (0.00 sec) */
```

🎉 MySQL is ready to rock.

### API deployment

The API is written in vanilla PHP without dependencies. To deploy the files, you'll need to:

1. Copy the source files to the server;
2. Update the configuration to allow the API to connect to your database;
3. Dump the autoloader;

#### Security concerns

1. We strongly encourage you to create a specific MySQL user with only the `insert`, `select`, and `update` permissions on your database and to use that user when searching the historical data.
2. You should protect at all costs the `db.ini` and `netcomponents.ini` files as they contain all the necessary access code to your server and the NetComponents API.

#### Copy the files to the server

Copy the `src/api` folder and the `composer.json` file to the server using your favorite method.

#### Database settings

You will now need to fill in the details to allow the database connection to work.

Start by renaming `db.ini.sample` to `db.ini` and fill in the values:

```ini
host = 127.0.0.1
name = new_db_name
user = user
pass = password
type = mysql
```

Then, rename `netcomponents.ini.sample` to `netcomponents.ini` and change the values as well:

```ini
password = user:pass
```

#### Dump the autoloader

As per the PHP.net documentation:

> Many developers writing object-oriented applications create one PHP source file per class definition. One of the biggest annoyances is having to write a long list of needed includes at the beginning of each script (one for each class).
>
> In PHP 5, this is no longer necessary. The [spl_autoload_register()](https://www.php.net/manual/en/function.spl-autoload-register.php) function registers any number of autoloaders, enabling classes and interfaces to be automatically loaded if they are currently not defined. By registering autoloaders, PHP is given a last chance to load the class or interface before it fails with an error.

Composer can be used to automatically generate this autoload file (see [autoloading](https://getcomposer.org/doc/01-basic-usage.md#autoloading) in the composer manual). All you have to do is dump the autoloader.

First, make sure that your CWD (current working directory) is the root of the project. Then, dump the autoloader:

```console
composer dump-autoload --optimize --no-dev
```

Composer will write the autoload files in `vendor/autoload.php` and `vendor/composer/`. This file and folders are needed for the API to properly work and they should be present on your server. To do so, copy the `vendor/` folder to your server.

🎉 The API is ready to rock.

## cron job

_TODO (sorry about that)_

## Web client

The web client is the main front-end, it’s what users will see and interact with.

### Build

First, install the dependencies:

```console
yarn install
```

Before building the project, you want to change the content of `src/web/apiconfig.json` to reflect your current database setup:

```json
{
	"db_address": "http://your-api.com"
}
```

Then build the project:

```console
yarn run build
```

If the build succeeded, you should have a similar output:

```console
λ yarn build
yarn run v1.22.5
$ rm -v ./node_modules/slim-select/.browserslistrc && parcel build "src/web/index.html" --dist-dir "dist/www/" --public-url "." --cache-dir ".cache/parcel/"

removed './node_modules/slim-select/.browserslistrc'

@parcel/transformer-postcss: WARNING: Using a JavaScript PostCSS config file means losing out on caching features of Parcel. Use a .postcssrc(.json) file whenever possible.
@parcel/transformer-postcss: WARNING: Using a JavaScript PostCSS config file means losing out on caching features of Parcel. Use a .postcssrc(.json) file whenever possible.

√ Built in 106.28s

dist\www\index.html                          3.63 KB     6.51s
dist\www\main.cdb49de2.js                  486.51 KB    44.20s
dist\www\main.e44d52aa.css                  59.27 KB     6.51s
dist\www\main.08baf1d0.js                   486.5 KB    44.19s
dist\www\style.b903252a.css                  1.31 KB    30.12s
dist\www\cat.waiting.2b1c2825.gif           27.57 KB     5.29s
dist\www\apple-touch-icon.f3481131.png       2.51 KB     5.29s
dist\www\favicon-32x32.841b46c6.png         10.64 KB     5.29s
dist\www\favicon-16x16.9db93fcb.png         10.49 KB     5.29s
dist\www\site.a91b1821.webmanifest             413 B     5.29s
dist\www\safari-pinned-tab.4769c917.svg      1.33 KB     5.29s
dist\www\logo.min.c6b1ffff.svg                 328 B     5.29s
Done in 115.21s.
```

The output is located in the `dist/www/` folder. The production bundle is already optimized to the last bit (tree shaking and image compression are implemented) and the dependencies are embedded in the bundle, so you don't need to worry about the size nor installing the dependencies on the server.

Once the project has been built, simply copy the `dist/www/` folder to your server.

🎉 The web interface is ready to rock.

**Congratulations, you've successfully deployed the project!**
