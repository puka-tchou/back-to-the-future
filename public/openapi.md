# OpenAPI documentation

```yaml
openapi: 3.0.2
info:
  version: 1.2.0
  title: Crouzet historical data API
  description: This API is designed to interact with the historical stock data of Crouzet's parts.
paths:
  /:
    get:
      summary: Prints the documentation.
    responses:
      '200':
        description: Success 🎉
  /add:
    post:
      summary: Add the part-numbers to the database.
      description: Post a form input to this endpoint. It must have a field named 'parts' and it should be a CSV file. Make sure that you the 'Content-Type' header to 'multipart/form-data'.
      requestBody:
        $ref: '#/components/requestBodies/CSVFile'
  /part:
    get:
      summary: Get the stock history for a given part number.
      parameters:
        - name: id
          required: true
          in: query
          description: The part-number.
          schema:
            type: string
        - name: source
          in: query
          description: >
            Choose the data source:
             * `DB` - Get the data only from the database
             * `WEB` - Get the data from the resellers websites
             * `BOTH` - Get the data from both the database and the resellers websites
          schema:
            type: string
            enum: [DB, WEB, BOTH]
  /parts:
    post:
      summary: Get the stock history for a given set of parts.
      description: Post a form input to this endpoint. It must have a field named `parts` and it should be a CSV file. Make sure that you set the `Content-Type` header to `multipart/form-data`.
      requestBody:
        $ref: '#/components/requestBodies/CSVFile'
  /products:
    get:
      summary: Get all the part-numbers in the database.
      description: This endpoint is paginated and will only return 100 parts. If you need to get the parts 100 to 199, set the `page` parmeter to 1.
      parameters:
        - name: page
          in: query
          description: The page number.
          schema:
            type: integer
  /update:
    post:
      summary: Request a manual update of the stock data for a set of part-numbers.
      description: Post a form input to this endpoint. It must have a field named `parts` and it should be a CSV file. Make sure that you the `Content-Type` header to `multipart/form-data`.
      requestBody:
        $ref: '#/components/requestBodies/CSVFile'

components:
  requestBodies:
    CSVFile:
      content:
        multipart/form-data:
          schema:
            type: object
            properties:
              parts:
                type: string
                format: binary
                description: A CSV file containing all the part-numbers to add.
  examples:
    sampleAddFile:
      summary: A sample CSV file valid when adding parts
      externalValue: ''
    sampleGetFile:
      summary: A sample CSV file valid when getting parts
      externalValue: ''
```

## Sample response

```json
{
	"code": 0,
	"message": "Everything went fine.",
	"body": {
		"WEB": {
			"part_number": "CWD4850",
			"date_checked": "2020-02-25",
			"stock": {
				"parts_in_stock": 35,
				"parts_on_order": 40,
				"parts_min_order": -1
			},
			"supplier": "alliedelec"
		}
	}
}
```

## Response codes

```yaml
---
0: OK
1: Notice (see the message to get additionnal informations)
2: Bad request (malformed request)
3: Not allowed (you can't do that, check your method or endpoint)
4: Not found
5: Server error (our bad)
# Numbers 6 to 8 are currently not used
9: Unknown error (good luck)
```
