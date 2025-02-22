# Assistants API deep

# dive

## In-depth guide to creating and managing

## assistants.

##### As described in the Assistants Overview, there are several

##### concepts involved in building an app with the Assistants API.

##### This guide goes deeper into each of these concepts.

##### If you want to get started coding right away, check out the

##### Assistants API Quickstart.

###### We recommend using OpenAI'slatest models with the

###### Assistants API for best results and maximum compatibility

###### with tools.

##### To g e t s t a r t e d , c r e a t i n g a n A s s i s t a n t o n l y r e q u i r e s s p e c i f y i n g

##### the model to use. But you can further customize the

##### behavior of the Assistant:

```
Beta
```
```
Copy page
```
## Creating Assistants

##### Use the instructions parameter to guide the

##### personality of the Assistant and define its goals.

##### Instructions are similar to system messages in the Chat

##### Completions API.

```
1
```
##### Use the tools parameter to give the Assistant access

##### to up to 128 tools. You can give it access to OpenAI-

##### hosted tools like code_interpreter and

##### file_search , or call a third-party tools via a

##### function calling.

```
2
```
##### Use the tool_resources parameter to give the tools

###### Quickstart

```
Start from a template using
the Assistants API with
Next.js.
```
###### Creating assistants

###### Managing threads and

###### messages

###### Runs and run steps

###### Data access guidance

```
3
```

##### For example, to create an Assistant that can create data

##### visualization based on a .csv file, first upload a file.

##### Then, create the Assistant with the code_interpreter tool

##### enabled and provide the file as a resource to the tool.

##### Yo u c a n a t t a c h a m a x i m u m o f 2 0 f i l e s t o

##### code_interpreter and 10,000 files to file_search

##### (using vector_store objects).

##### Each file can be at most 512 MB in size and have a maximum

##### of 5,000,000 tokens. By default, the size of all the files

##### like code_interpreter and file_search access to

##### files. Files are uploaded using the File upload

##### endpoint and must have the purpose set to

##### assistants to be used with this API.

###### curl

```
curl https://api.openai.com/v1/files \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-F purpose="assistants" \
-F file="@revenue-forecast.csv"
```
###### curl

```
curl https://api.openai.com/v1/assistants \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-H "Content-Type: application/json" \
-H "OpenAI-Beta: assistants=v2" \
-d '{
"name": "Data visualizer",
"description": "You are great at creating beautiful data visualiz
"model": "gpt-4o",
"tools": [{"type": "code_interpreter"}],
"tool_resources": {
"code_interpreter": {
"file_ids": ["file-BK7bzQj3FfZFXr7DbL6xJwfo"]
}
}
}'
```

##### uploaded in your project cannot exceed 100 GB, but you can

##### reach out to our support team to increase this limit.

##### Threads and Messages represent a conversation session

##### between an Assistant and a user. There is a limit of 100,

##### Messages per Thread. Once the size of the Messages

##### exceeds the context window of the model, the Thread will

##### attempt to smartly truncate messages, before fully dropping

##### the ones it considers the least important.

##### Yo u c a n c r e a t e a T h r e a d w i t h a n i n i t i a l l i s t o f M e s s a g e s l i ke

##### this:

##### Messages can contain text, images, or file attachment.

##### Message attachments are helper methods that add files to

##### a thread's tool_resources. You can also choose to add

##### files to the thread.tool_resources directly.

## Managing Threads and Messages

###### curl

```
curl https://api.openai.com/v1/threads \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-H "Content-Type: application/json" \
-H "OpenAI-Beta: assistants=v2" \
-d '{
"messages": [
{
"role": "user",
"content": "Create 3 data visualizations based on the trends
"attachments": [
{
"file_id": "file-ACq8OjcLQm2eIG0BvRM4z5qX",
"tools": [{"type": "code_interpreter"}]
}
]
}
]
}'
```

##### Message content can contain either external image URLs or

##### File IDs uploaded via the File API. Only models with Vision

##### support can accept image input. Supported image content

##### types include png, jpg, gif, and webp. When creating image

##### files, pass purpose="vision" to allow you to later

##### download and display the input content. Currently, there is a

##### 100GB limit per project. Please contact us to request a limit

##### increase.

##### To o l s c a n n o t a c c e s s i m a g e c o n t e n t u n l e s s s p e c i f i e d. To p a s s

##### image files to Code Interpreter, add the file ID in the message

##### attachments list to allow the tool to read and analyze the

##### input. Image URLs cannot be downloaded in Code

##### Interpreter today.

### Creating image input content

###### curl

```
# Upload a file with an "vision" purpose
curl https://api.openai.com/v1/files \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-F purpose="vision" \
-F file="@/path/to/myimage.png"
```
```
## Pass the file ID in the content
curl https://api.openai.com/v1/threads \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-H "Content-Type: application/json" \
-H "OpenAI-Beta: assistants=v2" \
-d '{
"messages": [
{
"role": "user",
"content": [
{
"type": "text",
"text": "What is the difference between these images?"
},
{
"type": "image_url",
"image_url": {"url": "https://example.com/image.png"}
},
{
```

