I'll create a comprehensive plan for the Docket Tracker feature.

<augment_code_snippet path="confluence/features/docket_tracker.md" mode="EDIT">
# Docket Tracker Feature

## Overview
The Docket Tracker allows users to maintain a chronological record of court proceedings, filings, and orders. It integrates with the existing case management system while maintaining separation from the correspondence tracking system.

## Database Schema

### New Tables

```sql
CREATE TABLE docket_entries (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    case_file_id BIGINT NOT NULL,
    entry_date DATE NOT NULL,
    entry_type ENUM('filing', 'order', 'hearing', 'notice', 'motion', 'judgment', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    filing_party VARCHAR(255),
    judge VARCHAR(255),
    docket_number VARCHAR(255),
    status ENUM('pending', 'granted', 'denied', 'heard', 'continued', 'withdrawn') NULL,
    is_sealed BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (case_file_id) REFERENCES case_files(id) ON DELETE CASCADE
);

CREATE TABLE docket_documents (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    docket_entry_id BIGINT NOT NULL,
    document_id BIGINT NOT NULL,
    is_primary BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (docket_entry_id) REFERENCES docket_entries(id) ON DELETE CASCADE,
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    UNIQUE KEY unique_primary_doc (docket_entry_id, is_primary)
);

CREATE TABLE docket_deadlines (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    docket_entry_id BIGINT NOT NULL,
    deadline_date DATE NOT NULL,
    deadline_type VARCHAR(255) NOT NULL,
    description TEXT,
    reminder_id BIGINT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (docket_entry_id) REFERENCES docket_entries(id) ON DELETE CASCADE,
    FOREIGN KEY (reminder_id) REFERENCES reminders(id) ON DELETE SET NULL
);
```

## Implementation Components

### 1. Models

```php
// DocketEntry.php
class DocketEntry extends Model
{
    protected $fillable = [
        'case_file_id',
        'entry_date',
        'entry_type',
        'title',
        'description',
        'filing_party',
        'judge',
        'docket_number',
        'status',
        'is_sealed'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'is_sealed' => 'boolean'
    ];

    // Relationships
    public function caseFile() { return $this->belongsTo(CaseFile::class); }
    public function documents() { return $this->belongsToMany(Document::class, 'docket_documents')->withPivot('is_primary'); }
    public function deadlines() { return $this->hasMany(DocketDeadline::class); }
}
```

### 2. Livewire Components

#### Primary Components
- `DocketDashboard` - Main docket view
- `CreateDocketEntry` - Form for new entries
- `DocketEntryDetails` - Detailed view of entry
- `DocketCalendar` - Calendar view of deadlines

#### Supporting Components
- `DocketDocumentSelector` - Document attachment interface
- `DeadlineCreator` - Deadline creation with reminder integration
- `DocketFilter` - Entry filtering and search

### 3. Views Structure
```
resources/views/livewire/docket/
├── dashboard.blade.php
├── create-entry.blade.php
├── entry-details.blade.php
├── calendar.blade.php
└── components/
    ├── entry-card.blade.php
    ├── deadline-form.blade.php
    └── document-selector.blade.php
```

## User Interface

### Main Dashboard
- Chronological list of docket entries
- Filtering by entry type, date range, and status
- Quick actions for common entry types
- Calendar integration for deadlines

### Entry Creation Flow
1. Select entry type
2. Enter basic information (date, title, description)
3. Attach relevant documents
4. Set related deadlines (optional)
5. Create associated reminders (optional)

### Calendar Integration
- Sync with case reminders
- Display upcoming deadlines
- Filter by entry type
- Export capabilities

## Security & Access Control

### Permissions
- View docket entries
- Create new entries
- Edit existing entries
- Delete entries (with audit trail)
- Access sealed entries

### Validation Rules
- Required fields by entry type
- Date validation (no future dates for past events)
- Document attachment limits
- Deadline constraints

## Integration Points

### Document System
- Link to existing documents
- Create new documents from docket entries
- Track document versions

### Reminder System
- Create reminders from deadlines
- Link existing reminders
- Automatic reminder creation for certain entry types

### Case Management
- Display relevant docket entries in case overview
- Link to related correspondence
- Include in case exports

## Implementation Phases

### Phase 1: Core Features
1. Basic docket entry creation
2. Document attachment
3. Chronological view
4. Simple filtering

### Phase 2: Enhanced Features
1. Calendar integration
2. Deadline management
3. Advanced filtering
4. Export capabilities

### Phase 3: Integration Features
1. Automated reminder creation
2. Document version tracking
3. API endpoints for external integration
4. Bulk import capabilities

## Testing Strategy

### Unit Tests
- Model relationships
- Validation rules
- Permission checks

### Feature Tests
- Entry creation flow
- Document attachment
- Deadline creation
- Calendar integration

### Integration Tests
- Reminder system integration
- Document system integration
- Export functionality

## Migration Plan

```php
// Migration command:
// php artisan make:migration create_docket_tables --create=docket_entries

// Additional migrations needed:
// php artisan make:migration create_docket_documents_table --create=docket_documents
// php artisan make:migration create_docket_deadlines_table --create=docket_deadlines
```

## Translation Keys Structure

```php
return [
    'docket' => [
        'entry' => [
            'types' => [
                'filing' => 'Filing',
                'order' => 'Order',
                'hearing' => 'Hearing',
                // ...
            ],
            'status' => [
                'pending' => 'Pending',
                'granted' => 'Granted',
                // ...
            ],
            // ...
        ],
        'deadlines' => [
            'types' => [
                'response_due' => 'Response Due',
                'hearing_date' => 'Hearing Date',
                // ...
            ],
            // ...
        ],
    ],
];
```
</augment_code_snippet>

This plan provides a comprehensive structure for implementing the Docket Tracker feature while following the project's established patterns and requirements. Would you like me to elaborate on any specific aspect of the plan?
