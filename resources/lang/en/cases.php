<?php

return [
    'details' => 'Case Details',
    'case_number' => 'Case Number',
    'desired_outcome' => 'Desired Outcome',
    'status' => [
        'title' => 'Status',
        'created' => 'Created',
        'last_updated' => 'Last Updated',
        'not_assigned' => 'Not Assigned'
    ],
    'quick_actions' => [
        'title' => 'Quick Actions',
        'documents' => [
            'title' => 'Documents',
            'description' => 'Upload and manage case documents',
            'action' => 'Manage Documents'
        ],
        'correspondences' => [
            'title' => 'Correspondences',
            'description' => 'View and manage case communications',
            'action' => 'View Correspondences'
        ],
        'parties' => [
            'title' => 'Parties',
            'description' => 'Manage case parties and contacts',
            'action' => 'Manage Parties'
        ]
    ],
    'actions' => [
        'back_to_dashboard' => 'Back to Dashboard',
        'edit' => 'Edit Case',
        'back_to_case' => 'Back to Case',
        'edit_case_file' => 'Edit Case File',
        'save_changes' => 'Save Changes',
        'cancel' => 'Cancel'
    ],
    'edit' => [
        'title' => 'Edit Case File',
        'success' => 'Case file updated successfully',
        'error' => 'There was an error updating the case file'
    ],
    'collaboration' => [
        'title' => 'Case Collaborators',
        'not_enabled' => 'Collaboration is not enabled for this case.',
        'enable' => 'Enable Collaboration',
        'manage' => 'Manage Collaborators',
        'current' => 'Current Collaborators',
        'invite' => 'Invite Collaborator',
        'no_collaborators' => 'No collaborators yet'
    ]
];