##### By controlling the detail parameter, which has three

##### options, low , high , or auto , you have control over how

##### the model processes the image and generates its textual

##### understanding.

```
"type": "image_file",
"image_file": {"file_id": file.id}
}
]
}
]
}'
```
#### Low or high fidelity image understanding

##### low will enable the "low res" mode. The model will

##### receive a low-res 512px x 512px version of the image, and

##### represent the image with a budget of 85 tokens. This

##### allows the API to return faster responses and consume

##### fewer input tokens for use cases that do not require high

##### detail.

##### high will enable "high res" mode, which first allows the

##### model to see the low res image and then creates

##### detailed crops of input images based on the input image

##### size. Use the pricing calculator to see token counts for

##### various image sizes.

###### curl

```
curl https://api.openai.com/v1/threads \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-H "Content-Type: application/json" \
-H "OpenAI-Beta: assistants=v2" \
-d '{
"messages": [
{
"role": "user",
"content": [
{
"type": "text",
"text": "What is this an image of?"
},
```

##### The Assistants API automatically manages the truncation to

##### ensure it stays within the model's maximum context length.

##### Yo u c a n c u s t o m i z e t h i s b e h a v i o r b y s p e c i f y i n g t h e m a x i m u m

##### tokens you'd like a run to utilize and/or the maximum number

##### of recent messages you'd like to include in a run.

##### To c o n t r o l t h e t o k e n u s a g e i n a s i n g l e R u n , s e t

##### max_prompt_tokens and max_completion_tokens when

##### creating the Run. These limits apply to the total number of

##### tokens used in all completions throughout the Run's lifecycle.

##### For example, initiating a Run with max_prompt_tokens set

##### to 500 and max_completion_tokens set to 1000 means the

##### first completion will truncate the thread to 500 tokens and

##### cap the output at 1000 tokens. If only 200 prompt tokens

##### and 300 completion tokens are used in the first completion,

##### the second completion will have available limits of 300

##### prompt tokens and 700 completion tokens.

##### If a completion reaches the max_completion_tokens limit,

##### the Run will terminate with a status of incomplete , and

##### details will be provided in the incomplete_details field of

##### the Run object.

```
{
"type": "image_url",
"image_url": {
"url": "https://example.com/image.png",
"detail": "high"
}
},
]
}
]
}'
```
### Context window management

#### Max Completion and Max Prompt Tokens


###### When using the File Search tool, we recommend setting the

###### max_prompt_tokens to no less than 20,000. For longer

###### conversations or multiple interactions with File Search,

###### consider increasing this limit to 50,000, or ideally, removing

###### the max_prompt_tokens limits altogether to get the highest

###### quality results.

##### Yo u m a y a l s o s p e c i f y a t r u n c a t i o n s t r a t e g y t o c o n t r o l h o w

##### your thread should be rendered into the model's context

##### window. Using a truncation strategy of type auto will use

##### OpenAI's default truncation strategy. Using a truncation

##### strategy of type last_messages will allow you to specify the

##### number of the most recent messages to include in the

##### context window.

##### Messages created by Assistants may contain annotations

##### within the content array of the object. Annotations provide

##### information around how you should annotate the text in the

##### Message.

##### There are two types of Annotations:

##### When annotations are present in the Message object, you'll

##### see illegible model-generated substrings in the text that you

##### should replace with the annotations. These strings may look

##### something like 【13†source】 or

##### sandbox:/mnt/data/file.csv. Here’s an example python

##### code snippet that replaces these strings with the

#### Tr u n c a t i o n S t r a t e g y

### Message annotations

##### file_citation: File citations are created by the

##### file_search tool and define references to a specific

##### file that was uploaded and used by the Assistant to

##### generate the response.

```
1
```
##### file_path : File path annotations are created by the

##### code_interpreter tool and contain references to the

##### files generated by the tool.

```
2
```

##### annotations.

##### When you have all the context you need from your user in the

##### Thread, you can run the Thread with an Assistant of your

##### choice.

###### python

```
# Retrieve the message object
message = client.beta.threads.messages.retrieve(
thread_id="...",
message_id="..."
)
```
```
# Extract the message content
message_content = message.content[ 0 ].text
annotations = message_content.annotations
citations = []
```
```
# Iterate over the annotations and add footnotes
for index, annotation in enumerate(annotations):
# Replace the text with a footnote
message_content.value = message_content.value.replace(annotation.
```
```
# Gather citations based on annotation attributes
if (file_citation := getattr(annotation, 'file_citation'
cited_file = client.files.retrieve(file_citation.file_id)
citations.append(f'[{index}] {file_citation.quote}
elif (file_path := getattr(annotation, 'file_path'
cited_file = client.files.retrieve(file_path.file_id)
citations.append(f'[{index}] Click <here> to download
# Note: File download functionality not implemented above for
```
```
# Add footnotes to the end of the message before displaying to user
message_content.value += '\n' + '\n'.join(citations)
```
## Runs and Run Steps

###### curl

