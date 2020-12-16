# OpenAPI documentation

```yaml
openapi: 3.0.2
info:
  version: 1.4.0
  title: Crouzet historical data API
  description: This API is designed to interact with the historical stock data of Crouzet's parts.
paths:
  /:
    get:
      summary: Prints the documentation.
      responses:
        200:
          description: Prints this documentation.
  /add:
    post:
      summary: Add the part-numbers to the database.
      description: Post a form input to this endpoint. It must have a field named 'parts' and it should be a CSV file. Make sure that you set the 'Content-Type' header to 'multipart/form-data'.
      responses:
        200:
          description: Success 🎉
        default:
          description: Unexpected error
      requestBody:
        description: A csv file containing all the parts to add.
        required: true
        content:
          text/csv:
            examples:
              parts:
                summary: A CSV file
                externalValue: https://gitlab.com/gaspacchio/back-to-the-future/-/blob/docs/docs/sample-set.csv
  /part:
    get:
      summary: Get the stock history for a given part number.
      parameters:
        - in: query
          name: id
          required: true
          description: The part-number.
          schema:
            type: string
        - in: query
          name: source
          required: false
          description: >
            Choose the data source:
            * `DB` - Get the data only from the database
            * `WEB` - Get the data from the resellers websites
            * `BOTH` - Get the data from both the database and the resellers websites
          schema:
            type: string
            enum: [DB, WEB, BOTH]
      responses:
        200:
          description: Success 🎉
  /parts:
    post:
      summary: Get the stock history for a given set of parts.
      description: >
        Post a form input to this endpoint.
        It must have a field named `parts` and it should be a CSV file.
        Make sure that you set the `Content-Type` header to `multipart/form-data`.
      requestBody:
        description: A CSV file containing the part numbers to get
        required: true
        content:
          text/csv:
            schema:
              type: object
      responses:
        200:
          description: Success 🎉
  /products:
    get:
      summary: Get all the part-numbers in the database.
      description: This endpoint is paginated and will only return 100 parts. If you need to get the parts 100 to 199, set the `page` parmeter to 1.
      parameters:
        - in: query
          name: page
          required: true
          description: The page number.
          schema:
            type: integer
      responses:
        200:
          description: Success 🎉
  /update:
    post:
      summary: Request a manual update of the stock data for a set of part-numbers.
      description: >
        Post a form input to this endpoint.
        It must have a field named `parts` and it should be a CSV file.
        Make sure that you the `Content-Type` header to `multipart/form-data`.
      requestBody:
        description: A csv file containing all the parts to update.
        required: true
        content:
          text/csv:
            examples:
              parts:
                summary: A CSV file
                externalValue: https://gitlab.com/gaspacchio/back-to-the-future/-/blob/docs/docs/sample-set.csv
      responses:
        200:
          description: Success 🎉
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
