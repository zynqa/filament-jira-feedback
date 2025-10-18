@php
    $colors = $this->getColors();
@endphp

<div class="fi-feedback-banner w-full {{ $colors['bg'] }} {{ $colors['text'] }} border-l-4 {{ $colors['border'] }} px-4 py-3 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 flex-1">
            <x-filament::icon
                :icon="$this->getBannerIcon()"
                class="h-5 w-5 flex-shrink-0 {{ $colors['icon'] }}"
            />
            <p class="text-sm font-medium">
                {{ $this->getBannerMessage() }}
            </p>
        </div>

        <div class="flex-shrink-0 pr-4">
            {{ $this->submitFeedbackAction }}
        </div>
    </div>
</div>

<x-filament-actions::modals />
