## Endpoints

```yaml
---
'/api/products':
  method: GET
  description: Get all the products in the database
  parameters: NONE
  body: NONE
'/api/part':
  method: GET
  description: Get the stock of a given part number
  parameters:
    id: The part-number to check
    source:
      '(DB, WEB, BOTH) Get data from the database, the web or both. Defaults
      to BOTH'
  body: NONE
'/api/parts':
  method: POST
  description: Get the stock history for a given set of parts
  parameters: NONE
  body: Form data containing a YAML file
'/api/update':
  method: POST
  description: Request a manual update of the stock data for a set of part
  parameters: NONE
  body: Form data containing a YAML file
'/api/add':
  method: POST
  description: Add parts to the database and start tracking stock
  parameters: NONE
  body: Form data containing a YAML file
```

## Example

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
