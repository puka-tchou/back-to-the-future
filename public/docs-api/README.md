## Endpoints

```yaml
---
/:
  description: Prints this documentation.
  howto: Make a request to the endpoint.
  method: GET
add:
  description: Add the part-numbers to the database.
  howto: Post a form input to this endpoint. It must have a field named 'parts' and it should be a CSV file. Make sure that you the 'Content-Type' header to 'multipart/form-data'.
  method: POST
part:
  description: Get the stock history for a given part number.
  howto:
    query parameters:
      id: '[*] - The part-number.'
      source: '[DB, WEB, BOTH] (optional) - Choose the data source.'
  method: GET
parts:
  description: Get the stock history for a given set of parts.
  howto: Post a form input to this endpoint. It must have a field named 'parts' and it should be a CSV file. Make sure that you the 'Content-Type' header to 'multipart/form-data'.
  method: POST
products:
  description: Get all the part-numbers in the database.
  howto:
    query parameters:
      page: '[0-9] (optional) - The page number. This endpoint is paginated and will only return 100 parts. If you need to get the parts 100 to 199, set the page to 1.'
  method: GET
update:
  description: Request a manual update of the stock data for a set of part-numbers.
  howto: Post a form input to this endpoint. It must have a field named 'parts' and it should be a CSV file. Make sure that you the 'Content-Type' header to 'multipart/form-data'.
  method: POST
```

### Response example

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
(...)
9: Unknown error (good luck)
```
