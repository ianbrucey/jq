# Administration

## Admin API Keys

### List admin API keys
Lists organization API keys.

#### Query parameters
- after (string, optional): Cursor for pagination
- order (string, optional): Sort order (asc/desc). Defaults to asc
- limit (integer, optional): Number of results per page. Defaults to 20

#### Example request
```bash
curl https://api.openai.com/v1/organization/admin_api_keys \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```

### Create admin API key
Creates a new organization admin API key.

#### Request body
- name (string, required): Name of the API key

#### Example request
```bash
curl -X POST https://api.openai.com/v1/organization/admin_api_keys \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json" \
-d '{
  "name": "New Admin Key"
}'
```

### Retrieve admin API key
Gets details for a specific admin API key.

#### Path parameters
- key_id (string, required): The ID of the key to retrieve

#### Example request
```bash
curl https://api.openai.com/v1/organization/admin_api_keys/{key_id} \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```

### Delete admin API key
Deletes an organization admin API key.

#### Path parameters
- key_id (string, required): The ID of the key to delete

#### Example request
```bash
curl -X DELETE https://api.openai.com/v1/organization/admin_api_keys/{key_id} \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```
