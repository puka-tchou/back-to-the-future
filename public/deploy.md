# Deploying to a production environment

## A quick note

The project is made of three separated components that work together: an API, a web interface and a cron job. The API is the core of the project and the only vital part, the web interface is used to allow easy access to the data and to export the data as Excel (`.xlsx`) files and the cron job is to regularly update the data.

### Pre-requirements

In order to clone the source code and deploy the project, you will need some development tools:

- **Git** (https://git-scm.com/)
- **Yarn** (https://yarnpkg.com/)
- **Composer** (https://getcomposer.org/)
- The **MySQL client** (https://dev.mysql.com/downloads/)
- **NodeJS LTS** (https://nodejs.org/en/download/)
- A nice **text editor** (why not [micro](https://micro-editor.github.io/index.html)?)

## Clone the project

To deploy the project, you will need its source files. The easiest way to get the source files is to clone the master with git and then checkout the latest tag. To do so, simply follow these steps:

1. clone the repo with `git clone https://gitlab.com/gaspacchio/back-to-the-future.git`
2. move to the directory with `cd back-to-the-future`
3. list the tags with `git tag -l`
4. checkout the latest tag with `git checkout tags/v1.0.0` (replace the version number with the latest one)

That’s it!

## Deploying the API server

### Requirements

- **Any web server** that supports PHP (Apache works just fine)
- **PHP >= 7.4.2** with the following extension installed and enabled:
  - curl ([Client URL Library](https://www.php.net/manual/en/book.curl))
  - mbstring
  - PDO and pdo_mysql ([PDO Drivers](https://www.php.net/manual/en/ref.pdo-mysql.php))
  - yaml ([YAML Data Serialization](https://www.php.net/manual/en/book.yaml.php))
- **Composer >= 1.9.3** (https://getcomposer.org/download/)
- **MySQL >= 5.7.24**

To get the PHP version that is installed on your server, you can use the following commands:

```shell-session
# Linux
curl --head http://adress.com

# Windows
telnet your.webserver.com 80
HEAD / HTTP/1.0
```

These commands should give you an output similar to what’s below. You can see the PHP version at the very end of the third line (here we are running PHP 7.4.2).

```shell-session
HTTP/1.1 200 OK
Date: Tue, 09 Jun 2020 13:28:14 GMT
Server: Apache/2.4.35 (Win64) OpenSSL/1.1.1d PHP/7.4.2
Content-Type: text/html;charset=UTF-8
```

To get the version of MySQL installed on your server, you will need to log in to your MySQL server using the first command and then issue a `SELECT` statement:

```shell-session
mysql --host=localhost --user=myname --password
```

```sql
SELECT VERSION();
```

You should get something similar to what’s below. Here, we can see that we are running MySQL version 5.7.24.

```sql
+-----------+
| VERSION() |
+-----------+
| 5.7.24    |
+-----------+
1 row in set (0.03 sec)
```

_Friendly reminder: semicolons are mandatory in SQL_

**Now that we have validated our setup, we can start to setup the database.**

### Database setup

In the `public/_MySQL/` folder, you will find the database dump ([dump_v1.0.0.sql](./_MySQL/dump_v1.0.0.sql)). This dump is holding the table structure, the relations and the triggers necessary to ensure smooth operation of the server API.

All you need to do to set the database up is to import this dump using your favorite method. For the sake of this document, we'll walk you through the steps of importing with the MySQL CLI.

First, log in to your MySQL server. Please, note that `localhost` and `root` are placeholder values and that you will need to use the address and username that are specific to your server:

```shell-session
mysql --host=localhost --user=root --password
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

once you're successfully logged in, create a new database. Once again, note that `new_db_name` is a placeholder value and that you should name it properly.

```sql
CREATE DATABASE new_db_name CHARACTER SET utf8 COLLATE utf8_unicode_ci;
/* Query OK, 1 row affected (0.00 sec) */
```

exit the MySQL client (`exit;`), then run the import command. The path to the dump is relative to your current working directory and you may need to adapt it (eg. change it to `.public/_MySQL/dump_v1.0.0.sql`).

```shell-session
mysql –u username –p new_db_name < dump_v1.0.0.sql
# Enter password: *****
```

if everything went fine, there should be no output. To check that the import worked, log back in your MySQL server (`mysql --host=localhost --user=root --password`) and `SHOW` all the tables. The output should be similar to the one pictured below:

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

The API is written in vanilla PHP. To deploy the files, you need to:

1. dump the autoloader
2. write in the correct settings to allow the API to connect to your database
3. copy the source files to the server

#### Security concerns

1. We strongly encourage you to create a specific MySQL user with only the `insert`, `select` and `update` permissions on your database and to use that user when searching the historical data.
2. You should protect at all costs the `db.ini` and `netcomponents.ini` files as they contains all the necessary access code to your server and the NetComponents API.

#### Dump the autoloader

As per the PHP.net documentation:

> Many developers writing object-oriented applications create one PHP source file per class definition. One of the biggest annoyances is having to write a long list of needed includes at the beginning of each script (one for each class).
>
> In PHP 5, this is no longer necessary. The [spl_autoload_register()](https://www.php.net/manual/en/function.spl-autoload-register.php) function registers any number of autoloaders, enabling for classes and interfaces to be automatically loaded if they are currently not defined. By registering autoloaders, PHP is given a last chance to load the class or interface before it fails with an error.

Composer can be used to automatically generate this autoload file (see [autoloading](https://getcomposer.org/doc/01-basic-usage.md#autoloading) in the composer manual). All you have to do is dump the autoloader.

First, make sure that your CWD (current working directory) is the root of the project. Then, dump the autoloader:

```shell-session
composer dump-autoload --optimize --no-dev
```

Composer will write the autoload file in `vendor/autoload.php`. This file is needed for the API to properly work and it should be present on your server. To do so, copy the autoloader to the `src/api` folder:

```shell-session
cp vendor/autoload.php src/api
```

After that, edit the third line in `src/api/index.php` to point to the correct location _(you could use `micro src/api/index.php`)_:

```php
<?php

require __DIR__ . 'autoload.php'; // <- this line was modified
```

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

#### Copy the files to the server

Then, copy the `src/api` folder to the server using your favorite method. Once the files are online, you can check if the API deployment was successful by browsing to your server (eg. http://localhost/api). If you get the documentation in a JSON format, everything should be fine.

```json
{
  "code": 0,
  "message": "Well, here is the documentation.",
  "body": {
    ...
}
```

🎉 The API is ready to rock.

## cron job

The cron job is used to update on a regular basis the stock information. A basic cron job example is provided in `src/cron/updatestock.sh`. You want the cron job to run the PHP script located in `src/tasks/UpdateStock.php`.

```shell
# cron task to update the stock of all the parts each week
#
# This will launch the task each monday at 00:00
0 0 * * MON /usr/bin/php /src/api/tasks/UpdateStock.php

```

## Web client

The web client is the main front-end, it’s what users will see and interact with. There is a documentation on how to use this interface here: https://gaspacchio.gitlab.io/back-to-the-future/#/.

### Build

First, install the dependencies:

```shell-session
yarn install
```

Before building the project, you want to change the content of `src/web/dbconfig.json` to reflect your current database setup:

```json
{
	"db_address": "http://src.test/api/parts"
}
```

Then build the project:

```shell-session
yarn run build
```

If the build succeeded, you should have a similar output:

```shell-session
$ yarn run build
yarn run v1.22.5
$ parcel build --experimental-scope-hoisting src/web/index.html -d dist/web/
--public-url .
√  Built in 24.63s.

dist\web\main.8380e6ae.js                       373.55 KB    21.34s
dist\web\cat.waiting.c74f143a.gif                27.57 KB     4.87s
dist\web\style.46ca8b8a.css                      16.11 KB    12.41s
dist\web\main.93a40e52.css                       16.04 KB    11.95s
dist\web\android-chrome-256x256.e2254900.png     13.13 KB     4.80s
dist\web\android-chrome-192x192.f1e4f2a7.png     12.18 KB     4.80s
dist\web\favicon-32x32.573a33b0.png              10.64 KB     4.80s
dist\web\favicon-16x16.c55144e8.png              10.49 KB     4.81s
dist\web\icon.color.48x.6d2bd575.png              8.47 KB     4.81s
dist\web\index.html                               3.17 KB     7.73s
dist\web\apple-touch-icon.c2f69cc8.png            2.51 KB     4.80s
dist\web\safari-pinned-tab.a7a2594b.svg           1.38 KB     4.81s
dist\web\site.webmanifest                           313 B     4.81s
✨  Done in 27.40s.
```

The output is located in the `dist/` folder. The production bundle is already optimized to the last bit (tree shaking and image compression are implemented) and the dependencies are embedded in the bundle, so you don't need to worry about the size nor installing the dependencies on the server.

Once the project has been built, simply copy the `dist/` folder to your server.

🎉 The web interface is ready to rock.

**Congratulations, you've successfully deployed the project!**
