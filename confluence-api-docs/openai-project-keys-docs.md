# Projects API Keys Documentation
# Project API keys

# List project API keys

### The ID of the project.

### project_id string Required

### A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 20.

### limit integer Optional Defaults to 20

### A cursor for use in pagination. after is an object ID that defines your place in the list. For instance, if you

### make a list request and receive 100 objects, ending with obj_foo, your subsequent call can include

### after=obj_foo in order to fetch the next page of the list.

### after string Optional


### Returns

```
GET https://api.openai.com/v1/organization/projects/{project_id}/api_keys/{key_id}
```
### A list of ProjectApiKey objects.

### Example request curl

```
curl https://api.openai.com/v1/organization/projects/proj_abc/api_keys?after=key_abc&
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```
```
Response
```
#### {

```
"object": "list",
"data": [
{
"object": "organization.project.api_key",
"redacted_value": "sk-abc...def",
"name": "My API Key",
"created_at": 1711471533 ,
"id": "key_abc",
"owner": {
"type": "user",
"user": {
"object": "organization.project.user",
"id": "user_abc",
"name": "First Last",
"email": "user@example.com",
"role": "owner",
"added_at": 1711471533
}
}
}
],
"first_id": "key_abc",
"last_id": "key_xyz",
"has_more": false
}
```
# Retrieve project API key


## Retrieves an API key in the project.

### Path parameters

### Returns

### The ID of the project.

### project_id string Required

### The ID of the API key.

### key_id string Required

### The ProjectApiKey object matching the specified ID.

### Example request curl

```
curl https://api.openai.com/v1/organization/projects/proj_abc/api_keys/key_abc \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```
```
Response
```
#### {

```
"object": "organization.project.api_key",
"redacted_value": "sk-abc...def",
"name": "My API Key",
"created_at": 1711471533 ,
"id": "key_abc",
"owner": {
"type": "user",
"user": {
"object": "organization.project.user",
"id": "user_abc",
"name": "First Last",
"email": "user@example.com",
"role": "owner",
"added_at": 1711471533
}
}
}
```

```
DELETE https://api.openai.com/v1/organization/projects/{project_id}/api_keys/{key_id}
```
## Deletes an API key from the project.

### Path parameters

### Returns

## Represents an individual API key in a project.

# Delete project API key

### The ID of the project.

### project_id string Required

### The ID of the API key.

### key_id string Required

### Confirmation of the key's deletion or an error if the key belonged to a service account

### Example request curl

```
curl -X DELETE https://api.openai.com/v1/organization/projects/proj_abc/api_keys/key_abc \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```
```
Response
```
#### {

```
"object": "organization.project.api_key.deleted",
"id": "key_abc",
"deleted": true
}
```
# The project API key object


### The object type, which is always organization.project.api_key

### object string

### The redacted value of the API key

### redacted_value string

### The name of the API key

### name string

### The Unix timestamp (in seconds) of when the API key was created

### created_at integer

### The identifier, which can be referenced in API endpoints

### id string

```
Show properties
```
### owner object

```
OBJECT The project API key object
```
#### {

```
"object": "organization.project.api_key",
"redacted_value": "sk-abc...def",
"name": "My API Key",
"created_at": 1711471533 ,
"id": "key_abc",
"owner": {
"type": "user",
"user": {
"object": "organization.project.user",
"id": "user_abc",
"name": "First Last",
"email": "user@example.com",
"role": "owner",
"created_at": 1711471533
}
}
}
```
