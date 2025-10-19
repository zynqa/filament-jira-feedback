<?php

declare(strict_types=1);

namespace Zynqa\FilamentJiraFeedback\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;
use Zynqa\FilamentJiraFeedback\Actions\SubmitFeedbackAction;

class FeedbackBannerWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;
    protected string $view = 'filament-jira-feedback::widgets.feedback-banner';

    protected static ?int $sort = -100;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return config('filament-jira-feedback.enabled', true)
            && config('filament-jira-feedback.widget.enabled', true);
    }

    public function getColumnSpan(): string | array | int
    {
        return $this->columnSpan;
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
}
