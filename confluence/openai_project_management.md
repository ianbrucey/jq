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

## Troubleshooting

### Common Issues

1. **API Key Invalid**
   - Verify key in OpenAI dashboard
   - Check for whitespace in key
   - Confirm organization ID if used

2. **Project Not Available**
   - Check active status
   - Verify storage limits
   - Review API key permissions

3. **Storage Usage Not Updating**
   - Check background jobs
   - Verify OpenAI file status
   - Review system logs

## Future Enhancements

Planned improvements include:
- Detailed usage analytics
- Automatic key rotation
- Usage quota management
- Cost tracking and reporting
- Automated health checks