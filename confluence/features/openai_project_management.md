I'll create documentation for the OpenAI key configuration approach:

<augment_code_snippet path="confluence/openai_project_management.md" mode="EDIT">
# OpenAI Project Key Management

## Overview

The OpenAI Project Key Management system allows administrators to manage multiple OpenAI API keys and their associated projects. This system enables load balancing across multiple API keys and provides a way to track usage and manage access to OpenAI services.

## Access Control

Access to the OpenAI project management interface is restricted to administrators with the `manage-project-tokens` permission. The interface can be accessed through:

1. The user dropdown menu in the navigation bar
2. Direct URL: `/manage-project-tokens`

## Features

### Project Management
- Create new OpenAI projects with unique API keys
- Edit existing project details
- Delete projects
- Toggle project active/inactive status
- Track storage usage per project

### Project Properties
- **Name**: Identifier for the project
- **API Key**: OpenAI API key (stored securely)
- **Organization ID**: Optional OpenAI organization identifier
- **Status**: Active/Inactive toggle
- **Storage Used**: Tracks storage consumption

## Usage

### Adding a New Project
1. Click "Add New Project" button
2. Fill in required fields:
    - Project Name
    - API Key
    - Organization ID (optional)
3. Submit to create the project

### Editing Projects
1. Click "Edit" on an existing project
2. Modify desired fields
3. Leave API key blank to keep existing key
4. Toggle active status as needed
5. Save changes

### Deleting Projects
1. Click "Delete" on the project row
2. Confirm deletion in the prompt
3. Project and associated key will be removed

## Technical Implementation

### Database Schema
```sql
CREATE TABLE openai_projects (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    api_key VARCHAR(255),
    organization_id VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT true,
    storage_used BIGINT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Key Security
- API keys are encrypted at rest
- Keys are only displayed as password fields
- Existing keys are never displayed in the interface

### Integration
The system integrates with the case management system by:
- Automatically selecting least-used active projects for new cases
- Tracking storage usage across all case documents
- Managing API key rotation and load balancing

### Runtime Configuration

The system uses Laravel's runtime configuration to manage OpenAI credentials. This approach allows for dynamic key switching without service restarts.

#### Configuration Methods

Two primary methods are available in `CaseAssistantService`:

1. `configureDefaultOpenAi()`: Selects and configures the least-used active project
```php
private function configureDefaultOpenAi(): void
{
    $project = OpenAiProject::where('is_active', true)
        ->orderBy('storage_used')
        ->first();

    if (!$project) {
        throw new Exception('No available OpenAI projects');
    }

    config([
        'openai.api_key' => $project->api_key,
        'openai.organization' => $project->organization_id
    ]);
}
```

2. `configureOpenAi(CaseFile $case)`: Configures OpenAI for a specific case
```php
private function configureOpenAi(CaseFile $case): void
{
    // Select or use existing project
    if (!$case->openai_project_id) {
        $project = OpenAiProject::where('is_active', true)
            ->orderBy('storage_used')
            ->first();
        
        $case->update(['openai_project_id' => $project->id]);
    }

    $project = $case->openAiProject;

    config([
        'openai.api_key' => $project->api_key,
        'openai.organization' => $project->organization_id
    ]);
}
```

#### Usage Pattern

1. Before making OpenAI API calls, configure the credentials:
```php
public function someOpenAiOperation(CaseFile $case): void
{
    $this->configureOpenAi($case);
    OpenAI::assistants()->create([/* ... */]);
}
```

2. For operations without a case context:
```php
public function generalOpenAiOperation(): void
{
    $this->configureDefaultOpenAi();
    OpenAI::files()->list();
}
```

#### Benefits
- Dynamic key selection based on usage
- Automatic load balancing
- Per-case key tracking
- No service restarts required
- Thread-safe in queue workers

## Best Practices

1. **Regular Audits**
    - Review active projects periodically
    - Monitor storage usage
    - Verify API key validity

2. **Key Rotation**
    - Rotate API keys periodically
    - Deactivate unused projects
    - Keep backup projects ready for failover

3. **Usage Monitoring**
    - Track storage usage trends
    - Monitor API usage per project
    - Set up alerts for high usage

4. **Configuration Management**
    - Always configure keys before API calls
    - Use case-specific configuration when available
    - Fall back to default configuration for general operations

## Troubleshooting

### Common Issues

1. **API Key Invalid**
    - Verify key in OpenAI dashboard
    - Check for whitespace in key
    - Confirm organization ID if used
    - Ensure configuration was called before API usage

2. **Project Not Available**
    - Check active status
    - Verify storage limits
    - Review API key permissions
    - Confirm project selection logic

3. **Storage Usage Not Updating**
    - Check background jobs
    - Verify OpenAI file status
    - Review system logs

## Future Enhancements

Planned improvements include:
1. Automatic key rotation
2. Usage-based load balancing improvements
3. Enhanced monitoring and alerting
4. Integration with key management services
   </augment_code_snippet>

I've added a new "Runtime Configuration" section that documents:
1. The configuration methods and their purpose
2. Code examples showing proper usage
3. Benefits of this approach
4. Usage patterns for different scenarios
5. Best practices for configuration management

I've also updated the troubleshooting section to include configuration-related issues. Would you like me to make any adjustments to this documentation?
