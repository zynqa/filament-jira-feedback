# Repository Guidelines

## Project Structure & Module Organization
This repository is a small Laravel package for Filament. Core PHP code lives in `src/`, split by responsibility: `Actions/` for Filament actions, `Livewire/` and `Widgets/` for UI entry points, `Services/` for Jira integration, and `FilamentJiraFeedbackServiceProvider.php` for package bootstrapping. Configuration is published from `config/filament-jira-feedback.php`. Blade views live in `resources/views/livewire` and `resources/views/widgets`. There is currently no committed `tests/` directory, even though `autoload-dev` reserves `Zynqa\\FilamentJiraFeedback\\Tests\\`.

## Build, Test, and Development Commands
Use Composer for package setup:

- `composer install` installs package dependencies.
- `composer dump-autoload` refreshes PSR-4 autoloading after moving classes.
- `vendor/bin/phpunit` runs the PHPUnit suite once tests and config are present.
- `php artisan vendor:publish --tag=filament-jira-feedback-config` publishes package config in a host Laravel app.
- `php artisan filament:cache-components` refreshes Filament component discovery after UI changes.

For quick syntax checks during development, use `php -l src/Services/JiraFeedbackService.php` or the file you changed.

## Coding Style & Naming Conventions
Follow the existing package style: PHP 8.2+, `declare(strict_types=1);`, PSR-12 formatting, and 4-space indentation. Keep classes under the `Zynqa\\FilamentJiraFeedback\\` namespace with PSR-4 paths that mirror class names. Use descriptive suffixes such as `*Service`, `*Action`, `*Widget`, and `*ServiceProvider`. Blade view names should stay kebab-case, matching the current `feedback-banner` pattern.

## Testing Guidelines
This package declares `phpunit/phpunit` and `orchestra/testbench`, so new tests should use Testbench for package-level integration coverage. Add tests under `tests/` with class names ending in `Test.php`. Prefer coverage for service behavior, configuration defaults, and Filament/Livewire registration paths. If you add tests, include the minimal PHPUnit bootstrap/config needed to run them consistently.

## Commit & Pull Request Guidelines
Recent history uses short, imperative subjects such as `Fix: declare $view as static...` and branch-linked fixes like `Fixes Filament4 incompatibility issues`. Keep commits focused and readable; prefer one logical change per commit. Pull requests should describe the user-visible impact, note any Filament or Laravel compatibility implications, link the related issue, and include screenshots or GIFs when Blade/Livewire output changes.

## Configuration & Security Notes
Never commit real Jira credentials. Use `.env` values for `FILAMENT_JIRA_FEEDBACK_*` settings and document any new configuration keys in both `README.md` and `config/filament-jira-feedback.php`.