```
curl https://api.openai.com/v1/threads/THREAD_ID/runs \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-H "Content-Type: application/json" \
-H "OpenAI-Beta: assistants=v2" \
```

##### By default, a Run will use the model and tools

##### configuration specified in Assistant object, but you can

##### override most of these when creating the Run for added

##### flexibility:

##### Note: tool_resources associated with the Assistant

##### cannot be overridden during Run creation. You must use the

##### modify Assistant endpoint to do this.

##### Run objects can have multiple statuses.

**STATUS DEFINITION**

###### queued When Runs are first created or when you

###### complete the required_action, they are moved

```
-d '{
"assistant_id": "asst_ToSF7Gb04YMj8AMMm50ZLLtY"
}'
```
###### curl

```
curl https://api.openai.com/v1/threads/THREAD_ID/runs \
-H "Authorization: Bearer $OPENAI_API_KEY" \
-H "Content-Type: application/json" \
-H "OpenAI-Beta: assistants=v2" \
-d '{
"assistant_id": "ASSISTANT_ID",
"model": "gpt-4o",
"instructions": "New instructions that override the Assistant ins
"tools": [{"type": "code_interpreter"}, {"type": "file_search"}]
}'
```
#### Run lifecycle


**STATUS DEFINITION**

###### to a queued status. They should almost

###### immediately move to in_progress.

###### in_progress While in_progress, the Assistant uses the model

###### and tools to perform steps. You can view

###### progress being made by the Run by examining

###### the Run Steps.

###### completed The Run successfully completed! You can now

###### view all Messages the Assistant added to the

###### Thread, and all the steps the Run took. You can

###### also continue the conversation by adding more

###### user Messages to the Thread and creating

###### another Run.

###### requires_action When using the Function calling tool, the Run will

###### move to a required_action state once the

###### model determines the names and arguments of

###### the functions to be called. You must then run

###### those functions and submit the outputs before

###### the run proceeds. If the outputs are not provided

###### before the expires_at timestamp passes

###### (roughly 10 mins past creation), the run will move

###### to an expired status.

###### expired This happens when the function calling outputs

###### were not submitted before expires_at and the

###### run expires. Additionally, if the runs take too long

###### to execute and go beyond the time stated in

###### expires_at, our systems will expire the run.

###### cancelling Yo u c a n a t t e m p t t o c a n c e l a n in_progress run

###### using the Cancel Run endpoint. Once the

###### attempt to cancel succeeds, status of the Run

###### moves to cancelled. Cancellation is attempted

###### but not guaranteed.

###### cancelled Run was successfully cancelled.

###### failed Yo u c a n v i e w t h e r e a s o n f o r t h e f a i l u r e b y l o o k i n g

###### at the last_error object in the Run. The

###### timestamp for the failure will be recorded under

###### failed_at.


**STATUS DEFINITION**

###### incomplete Run ended due to max_prompt_tokens or

###### max_completion_tokens reached. You can view

###### the specific reason by looking at the

###### incomplete_details object in the Run.

##### If you are not using streaming, in order to keep the status of

##### your run up to date, you will have to periodically retrieve the

##### Run object. You can check the status of the run each time

##### you retrieve the object to determine what your application

##### should do next.

##### Yo u c a n o p t i o n a l l y u s e P o l l i n g H e l p e r s i n o u r Node and

##### Python SDKs to help you with this. These helpers will

##### automatically poll the Run object for you and return the Run

##### object when it's in a terminal state.

##### When a Run is in_progress and not in a terminal state, the

##### Thread is locked. This means that:

##### Run step statuses have the same meaning as Run statuses.

##### Most of the interesting detail in the Run Step object lives in

##### the step_details field. There can be two types of step

#### Polling for updates

#### Thread locks

##### New Messages cannot be added to the Thread.

##### New Runs cannot be created on the Thread.

#### Run steps


##### details:

##### Currently, Assistants, Threads, Messages, and Vector Stores

##### created via the API are scoped to the Project they're created

##### in. As such, any person with API key access to that Project is

##### able to read or write Assistants, Threads, Messages, and

##### Runs in the Project.

##### We strongly recommend the following data access controls:

##### message_creation : This Run Step is created when the

##### Assistant creates a Message on the Thread.

```
1
```
##### tool_calls : This Run Step is created when the

##### Assistant calls a tool. Details around this are covered in

##### the relevant sections of the To o l s guide.

```
2
```
## Data Access Guidance

##### Implement authorization. Before performing reads or

##### writes on Assistants, Threads, Messages, and Vector

##### Stores, ensure that the end-user is authorized to do so.

##### For example, store in your database the object IDs that

##### the end-user has access to, and check it before fetching

##### the object ID with the API.

##### Restrict API key access. Carefully consider who in your

##### organization should have API keys and be part of a

##### Project. Periodically audit this list. API keys enable a wide

##### range of operations including reading and modifying

##### sensitive information, such as Messages and Files.

##### Create separate accounts. Consider creating separate

##### Projects for different applications in order to isolate data

##### across multiple applications.
