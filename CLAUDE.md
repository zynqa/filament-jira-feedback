# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What This Is

A FilamentPHP package (`zynqa/filament-jira-feedback`) that adds a feedback banner to Filament panels. Users click "Submit Feedback" to open a modal form that creates a Jira issue automatically. It supports Filament v3 and v4.

## Development Context

This is a **Composer package** installed in the host project at `vendor/zynqa/filament-jira-feedback`. It has no standalone test suite, build step, or CLI commands of its own. All testing happens through the consuming Laravel application.

### Linting / Static Analysis

Run from the **host project root** (not this package directory):
```bash
wcomposer run lint          # Laravel Pint
wcomposer run static-analysis  # PHPStan
```

### Running Tests

No tests directory exists in the package yet. The `composer.json` defines a `tests/` autoload-dev namespace with Orchestra Testbench and PHPUnit as dev dependencies, but no tests have been written.

## Architecture

### Entry Point

`FilamentJiraFeedbackServiceProvider` registers everything:
- Merges config from `config/filament-jira-feedback.php`
- Registers `JiraFeedbackService` as a singleton
- Registers two Livewire components: `filament-jira-feedback-banner` and `filament-jira-feedback-banner-widget`
- When enabled, auto-registers a render hook at `BODY_START` to inject the banner on all Filament pages

### Key Components

| Component | Role |
|---|---|
| `Services\JiraFeedbackService` | Guzzle-based client for Jira REST API v3. Creates issues, fetches/caches project issue types (1hr cache), converts plain text to ADF. |
| `Actions\SubmitFeedbackAction` | Static `make()` factory returning a Filament `Action` with modal form (issue type, summary, description). Handles issue creation, user context, filtering, and notifications. |
| `Livewire\FeedbackBanner` | Livewire component rendering the banner with color theming (preset or custom Tailwind classes). Delegates submission to `SubmitFeedbackAction`. |
| `Widgets\FeedbackBannerWidget` | Filament Widget wrapper around the banner, used for render hook injection and dashboard embedding. |

### Data Flow

1. User clicks "Submit Feedback" on the banner
2. `SubmitFeedbackAction` opens a Filament modal form
3. Issue types are fetched from Jira via `JiraFeedbackService::getProjectIssueTypes()` (cached 1hr, key: `jira_issue_types_{project_key}`)
4. On submit, description is converted to ADF format, user context is appended, and `JiraFeedbackService::createIssue()` POSTs to Jira REST API v3
5. Success/failure shown via Filament Notifications

### Configuration

All config lives in `config/filament-jira-feedback.php` with env var overrides. Key sections:
- `jira.*` - Jira credentials and project key
- `issue.*` - Issue type filtering (whitelist/blacklist), descriptions, default priority
- `banner.*` - Message, button label, color preset, icon
- `custom_colors.*` - Per-class Tailwind overrides for light/dark mode
- `modal.*` - Modal heading, description, width
- `user_context.*` - Optional project key prefix on issue summaries

### Blade Views

- `livewire/feedback-banner.blade.php` - Full banner with color theming
- `livewire/feedback-banner-hook.blade.php` - Render hook wrapper
- `widgets/feedback-banner.blade.php` - Widget view

## Important Patterns

- `SubmitFeedbackAction` is **not** a Filament Action subclass; it's a plain class with a static `make()` factory that returns `Filament\Actions\Action`.
- Issue type filtering supports both whitelist (`allowed_types`) and blacklist (`excluded_types`); whitelist takes precedence.
- The banner supports two color modes: preset names (danger/warning/success/info/primary/gray) mapped to Filament color tokens, or fully custom Tailwind classes via env vars.
- Consumers can disable auto-registration (`FILAMENT_JIRA_FEEDBACK_ENABLED=false`) and manually register the banner at any Filament render hook position.
