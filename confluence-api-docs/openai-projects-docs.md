# Projects

# List projects


## Returns a list of projects.

### Query parameters

### Returns

### A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 20.

### limit integer Optional Defaults to 20

### A cursor for use in pagination. after is an object ID that defines your place in the list. For instance, if you

### make a list request and receive 100 objects, ending with obj_foo, your subsequent call can include

### after=obj_foo in order to fetch the next page of the list.

### after string Optional

### If true returns all projects including those that have been archived. Archived projects are not included

### by default.

### include_archived boolean Optional Defaults to false

### A list of Project objects.

### Example request curl

```
curl https://api.openai.com/v1/organization/projects?after=proj_abc&limit=20&include_archived=
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
"id": "proj_abc",
"object": "organization.project",
"name": "Project example",
"created_at": 1711471533 ,
"archived_at": null,
"status": "active"
}
],
"first_id": "proj-abc",
```

```
POST https://api.openai.com/v1/organization/projects
```
## Create a new project in the organization. Projects can be created and archived, but cannot be

## deleted.

### Request body

### Returns

```
"last_id": "proj-xyz",
"has_more": false
}
```
# Create project

### The friendly name of the project, this name appears in reports.

### name string Required

### The created Project object.

### Example request curl

```
curl -X POST https://api.openai.com/v1/organization/projects \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json" \
-d '{
"name": "Project ABC"
}'
```
```
Response
```

```
GET https://api.openai.com/v1/organization/projects/{project_id}
```
## Retrieves a project.

### Path parameters

### Returns

#### {

```
"id": "proj_abc",
"object": "organization.project",
"name": "Project ABC",
"created_at": 1711471533 ,
"archived_at": null,
"status": "active"
}
```
# Retrieve project

### The ID of the project.

### project_id string Required

### The Project object matching the specified ID.

### Example request curl

```
curl https://api.openai.com/v1/organization/projects/proj_abc \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```
```
Response
```
#### {

```
"id": "proj_abc",
"object": "organization.project",
"name": "Project example",
"created_at": 1711471533 ,
"archived_at": null,
"status": "active"
```

```
POST https://api.openai.com/v1/organization/projects/{project_id}
```
## Modifies a project in the organization.

### Path parameters

### Request body

### Returns

```
POST https://api.openai.com/v1/organization/projects/{project_id}/archive
```
#### }

# Modify project

### The ID of the project.

### project_id string Required

### The updated name of the project, this name appears in reports.

### name string Required

### The updated Project object.

### Example request curl

```
curl -X POST https://api.openai.com/v1/organization/projects/proj_abc \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json" \
-d '{
"name": "Project DEF"
}'
```
# Archive project


## Archives a project in the organization. Archived projects cannot be used or updated.

### Path parameters

### Returns

## Represents an individual project.

### The ID of the project.

### project_id string Required

### The archived Project object.

### Example request curl

```
curl -X POST https://api.openai.com/v1/organization/projects/proj_abc/archive \
-H "Authorization: Bearer $OPENAI_ADMIN_KEY" \
-H "Content-Type: application/json"
```
```
Response
```
#### {

```
"id": "proj_abc",
"object": "organization.project",
"name": "Project DEF",
"created_at": 1711471533 ,
"archived_at": 1711471533 ,
"status": "archived"
}
```
# The project object

### The identifier, which can be referenced in API endpoints

### id string

### The object type, which is always organization.project

### object string
