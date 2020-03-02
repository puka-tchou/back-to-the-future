# Summary

| Members                                                                | Descriptions |
| ---------------------------------------------------------------------- | ------------ |
| `namespace`[`data::Database`](#namespace-datadatabase)                 |
| `namespace`[`data::Product`](#namespace-dataproduct)                   |
| `namespace`[`data::Stock`](#namespace-datastock)                       |
| `namespace`[`dealers::AlliedElec`](#namespace-dealersalliedelec)       |
| `namespace`[`dealers::DigiKey`](#namespace-dealersdigikey)             |
| `namespace`[`dealers::NetComponents`](#namespace-dealersnetcomponents) |
| `namespace`[`route::Route`](#namespace-routeroute)                     |
| `namespace`[`tasks::UpdateStock`](#namespace-tasksupdatestock)         |
| `namespace`[`utilities::Reader`](#namespace-utilitiesreader)           |
| `namespace`[`utilities::Reporter`](#namespac-eutilitiesreporter)       |

# namespace `data::Database`

## Summary

| Members                                                          | Descriptions                |
| ---------------------------------------------------------------- | --------------------------- |
| `class`[`data::Database::Database`](#class-datadatabasedatabase) | Interact with the database. |

# class `data::Database::Database`

Interact with the database.

## Summary

| Members                                                                                                            | Descriptions                                             |
| ------------------------------------------------------------------------------------------------------------------ | -------------------------------------------------------- |
| `public PDO`[`$connection`](#class-datadatabasedatabase_1abf673f35215519bd0d758b43abcd4b72)                        |
| `public`[`__construct`](#class-datadatabasedatabase_1a84b08c924ed98e1109ac613b5174dc5e)`()`                        | Construct a new connection to the database.              |
| `public`[`getAllProducts`](#class-datadatabasedatabase_1a8952ae83490cadd6cf1da12af686bd8e)`()`                     | Get all products stored in the database.                 |
| `public`[`partNumberExists`](#class-datadatabasedatabase_1abe9f8501262a7d402a7183cdb9096506)`(string $partNumber)` | Check if a given part number is present in the database. |

## Members

#### `public PDO`[`$connection`](#class-datadatabasedatabase_1abf673f35215519bd0d758b43abcd4b72)

#### `public`[`__construct`](#class-datadatabasedatabase_1a84b08c924ed98e1109ac613b5174dc5e)`()`

Construct a new connection to the database.

> Todo: Make this a singleton.

#### Returns

void

#### `public`[`getAllProducts`](#class-datadatabasedatabase_1a8952ae83490cadd6cf1da12af686bd8e)`()`

Get all products stored in the database.

#### Returns

array The products in the database.

#### `public`[`partNumberExists`](#class-datadatabasedatabase_1abe9f8501262a7d402a7183cdb9096506)`(string $partNumber)`

Check if a given part number is present in the database.

#### Parameters

- string`$partNumber` The part number to test (case insensitive).

#### Returns

bool `true` if the part-number exists in the database.

# namespace `data::Product`

## Summary

| Members                                                     | Descriptions                         |
| ----------------------------------------------------------- | ------------------------------------ |
| `class`[`data::Product::Product`](#classdataproductproduct) | Manipulate products in the database. |

# class `data::Product::Product`

Manipulate products in the database.

## Summary

| Members                                                                                                                                     | Descriptions                   |
| ------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------ |
| `public`[`add`](#classdataproductproduct_1af9a610336b3f441afc9c9a638f59dca0)`(string $partNumber,int $updateInterval,string $manufacturer)` | Add a product to the database. |

## Members

#### `public`[`add`](#classdataproductproduct_1af9a610336b3f441afc9c9a638f59dca0)`(string $partNumber,int $updateInterval,string $manufacturer)`

Add a product to the database.

#### Parameters

- string`$partNumber` The product part-number.

- integer`$updateInterval` The time, in days, between each update of the stock and the prices.

- string`$manufacturer` The part manufacturer.

#### Returns

array The result of the operation.

# namespace `data::Stock`

## Summary

| Members                                             | Descriptions                                        |
| --------------------------------------------------- | --------------------------------------------------- |
| `class`[`data::Stock::Stock`](#classdatastockstock) | Manipulate stock data, get data from online stores. |

# class `data::Stock::Stock`

Manipulate stock data, get data from online stores.

## Summary

| Members                                                                                             | Descriptions                                                            |
| --------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------- |
| `public`[`getFromDealers`](#classdatastockstock_1a709dd00f247913b29a208eeab53b8a73)`(string $part)` | Retrieve stock informations for a given part number from online stores. |

## Members

#### `public`[`getFromDealers`](#classdatastockstock_1a709dd00f247913b29a208eeab53b8a73)`(string $part)`

Retrieve stock informations for a given part number from online stores.

#### Parameters

- string`$part` The part number to test.

#### Returns

array

# namespace `dealers::AlliedElec`

## Summary

| Members                                                                         | Descriptions                                                               |
| ------------------------------------------------------------------------------- | -------------------------------------------------------------------------- |
| `class`[`dealers::AlliedElec::AlliedElec`](#classdealersallied_elecallied_elec) | [AlliedElec](#classdealersallied_elecallied_elec) distributor stock check. |

# class `dealers::AlliedElec::AlliedElec`

```
class dealers::AlliedElec::AlliedElec
  : public dealers\iDealer\iDealer
```

[AlliedElec](#classdealersallied_elecallied_elec) distributor stock check.

## Summary

| Members                                                                                                             | Descriptions                            |
| ------------------------------------------------------------------------------------------------------------------- | --------------------------------------- |
| `public`[`getStock`](#classdealersallied_elecallied_elec_1aed15a0ae729226fb45b9febeb00217fc)`(string $part_number)` | Check the stock of a given part number. |

## Members

#### `public`[`getStock`](#classdealersallied_elecallied_elec_1aed15a0ae729226fb45b9febeb00217fc)`(string $part_number)`

Check the stock of a given part number.

#### Parameters

- string`$part_number` The part number that you want to check.

#### Returns

array An array containing the stock, the stock on order and the minimum number of parts to order.

# namespace `dealers::DigiKey`

## Summary

| Members                                                             | Descriptions |
| ------------------------------------------------------------------- | ------------ |
| `class`[`dealers::DigiKey::DigiKey`](#classdealersdigi_keydigi_key) |

# class `dealers::DigiKey::DigiKey`

```
class dealers::DigiKey::DigiKey
  : public dealers\iDealer\iDealer
```

## Summary

| Members                                                                                                       | Descriptions                                                         |
| ------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------- |
| `public`[`getStock`](#classdealersdigi_keydigi_key_1a6c01abf6efac435cfcac2766013f289b)`(string $part_number)` | Get stock information from [DigiKey](#classdealersdigi_keydigi_key). |

## Members

#### `public`[`getStock`](#classdealersdigi_keydigi_key_1a6c01abf6efac435cfcac2766013f289b)`(string $part_number)`

Get stock information from [DigiKey](#classdealersdigi_keydigi_key).

> Todo: Implementation may need NodeJS because [DigiKey](#classdealersdigi_keydigi_key) dynamically generates its pages.

#### Returns

string[]

# namespace `dealers::NetComponents`

## Summary

| Members                                                                                     | Descriptions |
| ------------------------------------------------------------------------------------------- | ------------ |
| `class`[`dealers::NetComponents::NetComponents`](#classdealersnet_componentsnet_components) |

# class `dealers::NetComponents::NetComponents`

```
class dealers::NetComponents::NetComponents
  : public dealers\iDealer\iDealer
```

## Summary

| Members                                                                                                                   | Descriptions |
| ------------------------------------------------------------------------------------------------------------------------- | ------------ |
| `public`[`getStock`](#classdealersnet_componentsnet_components_1ab1d3cacc316cb5cc1c3934e15db3ad74)`(string $part_number)` | #### Returns |

## Members

#### `public`[`getStock`](#classdealersnet_componentsnet_components_1ab1d3cacc316cb5cc1c3934e15db3ad74)`(string $part_number)`

#### Returns

mixed[]

# namespace `route::Route`

## Summary

| Members                                               | Descriptions                                       |
| ----------------------------------------------------- | -------------------------------------------------- |
| `class`[`route::Route::Route`](#classrouterouteroute) | This class represents the different API endpoints. |

# class `route::Route::Route`

This class represents the different API endpoints.

## Summary

| Members                                                                                            | Descriptions                                                |
| -------------------------------------------------------------------------------------------------- | ----------------------------------------------------------- |
| `public`[`add`](#classrouterouteroute_1aa769945bab88d344f6d2aac19b30975d)`()`                      | Add products to the database from a given CSV file.         |
| `public`[`documentation`](#classrouterouteroute_1a8b7e2c6813d5fe5bfc84b416d284a4ff)`(string $url)` | Read API documentation from a YAML file.                    |
| `public`[`products`](#classrouterouteroute_1a46fd15b098db5e273b61e72fb29a7056)`()`                 | Get all products from the database.                         |
| `public`[`part`](#classrouterouteroute_1a11452c509e1ca2a505d9ae6bf70e65b6)`()`                     | Get stock information for a part-number.                    |
| `public`[`parts`](#classrouterouteroute_1aea9ed350a6a26aa6248845085242e699)`()`                    | Get stock information for a set of parts in a CSV file.     |
| `public`[`update`](#classrouterouteroute_1a99765cdc6c3de4f63ea4261b81f85466)`()`                   | Update stock informations for a set of parts in a CSV file. |

## Members

#### `public`[`add`](#classrouterouteroute_1aa769945bab88d344f6d2aac19b30975d)`()`

Add products to the database from a given CSV file.

#### Returns

void

#### `public`[`documentation`](#classrouterouteroute_1a8b7e2c6813d5fe5bfc84b416d284a4ff)`(string $url)`

Read API documentation from a YAML file.

#### Parameters

- string`$url` The request made to the server.

#### Returns

void

#### `public`[`products`](#classrouterouteroute_1a46fd15b098db5e273b61e72fb29a7056)`()`

Get all products from the database.

#### Returns

void

#### `public`[`part`](#classrouterouteroute_1a11452c509e1ca2a505d9ae6bf70e65b6)`()`

Get stock information for a part-number.

#### Returns

void

#### `public`[`parts`](#classrouterouteroute_1aea9ed350a6a26aa6248845085242e699)`()`

Get stock information for a set of parts in a CSV file.

#### Returns

string

#### `public`[`update`](#classrouterouteroute_1a99765cdc6c3de4f63ea4261b81f85466)`()`

Update stock informations for a set of parts in a CSV file.

#### Returns

void

# namespace `tasks::UpdateStock`

## Summary

| Members                                                                         | Descriptions       |
| ------------------------------------------------------------------------------- | ------------------ |
| `class`[`tasks::UpdateStock::UpdateStock`](#classtasksupdate_stockupdate_stock) | Update stock data. |

# class `tasks::UpdateStock::UpdateStock`

Update stock data.

## Summary

| Members                                                                                                             | Descriptions                                  |
| ------------------------------------------------------------------------------------------------------------------- | --------------------------------------------- |
| `public`[`addRecord`](#classtasksupdate_stockupdate_stock_1af6ed3c40b9cf6c6f63a111c147895ee0)`(string $partNumber)` | Add a stock record for the given part-number. |

## Members

#### `public`[`addRecord`](#classtasksupdate_stockupdate_stock_1af6ed3c40b9cf6c6f63a111c147895ee0)`(string $partNumber)`

Add a stock record for the given part-number.

#### Parameters

- string`$partNumber` The part-number.

#### Returns

string[]|bool[]|mixed[][]|bool[]|bool `true` if the operation succeeded, an `array` containing informations about the error if the operation did not succeeded.

# namespace `utilities::Reader`

## Summary

| Members                                                           | Descriptions                                  |
| ----------------------------------------------------------------- | --------------------------------------------- |
| `class`[`utilities::Reader::Reader`](#classutilitiesreaderreader) | Construct a list of part numbers from a file. |

# class `utilities::Reader::Reader`

Construct a list of part numbers from a file.

## Summary

| Members                                                                                                     | Descriptions                             |
| ----------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| `public`[`readCSVFile`](#classutilitiesreaderreader_1afff1dfdf56dcbb80859356959e8029ec)`(string $path)`     | Reads a file input in CSV format.        |
| `public`[`readYAMLFile`](#classutilitiesreaderreader_1a86570498dc2078ed7b9a3339a3561186)`(string $path)`    | Reads a file input in YAML format.       |
| `public`[`readFromString`](#classutilitiesreaderreader_1a0e0d23620519fdf1db8dd1356355c1fd)`(string $input)` | Reads a YAML string and return an array. |

## Members

#### `public`[`readCSVFile`](#classutilitiesreaderreader_1afff1dfdf56dcbb80859356959e8029ec)`(string $path)`

Reads a file input in CSV format.

#### Parameters

- string`$path` The path to the file.

#### Returns

array

#### `public`[`readYAMLFile`](#classutilitiesreaderreader_1a86570498dc2078ed7b9a3339a3561186)`(string $path)`

Reads a file input in YAML format.

#### Parameters

- string`$path`

#### Returns

array

#### `public`[`readFromString`](#classutilitiesreaderreader_1a0e0d23620519fdf1db8dd1356355c1fd)`(string $input)`

Reads a YAML string and return an array.

#### Parameters

- string`$input` The YAML string to parse.

#### Returns

array

# namespace `utilities::Reporter`

## Summary

| Members                                                                   | Descriptions                                                                                           |
| ------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------ |
| `class`[`utilities::Reporter::Reporter`](#classutilitiesreporterreporter) | The [Reporter](#classutilitiesreporterreporter) class is responsible for returning data to the client. |

# class `utilities::Reporter::Reporter`

The [Reporter](#classutilitiesreporterreporter) class is responsible for returning data to the client.

## Summary

| Members                                                                                                                    | Descriptions                                                                       |
| -------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------- |
| `public`[`send`](#classutilitiesreporterreporter_1a70aa26e130392b95ac0b137c18081dff)`( $body,string $message,int $code)`   | Format and send the appropriate answer, accompanied with the correct HTTP headers. |
| `public`[`format`](#classutilitiesreporterreporter_1a952dcbb8b9e1bf21c215363b5ce7ca34)`( $body,string $message,int $code)` | #### Returns                                                                       |

## Members

#### `public`[`send`](#classutilitiesreporterreporter_1a70aa26e130392b95ac0b137c18081dff)`( $body,string $message,int $code)`

Format and send the appropriate answer, accompanied with the correct HTTP headers.

#### Parameters

- mixed`$body` The body of the answer.

- string`$message` The message should complement the status code.

- integer`$code` The code. `0` if everything is OK.

#### Returns

mixed[]

#### `public`[`format`](#classutilitiesreporterreporter_1a952dcbb8b9e1bf21c215363b5ce7ca34)`( $body,string $message,int $code)`

#### Returns

mixed[]

Generated by [Moxygen](https://sourcey.com/moxygen)
