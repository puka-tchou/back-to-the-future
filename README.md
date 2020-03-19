# Stock and pricing history

[![Repository](https://badgen.net/badge/repository/gitlab/orange?icon=gitlab)](https://gitlab.com/gaspacchio/back-to-the-future) [![MIT license](https://badgen.net/badge/license/MIT/blue)](https://gitlab.com/gaspacchio/back-to-the-future/-/blob/master/LICENSE) [![Gitlab pipeline status](https://gitlab.com/gaspacchio/back-to-the-future/badges/master/pipeline.svg)](https://gitlab.com/gaspacchio/back-to-the-future/-/commits/master) [![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=gaspacchio_back-to-the-future&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=gaspacchio_back-to-the-future)

## Table of contents

- [Stock and pricing history](#stock-and-pricing-history)
  - [Table of contents](#table-of-contents)
  - [The purpose](#the-purpose)
  - [Documentation](#documentation)
  - [Contributing](#contributing)

## The purpose

This tool is intended to capture the stock history for a set of part-numbers on various online retailers. Here is a full list of the supported online store as of v1.0.0:

| Store         | Status |
| ------------- | ------ |
| NetComponents | ✅     |
| DigiKey       | 🚫     |

## Documentation

The documentation for the API and the web interface is located [in the GitLab pages for this project](https://gaspacchio.gitlab.io/back-to-the-future): https://gaspacchio.gitlab.io/back-to-the-future.

## Contributing

Follow these steps and you should be up and running. The prerequisites are:

- A recent version of NodeJS and Yarn ([download NodeJS](https://nodejs.org/en/), [download Yarn](https://yarnpkg.com/getting-started/install))
- A recent version of PHP and Composer ([download PHP](https://www.php.net/downloads.php), [download Composer](https://getcomposer.org/download/))
- An apache server

If you would like to be up and running quickly, you should try Laragon: [download Laragon](https://laragon.org/download/)

1. Clone the repo
   ```bash
   git clone https://gitlab.com/gaspacchio/back-to-the-future.git
   ```
2. `cd` to it
   ```bash
   cd back-to-the-future
   ```
3. Install the dependencies
   ```bash
   yarn install
   ```
   ```bash
   composer install
   ```
4. Start the development server
   ```bash
   yarn start
   ```
