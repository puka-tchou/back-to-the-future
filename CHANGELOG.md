# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v1.3.1] - 2020-12-10

### Added

- You can export the stock data as an Excel file
- The list of parts is paginated and you can sort it by column
- The summary is paginated and you can sort it by column

### Updated

- spectre.css 0.5.8 -> 0.5.9
- chart.js 3.0.0-alpha -> 2.9.4
- Dev dependencies:
  - @prettier/plugin-xml 0.7.2 -> 0.12.2
  - parcel 1.12.4 -> 2.0.0-beta.1
  - prettier 2.0.2 -> 2.1.2
  - sass 1.26.3 -> 1.28.0
  - stylelint 13.2.1 -> 13.7.2
  - stylelint-config-prettier 8.0.1 -> 8.0.2
  - squizlabs/php_codesniffer 3.5.4 -> 3.5.8

## [v1.2.0] - 2020-03-31

### Added

- You can compare the stock for each part by dealer
- There is now a loading animation when requesting stock history
- We are now using netComponents to get our stock data

### Fixed

- Improved the performance when creating the table thanks to `documentFragment` (see [#17](https://gitlab.com/gaspacchio/back-to-the-future/-/issues/17))
- Updated dependencies (see [#12](https://gitlab.com/gaspacchio/back-to-the-future/-/issues/12)):
  - babel to v7.9.0
  - rector to v0.7.7
  - prettier to v2.0.2
  - autoprefixer to v9.7.5
  - chart.js to 3.0.0-alpha
- Switched from `XLSX` to `excel4node`

## [v1.1.0] - 2020-03-20

### Added

- You can now add parts to the database from the web interface.

## [v1.0.0] - 2020-03-18

### Added

- `/` route to get the documentation.
- `/add` route to add part-numbers to the database.
- `/part` route to get the stock history for a part-number.
- `/parts` route to get the stock for a given set of part-numbers.
- `/products` route to list all part-numbers in the database.
- `/update` route to request a manual update of a set of part-numbers.
- A web interface for common operations.
- A documentation website published on [Gitlab pages](https://gaspacchio.gitlab.io/back-to-the-future/#/).

[v1.3.1]: https://gitlab.com/gaspacchio/back-to-the-future/-/tags/v1.3.1
[v1.2.0]: https://gitlab.com/gaspacchio/back-to-the-future/-/tags/v1.2.0
[v1.1.0]: https://gitlab.com/gaspacchio/back-to-the-future/-/tags/v1.1.0
[v1.0.0]: https://gitlab.com/gaspacchio/back-to-the-future/-/tags/v1.0.0
