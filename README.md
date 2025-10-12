# Filament Jira Feedback

A FilamentPHP package to collect user feedback and automatically create Jira issues. Perfect for beta testing, feature requests, bug reports, and general user feedback.

## Features

- **Zero Configuration**: Automatically displays feedback banner at the top of all Filament pages
- **Filament Native**: Built with Filament components, forms, modals, and notifications
- **Dark Mode Support**: Automatically adapts to your Filament theme
- **Dynamic Issue Types**: Fetches issue types from your Jira project with filtering and custom descriptions
- **Flexible Positioning**: Position banner anywhere using Filament render hooks
- **User Context**: Includes authenticated user information with feedback
- **Fully Customizable**: Colors, icons, messages, and positioning

## Requirements

- PHP 8.2+
- Laravel (compatible with Laravel 11+)
- FilamentPHP (see `composer.json` for version compatibility)
- Jira Cloud account with API access

## Installation

```bash
composer require zynqa/filament-jira-feedback
```

### Publish Configuration

```bash
php artisan vendor:publish --tag=filament-jira-feedback-config
```

### Configure Environment Variables

Add to your `.env` file:

```env
# Required
FILAMENT_JIRA_FEEDBACK_URL=https://your-domain.atlassian.net
FILAMENT_JIRA_FEEDBACK_EMAIL=your-email@domain.com
FILAMENT_JIRA_FEEDBACK_API_TOKEN=your-jira-api-token
FILAMENT_JIRA_FEEDBACK_PROJECT_KEY=FEEDBACK

# Optional
FILAMENT_JIRA_FEEDBACK_ENABLED=true
FILAMENT_JIRA_FEEDBACK_BANNER_MESSAGE="Help us improve by reporting issues"
FILAMENT_JIRA_FEEDBACK_BUTTON_LABEL="Submit Feedback"
FILAMENT_JIRA_FEEDBACK_COLOR=info
FILAMENT_JIRA_FEEDBACK_ICON=heroicon-o-information-circle
FILAMENT_JIRA_FEEDBACK_REQUIRE_AUTH=false
FILAMENT_JIRA_FEEDBACK_DEFAULT_PRIORITY=Medium
```

### Get Your Jira API Token

1. Log in to your Atlassian account
2. Go to [https://id.atlassian.com/manage-profile/security/api-tokens](https://id.atlassian.com/manage-profile/security/api-tokens)
3. Click "Create API token"
4. Copy the token and add it to your `.env` file

That's it! The feedback banner will automatically appear at the top of your Filament pages.

## Usage

### Basic Usage

Users can click the "Submit Feedback" button to open a modal with a form. The form includes:
- Issue type dropdown (dynamically fetched from Jira)
- Title field
- Description field

Upon submission, a Jira issue is automatically created with user context.

### Using the Action Component

Use the feedback action in any Filament resource, page, or widget:

```php
use Zynqa\FilamentJiraFeedback\Actions\SubmitFeedbackAction;

protected function getHeaderActions(): array
{
    return [
        SubmitFeedbackAction::make(),
    ];
}
```

## Customization

### Banner Position

By default, the banner appears at the top of the page. To customize:

1. Disable auto-registration in `.env`:
```env
FILAMENT_JIRA_FEEDBACK_ENABLED=false
```

2. Register at custom position in your Panel Provider:
```php
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

->renderHook(
    PanelsRenderHook::CONTENT_START, // or any other hook
    fn (): string => Blade::render('@livewire(\'filament-jira-feedback-banner-widget\')')
)
```

**Available hooks:** `BODY_START`, `BODY_END`, `CONTENT_START`, `CONTENT_END`, `TOPBAR_START`, `TOPBAR_END`, `SIDEBAR_NAV_START`, `SIDEBAR_NAV_END`, `FOOTER`

### Colors

Use preset colors:
```env
FILAMENT_JIRA_FEEDBACK_COLOR=info # danger, warning, success, info, primary, secondary, gray
```

Or define custom Tailwind classes for light and dark modes:
```env
# Light Mode
FILAMENT_JIRA_FEEDBACK_BG=bg-blue-50
FILAMENT_JIRA_FEEDBACK_TEXT=text-blue-900
FILAMENT_JIRA_FEEDBACK_BORDER=border-blue-600
FILAMENT_JIRA_FEEDBACK_ICON_COLOR=text-blue-600

# Dark Mode
FILAMENT_JIRA_FEEDBACK_BG_DARK=bg-blue-950/50
FILAMENT_JIRA_FEEDBACK_TEXT_DARK=text-blue-100
FILAMENT_JIRA_FEEDBACK_BORDER_DARK=border-blue-400
FILAMENT_JIRA_FEEDBACK_ICON_COLOR_DARK=text-blue-400
```

### Icons

Use any Heroicon:
```env
FILAMENT_JIRA_FEEDBACK_ICON=heroicon-o-bug-ant
```

### Issue Types

Issue types are automatically fetched from Jira and cached for 1 hour.

**Filter with whitelist:**
```php
// config/filament-jira-feedback.php
'issue' => [
    'allowed_types' => ['Bug', 'Story', 'Task'],
],
```

**Filter with blacklist:**
```php
'issue' => [
    'allowed_types' => null,
    'excluded_types' => ['Epic', 'Sub-task'],
],
```

**Add descriptions:**
```php
'issue' => [
    'type_descriptions' => [
        'Bug' => 'Report a software defect or error',
        'Task' => 'Request a general task or work item',
        'Story' => 'Request a new feature or enhancement',
    ],
],
```

Descriptions display as: `"Bug - Report a software defect or error"`

### Conditional Display

Show banner only to specific users:
```php
->renderHook(
    PanelsRenderHook::BODY_START,
    fn (): string => auth()->user()?->hasRole('beta-tester')
        ? Blade::render('@livewire(\'filament-jira-feedback-banner-widget\')')
        : ''
)
```

### User Context

Include user's project key in issue summary:
```php
'user_context' => [
    'include_project_key' => true,
    'project_key_field' => 'project_key',
],
```

This prefixes summaries with `[PROJECT_KEY]`.

## Troubleshooting

**Banner doesn't appear:**
- Check `FILAMENT_JIRA_FEEDBACK_ENABLED=true` in `.env`
- Clear cache: `php artisan config:clear && php artisan filament:cache-components`

**Feedback submission fails:**
- Verify Jira credentials are correct
- Check that the project key exists
- Ensure your Jira user has create issue permissions
- Check `storage/logs/laravel.log` for detailed errors

**Issue types not working:**
- Issue types are cached for 1 hour - clear cache: `php artisan cache:clear`
- Verify issue types exist in your Jira project
- Issue type names are case-sensitive

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
