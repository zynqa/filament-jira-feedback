<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Feedback Banner
    |--------------------------------------------------------------------------
    |
    | This option allows you to enable or disable the feedback banner widget
    | throughout your Filament panels. Set to false to hide the banner.
    |
    */
    'enabled' => env('FILAMENT_JIRA_FEEDBACK_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Jira Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Jira instance credentials and settings here.
    | You can obtain an API token from your Atlassian account settings.
    |
    */
    'jira' => [
        'url' => env('FILAMENT_JIRA_FEEDBACK_URL', 'https://your-domain.atlassian.net'),
        'email' => env('FILAMENT_JIRA_FEEDBACK_EMAIL'),
        'api_token' => env('FILAMENT_JIRA_FEEDBACK_API_TOKEN'),
        'project_key' => env('FILAMENT_JIRA_FEEDBACK_PROJECT_KEY', 'FEEDBACK'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Issue Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the default settings for issues created from feedback.
    |
    */
    'issue' => [
        'types' => [
            'Bug' => 'Bug - Report a software defect',
            'Task' => 'Task - Request a general task',
            'Story' => 'Story - Request a new feature or enhancement',
            'Ask a question' => 'Ask a question - Get help or ask for information',
        ],
        'default_priority' => env('FILAMENT_JIRA_FEEDBACK_DEFAULT_PRIORITY', 'Medium'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Banner Configuration
    |--------------------------------------------------------------------------
    |
    | Customize the appearance and message of the feedback banner widget.
    |
    */
    'banner' => [
        'message' => env(
            'FILAMENT_JIRA_FEEDBACK_BANNER_MESSAGE',
            'This app is in beta testing and may not work as expected. Please help us by reporting any issues or suggesting improvements'
        ),
        'button_label' => env('FILAMENT_JIRA_FEEDBACK_BUTTON_LABEL', 'Submit Feedback'),
        'color' => env('FILAMENT_JIRA_FEEDBACK_COLOR', 'info'), // danger, warning, success, info, primary, secondary, gray
        'icon' => env('FILAMENT_JIRA_FEEDBACK_ICON', 'heroicon-o-information-circle'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Color Configuration
    |--------------------------------------------------------------------------
    |
    | Override the default color scheme with custom Tailwind CSS classes.
    | Leave null to use the preset color scheme defined above.
    | Use full Tailwind classes like 'bg-blue-50', 'text-blue-800', etc.
    |
    */
    'custom_colors' => [
        // Light mode colors
        'background' => env('FILAMENT_JIRA_FEEDBACK_BG'),
        'text' => env('FILAMENT_JIRA_FEEDBACK_TEXT'),
        'border' => env('FILAMENT_JIRA_FEEDBACK_BORDER'),
        'icon' => env('FILAMENT_JIRA_FEEDBACK_ICON_COLOR'),

        // Dark mode colors
        'background_dark' => env('FILAMENT_JIRA_FEEDBACK_BG_DARK'),
        'text_dark' => env('FILAMENT_JIRA_FEEDBACK_TEXT_DARK'),
        'border_dark' => env('FILAMENT_JIRA_FEEDBACK_BORDER_DARK'),
        'icon_dark' => env('FILAMENT_JIRA_FEEDBACK_ICON_COLOR_DARK'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the feedback submission modal.
    |
    */
    'modal' => [
        'heading' => 'Submit Feedback',
        'description' => 'Help us improve the application by sharing your feedback.',
        'submit_button_label' => 'Submit Feedback',
        'cancel_button_label' => 'Cancel',
        'width' => 'lg', // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, screen
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Configure whether authentication is required to submit feedback.
    | When set to false, anonymous feedback will be allowed.
    |
    */
    'require_authentication' => env('FILAMENT_JIRA_FEEDBACK_REQUIRE_AUTH', false),

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Configure validation rules for feedback submissions.
    |
    */
    'validation' => [
        'summary_max_length' => 200,
        'description_max_length' => 2000,
    ],

    /*
    |--------------------------------------------------------------------------
    | User Context
    |--------------------------------------------------------------------------
    |
    | Configure how user information is attached to feedback submissions.
    | Set 'include_project_key' to true if your users have a project_key field.
    |
    */
    'user_context' => [
        'include_project_key' => false,
        'project_key_field' => 'project_key',
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where and how the feedback widget appears.
    |
    */
    'widget' => [
        'enabled' => true,
        'sort' => -100, // Display at the top by default
    ],
];
