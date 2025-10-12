<?php

declare(strict_types=1);

namespace Zynqa\FilamentJiraFeedback\Actions;

use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Zynqa\FilamentJiraFeedback\Services\JiraFeedbackService;

class SubmitFeedbackAction
{
    public static function make(): Action
    {
        return Action::make('submitFeedback')
            ->label(config('filament-jira-feedback.banner.button_label', 'Submit Feedback'))
            ->icon(config('filament-jira-feedback.banner.icon', 'heroicon-o-exclamation-triangle'))
            ->color(config('filament-jira-feedback.banner.color', 'warning'))
            ->modalHeading(config('filament-jira-feedback.modal.heading', 'Submit Feedback'))
            ->modalDescription(config('filament-jira-feedback.modal.description', 'Help us improve the application by sharing your feedback.'))
            ->modalSubmitActionLabel(config('filament-jira-feedback.modal.submit_button_label', 'Submit Feedback'))
            ->modalCancelActionLabel(config('filament-jira-feedback.modal.cancel_button_label', 'Cancel'))
            ->modalWidth(config('filament-jira-feedback.modal.width', 'lg'))
            ->form([
                Select::make('issue_type')
                    ->label('Issue Type')
                    ->options(config('filament-jira-feedback.issue.types', []))
                    ->required()
                    ->native(false)
                    ->placeholder('Select issue type'),

                TextInput::make('summary')
                    ->label('Title')
                    ->required()
                    ->maxLength(config('filament-jira-feedback.validation.summary_max_length', 200))
                    ->placeholder('Brief description of the issue or request'),

                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(4)
                    ->maxLength(config('filament-jira-feedback.validation.description_max_length', 2000))
                    ->placeholder('Provide detailed information about your feedback, issue, or request'),
            ])
            ->action(function (array $data, JiraFeedbackService $jiraService): void {
                // Check if feedback is enabled
                if (! config('filament-jira-feedback.enabled')) {
                    Notification::make()
                        ->title('Feedback Disabled')
                        ->body('Feedback submission is currently disabled.')
                        ->danger()
                        ->send();

                    return;
                }

                // Validate Jira configuration
                if (! $jiraService->validateConfiguration()) {
                    Notification::make()
                        ->title('Configuration Error')
                        ->body('Feedback service is not properly configured.')
                        ->danger()
                        ->send();

                    Log::error('Jira feedback configuration is invalid');

                    return;
                }

                // Check authentication requirement
                $user = Auth::user();
                if (config('filament-jira-feedback.require_authentication') && ! $user) {
                    Notification::make()
                        ->title('Authentication Required')
                        ->body('You must be logged in to submit feedback.')
                        ->warning()
                        ->send();

                    return;
                }

                try {
                    // Prepare the issue summary with project key prefix if configured
                    $summary = $data['summary'];
                    $projectKeyField = config('filament-jira-feedback.user_context.project_key_field');

                    if ($user
                        && config('filament-jira-feedback.user_context.include_project_key')
                        && isset($user->{$projectKeyField})
                        && ! empty($user->{$projectKeyField})) {
                        $summary = "[{$user->{$projectKeyField}}] {$summary}";
                    }

                    // Build description with user context
                    $description = self::buildDescription($data['description'], $user);

                    // Prepare issue data
                    $issueData = [
                        'project' => [
                            'key' => $jiraService->getProjectKey(),
                        ],
                        'summary' => $summary,
                        'description' => $description,
                        'issuetype' => [
                            'name' => $data['issue_type'],
                        ],
                        'priority' => [
                            'name' => config('filament-jira-feedback.issue.default_priority', 'Medium'),
                        ],
                    ];

                    // Create the issue in Jira
                    $response = $jiraService->createIssue($issueData);

                    if (isset($response['key'])) {
                        Log::info('Feedback submitted successfully', [
                            'user_id' => $user?->id ?? 'anonymous',
                            'user_email' => $user?->email ?? 'anonymous',
                            'issue_key' => $response['key'],
                            'issue_type' => $data['issue_type'],
                            'summary' => $summary,
                        ]);

                        Notification::make()
                            ->title('Feedback Submitted!')
                            ->body("Your feedback has been submitted successfully. Issue {$response['key']} has been created.")
                            ->success()
                            ->send();

                        return;
                    }

                    throw new Exception('Failed to create Jira issue: Invalid response');
                } catch (Exception $e) {
                    Log::error('Feedback submission failed', [
                        'user_id' => $user?->id,
                        'user_email' => $user?->email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'form_data' => $data,
                    ]);

                    Notification::make()
                        ->title('Submission Failed')
                        ->body('Failed to submit feedback. Please try again later.')
                        ->danger()
                        ->send();
                }
            });
    }

    /**
     * Build the issue description with user context.
     */
    protected static function buildDescription(string $description, mixed $user): string
    {
        if (! $user) {
            return "Anonymous feedback\n\n".$description;
        }

        $userName = $user->name ?? 'Unknown';
        $userEmail = $user->email ?? 'Unknown';

        return "Feedback submitted by: {$userName} ({$userEmail})\n\n".$description;
    }
}
