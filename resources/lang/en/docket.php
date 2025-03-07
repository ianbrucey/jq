<?php

return [
    'title' => 'Docket',
    'actions' => [
        'create' => 'Add Entry',
        'edit' => 'Edit Entry',
        'delete' => 'Delete Entry',
        'back_to_case' => 'Back to Case',
    ],
    'entry' => [
        'create_new' => 'Create New Docket Entry',
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
        'create' => 'Create Entry',
        'edit' => 'Edit Entry',
        'delete' => 'Delete Entry',
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
