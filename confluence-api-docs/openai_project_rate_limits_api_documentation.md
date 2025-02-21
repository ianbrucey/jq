# Project rate limits


## Manage rate limits per model for projects. Rate limits may be configured to be equal to or

## lower than the organization's rate limits.

```
GET https://api.openai.com/v1/organization/projects/{project_id}/rate_limits
```
## Returns the rate limits per model for a project.

### Path parameters

### Query parameters

### Returns

# List project rate limits

### The ID of the project.

### project_id string Required

### A limit on the number of objects to be returned. The default is 100.

### limit integer Optional Defaults to 100

### A cursor for use in pagination. after is an object ID that defines your place in the list. For instance, if you

### make a list request and receive 100 objects, ending with obj_foo, your subsequent call can include

### after=obj_foo in order to fetch the next page of the list.

### after string Optional

### A cursor for use in pagination. before is an object ID that defines your place in the list. For instance, if you

### make a list request and receive 100 objects, beginning with obj_foo, your subsequent call can include

### before=obj_foo in order to fetch the previous page of the list.

### before string Optional

### A list of ProjectRateLimit objects.

### Example request curl

```
curl https://api.openai.com/v1/organization/projects/proj_abc/rate_limits?after=rl_xxx&
```

```
POSThttps://api.openai.com/v1/organization/projects/{project_id}/rate_limits/{rate_l
imit_id}
```
## Updates a project rate limit.

### Path parameters

### Request body

```
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
"object": "project.rate_limit",
"id": "rl-ada",
"model": "ada",
"max_requests_per_1_minute": 600 ,
"max_tokens_per_1_minute": 150000 ,
"max_images_per_1_minute": 10
}
],
"first_id": "rl-ada",
"last_id": "rl-ada",
"has_more": false
}
```
# Modify project rate limit

### The ID of the project.

### project_id string Required

### The ID of the rate limit.

### rate_limit_id string Required


### Returns

### The maximum requests per minute.

### max_requests_per_1_minute integer Optional

### The maximum tokens per minute.

### max_tokens_per_1_minute integer Optional

### The maximum images per minute. Only relevant for certain models.

### max_images_per_1_minute integer Optional

### The maximum audio megabytes per minute. Only relevant for certain models.

### max_audio_megabytes_per_1_minute integer Optional

### The maximum requests per day. Only relevant for certain models.

### max_requests_per_1_day integer Optional

### The maximum batch input tokens per day. Only relevant for certain models.

### batch_1_day_max_input_tokens integer Optional

### The updated ProjectRateLimit object.

### Example request curl

```
curl -X POST https://api.openai.com/v1/organization/projects/proj_abc/rate_limits/rl_xxx \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json" \
-d '{
"max_requests_per_1_minute": 500
}'
```
```
Response
```
#### {

```
"object": "project.rate_limit",
"id": "rl-ada",
"model": "ada",
"max_requests_per_1_minute": 600 ,
"max_tokens_per_1_minute": 150000 ,
"max_images_per_1_minute": 10
```

## Represents a project rate limit config.

#### }

# The project rate limit object

### The object type, which is always project.rate_limit

### object string

### The identifier, which can be referenced in API endpoints.

### id string

### The model this rate limit applies to.

### model string

### The maximum requests per minute.

### max_requests_per_1_minute integer

### The maximum tokens per minute.

### max_tokens_per_1_minute integer

### The maximum images per minute. Only present for relevant models.

### max_images_per_1_minute integer

### The maximum audio megabytes per minute. Only present for relevant models.

### max_audio_megabytes_per_1_minute integer

### The maximum requests per day. Only present for relevant models.

### max_requests_per_1_day integer

### The maximum batch input tokens per day. Only present for relevant models.

### batch_1_day_max_input_tokens integer

```
OBJECT The project rate limit object
```
#### {

```
"object": "project.rate_limit",
"id": "rl_ada",
"model": "ada",
```
