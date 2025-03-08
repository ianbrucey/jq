<?php

return [
    'navigation' => [
        'docket' => 'Docket',
        'view_docket' => 'View Docket',
    ],
    'title' => 'Docket',
    'dashboard' => [
        'title' => 'Docket Entries',
    ],
    'search' => [
        'placeholder' => 'Search docket entries...',
    ],
    'filter' => [
        'all_types' => 'All Entry Types',
        'all_statuses' => 'All Statuses',
    ],
    'empty_state' => 'No docket entries found',
    'actions' => [
        'create' => 'Add Entry',
        'edit' => 'Edit Entry',
        'delete' => 'Delete Entry',
        'back_to_case' => 'Back to Case',
    ],
    'entry' => [
        'create_new' => 'Create New Docket Entry',
        'create' => 'Create Entry',
        'fields' => [
            'date' => 'Date',
            'type' => 'Type',
            'title' => 'Title',
            'description' => 'Description',
            'filing_party' => 'Filing Party',
            'judge' => 'Judge',
            'docket_number' => 'Docket Number',
            'status' => 'Status',
        ],
        'types' => [
            'filing' => 'Filing',
            'order' => 'Order',
            'hearing' => 'Hearing',
            'notice' => 'Notice',
            'motion' => 'Motion',
            'judgment' => 'Judgment',
            'other' => 'Other'
        ],
        'status' => [
            'pending' => 'Pending',
            'granted' => 'Granted',
            'denied' => 'Denied',
            'heard' => 'Heard',
            'continued' => 'Continued',
            'withdrawn' => 'Withdrawn'
        ],
        'search_filing_party' => 'Search for filing party...',
        'no_parties_found' => 'No matching parties found',
        'search_judge' => 'Search for judge...',
        'no_judges_found' => 'No matching judges found',
        'upload_documents' => 'Upload Documents',
        'documents' => [
            'title' => 'Related Documents',
            'add' => 'Add Document',
            'remove' => 'Remove Document',
            'none_selected' => 'No documents selected',
            'search' => 'Search documents...',
            'no_results' => 'No documents found'
        ],
        'validation' => [
            'date_required' => 'The entry date is required.',
            'type_required' => 'The entry type is required.',
            'title_required' => 'The entry title is required.',
            'description_required' => 'The description is required.',
            'filing_party_required' => 'The filing party is required.',
            'judge_required' => 'The judge is required.'
        ],
        'actions' => [
            'save' => 'Save Entry',
            'cancel' => 'Cancel',
            'clear_search' => 'Clear Search'
        ]
    ],
    'messages' => [
        'created' => 'Docket entry created successfully.',
        'updated' => 'Docket entry updated successfully.',
        'deleted' => 'Docket entry deleted successfully.',
    ],
    'filters' => [
        'date_range' => 'Date Range',
        'type' => 'Entry Type',
        'status' => 'Status',
        'search' => 'Search docket entries...',
        'apply' => 'Apply Filters',
        'clear' => 'Clear Filters',
    ],
];
