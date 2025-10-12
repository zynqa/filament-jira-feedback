<x-filament-widgets::widget>
    <x-filament::section
        :icon="$this->getBannerIcon()"
        :icon-color="$this->getBannerColor()"
        class="fi-wi-feedback-banner"
    >
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-sm">
                    {{ $this->getBannerMessage() }}
                </p>
            </div>

            <div class="flex-shrink-0">
                {{ ($this->getHeaderActions()[0]) }}
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
