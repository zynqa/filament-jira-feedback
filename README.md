# Filament Jira Feedback

A **FilamentPHP** package to collect user feedback and automatically create Jira issues. Perfect for beta testing, feature requests, bug reports, and general user feedback directly from your Filament admin panel.

## Features

- **Filament-Native**: Built specifically for FilamentPHP v3 using Filament components
- **Widget-Based**: Display a dismissible feedback banner across your Filament panels
- **Filament Actions**: Uses Filament's modal and form system for a seamless experience
- **Filament Notifications**: Beautiful success/error notifications that match your theme
- **Dark Mode Support**: Automatically adapts to Filament's dark mode
- **Jira Integration**: Automatically creates Jira issues from user feedback
- **Fully Configurable**: Customize colors, icons, messages, and issue types
- **User Context**: Automatically includes authenticated user information
- **Flexible Authentication**: Support for both authenticated and anonymous feedback

## Requirements

- PHP 8.2 or higher
- Laravel 11.0 or higher
- FilamentPHP v3.0 or higher
- A Jira Cloud account with API access

## Installation

### 1. Install the package via Composer

```bash
composer require zynqa/filament-jira-feedback
```

### 2. Publish the configuration file

```bash
php artisan vendor:publish --tag=filament-jira-feedback-config
```

This will create a `config/filament-jira-feedback.php` file.

### 3. Configure your environment variables

Add the following to your `.env` file:

```env
# Enable or disable the feedback widget
FILAMENT_JIRA_FEEDBACK_ENABLED=true

# Jira Configuration
FILAMENT_JIRA_FEEDBACK_URL=https://your-domain.atlassian.net
FILAMENT_JIRA_FEEDBACK_EMAIL=your-email@domain.com
FILAMENT_JIRA_FEEDBACK_API_TOKEN=your-jira-api-token
FILAMENT_JIRA_FEEDBACK_PROJECT_KEY=FEEDBACK

# Optional: Customize the banner
FILAMENT_JIRA_FEEDBACK_BANNER_MESSAGE="This app is in beta testing. Please report any issues"
FILAMENT_JIRA_FEEDBACK_BUTTON_LABEL="Submit Feedback"
FILAMENT_JIRA_FEEDBACK_COLOR=warning
FILAMENT_JIRA_FEEDBACK_ICON=heroicon-o-exclamation-triangle

# Optional: Require authentication
FILAMENT_JIRA_FEEDBACK_REQUIRE_AUTH=false

# Optional: Default priority for created issues
FILAMENT_JIRA_FEEDBACK_DEFAULT_PRIORITY=Medium
```

### 4. Get your Jira API Token

