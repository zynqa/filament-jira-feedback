<?php

declare(strict_types=1);

namespace Zynqa\FilamentJiraFeedback;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Zynqa\FilamentJiraFeedback\Livewire\FeedbackBanner;
use Zynqa\FilamentJiraFeedback\Services\JiraFeedbackService;
use Zynqa\FilamentJiraFeedback\Widgets\FeedbackBannerWidget;

class FilamentJiraFeedbackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge package configuration with application configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-jira-feedback.php',
            'filament-jira-feedback'
        );

        // Register the JiraFeedbackService as a singleton
        $this->app->singleton(JiraFeedbackService::class, function ($app) {
            return new JiraFeedbackService;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/filament-jira-feedback.php' => config_path('filament-jira-feedback.php'),
        ], 'filament-jira-feedback-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-jira-feedback'),
        ], 'filament-jira-feedback-views');

        // Load package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-jira-feedback');

        // Register Livewire component
        Livewire::component('filament-jira-feedback-banner', FeedbackBanner::class);

        // Register the widget as a Livewire component
        Livewire::component('filament-jira-feedback-banner-widget', FeedbackBannerWidget::class);

        // Register the widget globally on all pages using a render hook
        if (config('filament-jira-feedback.enabled', true)) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::BODY_START,
                fn (): string => Blade::render('@livewire(\'filament-jira-feedback-banner-widget\')')
            );
        }
    }
}
