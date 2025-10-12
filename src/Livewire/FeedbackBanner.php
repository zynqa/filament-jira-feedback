<?php

declare(strict_types=1);

namespace Zynqa\FilamentJiraFeedback\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Zynqa\FilamentJiraFeedback\Actions\SubmitFeedbackAction;

class FeedbackBanner extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public static function canView(): bool
    {
        return config('filament-jira-feedback.enabled', true);
    }

    public function submitFeedbackAction(): Action
    {
        return SubmitFeedbackAction::make();
    }

    public function getBannerMessage(): string
    {
        return config('filament-jira-feedback.banner.message', 'This app is in beta testing and may not work as expected. Please help us by reporting any issues or suggesting improvements');
    }

    public function getBannerColor(): string
    {
        return config('filament-jira-feedback.banner.color', 'warning');
    }

    public function getBannerIcon(): string
    {
        return config('filament-jira-feedback.banner.icon', 'heroicon-o-exclamation-triangle');
    }

    public function getColors(): array
    {
        // Check if custom colors are defined
        $customColors = config('filament-jira-feedback.custom_colors', []);

        if ($customColors['background'] || $customColors['background_dark']) {
            // Use custom colors
            return [
                'bg' => trim(($customColors['background'] ?? '') . ' ' . ($customColors['background_dark'] ? 'dark:' . $customColors['background_dark'] : '')),
                'text' => trim(($customColors['text'] ?? '') . ' ' . ($customColors['text_dark'] ? 'dark:' . $customColors['text_dark'] : '')),
                'border' => trim(($customColors['border'] ?? '') . ' ' . ($customColors['border_dark'] ? 'dark:' . $customColors['border_dark'] : '')),
                'icon' => trim(($customColors['icon'] ?? '') . ' ' . ($customColors['icon_dark'] ? 'dark:' . $customColors['icon_dark'] : '')),
            ];
        }

        // Use preset color scheme
        $colorMap = [
            'danger' => [
                'bg' => 'bg-danger-50 dark:bg-danger-950/50',
                'border' => 'border-danger-600 dark:border-danger-500',
                'text' => 'text-danger-800 dark:text-danger-200',
                'icon' => 'text-danger-600 dark:text-danger-400',
            ],
            'warning' => [
                'bg' => 'bg-warning-50 dark:bg-warning-950/50',
                'border' => 'border-warning-600 dark:border-warning-500',
                'text' => 'text-warning-800 dark:text-warning-200',
                'icon' => 'text-warning-600 dark:text-warning-400',
            ],
            'success' => [
                'bg' => 'bg-success-50 dark:bg-success-950/50',
                'border' => 'border-success-600 dark:border-success-500',
                'text' => 'text-success-800 dark:text-success-200',
                'icon' => 'text-success-600 dark:text-success-400',
            ],
            'info' => [
                'bg' => 'bg-info-50 dark:bg-info-950/50',
                'border' => 'border-info-600 dark:border-info-500',
                'text' => 'text-info-800 dark:text-info-200',
                'icon' => 'text-info-600 dark:text-info-400',
            ],
            'primary' => [
                'bg' => 'bg-primary-50 dark:bg-primary-950/50',
                'border' => 'border-primary-600 dark:border-primary-500',
                'text' => 'text-primary-800 dark:text-primary-200',
                'icon' => 'text-primary-600 dark:text-primary-400',
            ],
            'gray' => [
                'bg' => 'bg-gray-50 dark:bg-gray-950/50',
                'border' => 'border-gray-600 dark:border-gray-500',
                'text' => 'text-gray-800 dark:text-gray-200',
                'icon' => 'text-gray-600 dark:text-gray-400',
            ],
        ];

        return $colorMap[$this->getBannerColor()] ?? $colorMap['warning'];
    }

    public function render()
    {
        return view('filament-jira-feedback::livewire.feedback-banner');
    }
}
