# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v1.2.0] - 2020-03-31

## Added

- You can compare the stock for each part by dealer
- There is now a loading animation when requesting stock history
- We are now using netComponents to get our stock data

## Fixed

- Improved the performance when creating the table thanks to `documentFragment` (see [#17](https://gitlab.com/gaspacchio/back-to-the-future/-/issues/17))
- Updated dependencies (see [#12](https://gitlab.com/gaspacchio/back-to-the-future/-/issues/12)):
  - babel to v7.9.0
  - rector to v0.7.7
  - prettier to v2.0.2
  - autoprefixer to v9.7.5
  - chart.js to 3.0.0-alpha
- Switched from `XLSX` to `excel4node`

## [v1.1.0] - 2020-03-20

## Added

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

[v1.2.0]: https://gitlab.com/gaspacchio/back-to-the-future/-/tags/v1.2.0
[v1.1.0]: https://gitlab.com/gaspacchio/back-to-the-future/-/tags/v1.1.0
[v1.0.0]: https://gitlab.com/gaspacchio/back-to-the-future/-/tags/v1.0.0
