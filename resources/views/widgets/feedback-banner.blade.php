<x-filament-widgets::widget>
    <x-filament::section class="fi-wi-feedback-banner">
        <div class="flex items-center gap-4" style="display: flex !important; flex-wrap: nowrap !important; align-items: center !important;">
            <div class="flex-shrink-0 pr-4" style="padding-right: 4px; flex-shrink: 0 !important;">
                <x-filament::icon
                    :icon="$this->getBannerIcon()"
                    :class="'w-6 h-6 text-' . $this->getBannerColor() . '-500'"
                    style="flex-shrink: 0 !important;"
                />
            </div>

            <div class="flex-1" style="flex: 1 1 0% !important; min-width: 0 !important; overflow: hidden !important;">
                <p class="text-sm" style="white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important;">
                    {{ $this->getBannerMessage() }}
                </p>
            </div>

            <div class="flex-shrink-0" style="padding-right: 2px; flex-shrink: 0 !important; white-space: nowrap !important; padding-right: 0.5rem !important;">
                {{ $this->submitFeedbackAction }}
            </div>
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
