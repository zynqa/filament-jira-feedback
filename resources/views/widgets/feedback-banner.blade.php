<x-filament-widgets::widget>
    <x-filament::section class="fi-wi-feedback-banner">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0">
                <x-filament::icon
                    :icon="$this->getBannerIcon()"
                    :class="'w-6 h-6 text-' . $this->getBannerColor() . '-500'"
                />
            </div>

            <div class="flex-1">
                <p class="text-sm">
                    {{ $this->getBannerMessage() }}
                </p>
            </div>

            <div class="flex-shrink-0">
                {{ $this->submitFeedbackAction }}
            </div>
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