1. Log in to your Atlassian account
2. Go to [https://id.atlassian.com/manage-profile/security/api-tokens](https://id.atlassian.com/manage-profile/security/api-tokens)
3. Click "Create API token"
4. Give it a name and copy the token
5. Add it to your `.env` file as `FILAMENT_JIRA_FEEDBACK_API_TOKEN`

### 5. Register the Widget

Add the widget to your Filament Panel Provider (usually `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Zynqa\FilamentJiraFeedback\Widgets\FeedbackBannerWidget;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->widgets([
            FeedbackBannerWidget::class,
        ]);
}
```

That's it! The feedback banner will now appear at the top of your Filament dashboard.

## Usage

### Basic Usage

Once installed and configured, the feedback banner widget will automatically appear on your Filament dashboard. Users can click the "Submit Feedback" button to open a modal with a form.

### Using the Action Anywhere

You can use the feedback action in any Filament resource, page, or custom widget:

```php
use Zynqa\FilamentJiraFeedback\Actions\SubmitFeedbackAction;

// In a Filament Resource
protected function getHeaderActions(): array
{
    return [
        SubmitFeedbackAction::make(),
    ];
}

// In a custom widget
public function getActions(): array
{
    return [
        SubmitFeedbackAction::make(),
    ];
}
```

### Customizing the Widget Placement

Control where the widget appears by configuring it in your Panel Provider:

```php
->widgets([
    FeedbackBannerWidget::class,
])
->widgetsSidebar([
    // Or place it in the sidebar
    FeedbackBannerWidget::class,
])
```

### Customizing Colors

#### Using Preset Color Schemes

Filament supports these preset color options:
- `danger` - Red
- `warning` - Amber (default)
- `success` - Green
- `info` - Blue
- `primary` - Your theme's primary color
- `secondary` - Your theme's secondary color
- `gray` - Gray

Set in config or `.env`:

```env
FILAMENT_JIRA_FEEDBACK_COLOR=info
```

#### Using Custom Colors (Advanced)

For complete control over the banner appearance, you can define custom Tailwind CSS classes for both light and dark modes. This overrides the preset color scheme.

Add to your `.env`:

```env
# Light Mode Colors
FILAMENT_JIRA_FEEDBACK_BG=bg-blue-50
FILAMENT_JIRA_FEEDBACK_TEXT=text-blue-900
FILAMENT_JIRA_FEEDBACK_BORDER=border-blue-600
FILAMENT_JIRA_FEEDBACK_ICON_COLOR=text-blue-600

# Dark Mode Colors
FILAMENT_JIRA_FEEDBACK_BG_DARK=bg-blue-950/50
FILAMENT_JIRA_FEEDBACK_TEXT_DARK=text-blue-100
FILAMENT_JIRA_FEEDBACK_BORDER_DARK=border-blue-400
FILAMENT_JIRA_FEEDBACK_ICON_COLOR_DARK=text-blue-400
```

**Available Options:**
- `FILAMENT_JIRA_FEEDBACK_BG` - Background color (light mode)
- `FILAMENT_JIRA_FEEDBACK_TEXT` - Text color (light mode)
- `FILAMENT_JIRA_FEEDBACK_BORDER` - Left border color (light mode)
- `FILAMENT_JIRA_FEEDBACK_ICON_COLOR` - Icon color (light mode)
- `FILAMENT_JIRA_FEEDBACK_BG_DARK` - Background color (dark mode)
- `FILAMENT_JIRA_FEEDBACK_TEXT_DARK` - Text color (dark mode)
- `FILAMENT_JIRA_FEEDBACK_BORDER_DARK` - Left border color (dark mode)
- `FILAMENT_JIRA_FEEDBACK_ICON_COLOR_DARK` - Icon color (dark mode)

**Tips for Custom Colors:**
- Use lighter backgrounds (`*-50`, `*-100`) in light mode for readability
- Use darker backgrounds (`*-950/50`, `*-900/50`) in dark mode
- Ensure sufficient contrast between text and background
- Use `/50` opacity for dark mode backgrounds for a subtle effect
- Test in both light and dark modes to ensure good visibility

**Example Color Combinations:**

Emerald Green:
```env
FILAMENT_JIRA_FEEDBACK_BG=bg-emerald-50
FILAMENT_JIRA_FEEDBACK_TEXT=text-emerald-900
FILAMENT_JIRA_FEEDBACK_BORDER=border-emerald-600
FILAMENT_JIRA_FEEDBACK_ICON_COLOR=text-emerald-600
FILAMENT_JIRA_FEEDBACK_BG_DARK=bg-emerald-950/50
FILAMENT_JIRA_FEEDBACK_TEXT_DARK=text-emerald-100
FILAMENT_JIRA_FEEDBACK_BORDER_DARK=border-emerald-400
FILAMENT_JIRA_FEEDBACK_ICON_COLOR_DARK=text-emerald-400
```

Purple:
```env
FILAMENT_JIRA_FEEDBACK_BG=bg-purple-50
FILAMENT_JIRA_FEEDBACK_TEXT=text-purple-900
FILAMENT_JIRA_FEEDBACK_BORDER=border-purple-600
FILAMENT_JIRA_FEEDBACK_ICON_COLOR=text-purple-600
FILAMENT_JIRA_FEEDBACK_BG_DARK=bg-purple-950/50
FILAMENT_JIRA_FEEDBACK_TEXT_DARK=text-purple-100
FILAMENT_JIRA_FEEDBACK_BORDER_DARK=border-purple-400
FILAMENT_JIRA_FEEDBACK_ICON_COLOR_DARK=text-purple-400
```

### Customizing Icons

Use any Heroicon:

```env
FILAMENT_JIRA_FEEDBACK_ICON=heroicon-o-bug-ant
```

Common options:
- `heroicon-o-exclamation-triangle` (default)
- `heroicon-o-bug-ant`
- `heroicon-o-chat-bubble-left-right`
- `heroicon-o-light-bulb`
- `heroicon-o-megaphone`

### Issue Types

The package automatically fetches all available issue types from your Jira project. This ensures that the feedback form always shows the correct issue types configured in your Jira instance.

#### Filtering Issue Types

You can control which issue types appear in the feedback form using two approaches:

**1. Whitelist (Allowed Types)**

Show only specific issue types by setting `allowed_types`:

```php
'issue' => [
    'allowed_types' => ['Bug', 'Story', 'Task'], // Only show these types
    'excluded_types' => [], // Ignored when allowed_types is set
    // ...
],
```

**2. Blacklist (Excluded Types)**

Hide specific issue types while showing all others:

```php
'issue' => [
    'allowed_types' => null, // Must be null for exclusions to work
    'excluded_types' => ['Epic', 'Sub-task'], // Hide these types
    // ...
],
```

**Note:** If `allowed_types` is set, `excluded_types` is ignored. Set `allowed_types` to `null` to use exclusions instead.

#### Adding Custom Descriptions

You can optionally add helpful descriptions to your issue types to guide users in selecting the right type. These descriptions appear in the dropdown as `"Issue Type - Description"`.

Edit `config/filament-jira-feedback.php`:

```php
'issue' => [
    // Filtering (optional)
    'allowed_types' => null,
    'excluded_types' => [],

    // Custom descriptions for issue types (optional)
    // The keys should match the issue type names from your Jira project
    'type_descriptions' => [
        'Bug' => 'Report a software defect or error',
        'Task' => 'Request a general task or work item',
        'Story' => 'Request a new feature or enhancement',
        'Epic' => 'Define a large body of work or initiative',
        'Service Request' => 'Request IT service or support',
        'Ask a question' => 'Get help, information, or clarification',
    ],
    'default_priority' => 'Medium',
],
```

**How it works:**
- Issue types are fetched dynamically from Jira and cached for 1 hour
- Filtering is applied before displaying the types
- If a description is configured for an issue type, it displays as: `"Bug - Report a software defect or error"`
- If no description is configured, the issue type displays as just: `"Bug"`
- All filtered issue types will appear, regardless of whether they have descriptions

**Note:** Issue type names are case-sensitive and must exactly match the names in your Jira project.

### User Context

If your users have a `project_key` field (or any custom field), include it in the issue summary:

```php
// config/filament-jira-feedback.php
'user_context' => [
    'include_project_key' => true,
    'project_key_field' => 'project_key',
],
```

This will prefix the issue summary with `[PROJECT_KEY]` for authenticated users.

### Conditional Display

Show the widget only to specific users:

```php
use Zynqa\FilamentJiraFeedback\Widgets\FeedbackBannerWidget;

class CustomFeedbackWidget extends FeedbackBannerWidget
{
    public static function canView(): bool
    {
        return parent::canView() && auth()->user()->isBetaTester();
    }
}
```

Then register your custom widget instead:

```php
->widgets([
    CustomFeedbackWidget::class,
])
```

### Publishing Views

To customize the widget appearance:

```bash
php artisan vendor:publish --tag=filament-jira-feedback-views
```

Views will be copied to `resources/views/vendor/filament-jira-feedback/`.

## Configuration Reference

### Banner Configuration

```php
'banner' => [
    'message' => 'Your custom message',
    'button_label' => 'Submit Feedback',
    'color' => 'warning', // danger, warning, success, info, primary, secondary, gray
    'icon' => 'heroicon-o-exclamation-triangle',
],
```

### Modal Configuration

```php
'modal' => [
    'heading' => 'Submit Feedback',
    'description' => 'Help us improve the application.',
    'submit_button_label' => 'Submit Feedback',
    'cancel_button_label' => 'Cancel',
    'width' => 'lg', // xs, sm, md, lg, xl, 2xl, etc.
],
```

### Widget Configuration

```php
'widget' => [
    'enabled' => true,
    'sort' => -100, // Display at the top
],
```

### Validation Rules

```php
'validation' => [
    'summary_max_length' => 200,
    'description_max_length' => 2000,
],
```

## How It Works

1. User clicks the "Submit Feedback" button in the banner
2. A Filament modal opens with a form (using Filament Form Builder)
3. User fills in:
   - Issue type (Bug, Task, Story, etc.)
   - Title (summary)
   - Description
4. On submission:
   - Input is validated
   - Description is converted to ADF format
   - A Jira issue is created via the Jira REST API
   - User context is included (if authenticated)
   - A Filament notification confirms success or shows errors
5. The created Jira issue key is displayed to the user

## Differences from Standard Laravel Package

If you're familiar with the standard Laravel version, here are the key differences:

| Feature | Standard Laravel | Filament Version |
|---------|-----------------|------------------|
| UI Framework | Alpine.js + Tailwind | Filament Components |
| Forms | HTML Forms | Filament Form Builder |
| Modals | Custom Alpine.js | Filament Actions & Modals |
| Notifications | JavaScript alerts | Filament Notifications |
| Styling | Manual Tailwind | Filament Theme System |
| Dark Mode | Not supported | Automatic support |
| Integration | Blade component | Filament Widget |

## Troubleshooting

### Widget doesn't appear

- Check that `FILAMENT_JIRA_FEEDBACK_ENABLED=true` in `.env`
- Ensure the widget is registered in your Panel Provider
- Clear cache: `php artisan filament:cache-components`

### Feedback submission fails

- Verify Jira credentials are correct
- Check that the Jira project key exists
- Ensure your Jira user has permission to create issues
- Check `storage/logs/laravel.log` for detailed errors

### Issue types not working

- Ensure the issue types exist in your Jira project
- Check your Jira project settings for available issue types
- Clear Laravel config cache if descriptions aren't showing: `php artisan config:clear`
- Issue types are cached for 1 hour - wait or clear application cache: `php artisan cache:clear`

### Styling looks off

- Clear Filament cache: `php artisan filament:cache-components`
- Ensure you're using FilamentPHP v3

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT licence](LICENSE.md).

