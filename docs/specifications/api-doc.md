# API documentation

_You will find the complete openapi definition here: [API.v1.4.0](API.v1.4.0.yml)._

## Sample response

_Below is the API response when using the /parts route_

```json
{
	"code": 0,
	"message": "Found stock history for 1 part-numbers.",
	"body": {
		"25622076": {
			"code": 0,
			"message": "9 stock records found.",
			"body": [
				{
					"id": "1",
					"part_number": "25622076",
					"date_checked": "2020-12-14",
					"state": "0",
					"parts_in_stock": "17",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Digi-Key Electronics"
				},
				{
					"id": "2",
					"part_number": "25622076",
					"date_checked": "2020-12-14",
					"state": "0",
					"parts_in_stock": "7",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Arndt Automatic GmbH"
				},
				{
					"id": "3",
					"part_number": "25622076",
					"date_checked": "2020-12-14",
					"state": "0",
					"parts_in_stock": "5",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Sentronic AG"
				},
				{
					"id": "1774",
					"part_number": "25622076",
					"date_checked": "2020-12-16",
					"state": "0",
					"parts_in_stock": "7",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Arndt Automatic GmbH"
				},
				{
					"id": "1775",
					"part_number": "25622076",
					"date_checked": "2020-12-16",
					"state": "0",
					"parts_in_stock": "17",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Digi-Key Electronics"
				},
				{
					"id": "1776",
					"part_number": "25622076",
					"date_checked": "2020-12-16",
					"state": "0",
					"parts_in_stock": "5",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Sentronic AG"
				},
				{
					"id": "2888",
					"part_number": "25622076",
					"date_checked": "2020-12-16",
					"state": "0",
					"parts_in_stock": "7",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Arndt Automatic GmbH"
				},
				{
					"id": "2889",
					"part_number": "25622076",
					"date_checked": "2020-12-16",
					"state": "0",
					"parts_in_stock": "17",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Digi-Key Electronics"
				},
				{
					"id": "2890",
					"part_number": "25622076",
					"date_checked": "2020-12-16",
					"state": "0",
					"parts_in_stock": "5",
					"parts_on_order": "-1",
					"min_order": "-1",
					"supplier": "Sentronic AG"
				}
			]
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
