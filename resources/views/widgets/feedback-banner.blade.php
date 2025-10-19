<x-filament-widgets::widget>
    <x-filament::section class="fi-wi-feedback-banner">
        <div style="display: flex !important; flex-direction: row !important; align-items: center !important; justify-content: space-between !important; gap: 1rem !important;">
            <div style="display: flex !important; flex-direction: row !important; align-items: center !important; gap: 0.75rem !important; flex: 1 !important;">
                <x-filament::icon
                    :icon="$this->getBannerIcon()"
                    :class="'w-6 h-6 text-' . $this->getBannerColor() . '-500'"
                    style="flex-shrink: 0 !important;"
                />
                <p class="text-sm" style="margin: 0 !important;">
                    {{ $this->getBannerMessage() }}
                </p>
            </div>

            <div style="flex-shrink: 0 !important;">
                {{ $this->submitFeedbackAction }}
            </div>
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
