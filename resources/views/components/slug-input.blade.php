<x-forms::field-wrapper :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
    class="-mt-3 filament-addons-slug-input-wrapper">
    <div x-data="{
        state: $wire.entangle('{{ $getStatePath() }}'),
        original: '',
        editing: false,
        init: function() { this.original = this.state; }
    }">
        <div {{ $attributes->merge($getExtraAttributes())->class(['flex items-center space-x-2 group filament-forms-text-input-component']) }}>

            <div class="text-sm">
                <strong>Permalink:</strong> {{ $getRouteName() }}@if(! $isFrontPage())<button
                        type="button"
                        class="underline inline text-primary-500 hover:text-primary-600 focus:text-primary-600"
                        x-on:click="editing = true"
                        x-show="!editing">{{ $getState() }}</button><span x-show="!editing && state !== null">/</span>@endif
            </div>

            <div class="flex-1"
                x-show="editing"
                style="display: none;">
                <input type="text"
                    x-model="original"
                    x-bind:disabled="!editing"
                    id="{{ $getId() }}"
                    {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                    {!! $isRequired() ? 'required' : null !!}
                    {{ $getExtraInputAttributeBag()->class([
                        'block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70', 'dark:bg-gray-700 dark:text-white' => config('forms.dark_mode'),
                        'border-gray-300' => !$errors->has($getStatePath()),
                        'dark:border-gray-600' => !$errors->has($getStatePath()) && config('forms.dark_mode'),
                        'border-danger-600 ring-danger-600' => $errors->has($getStatePath())
                    ]) }}
                />
                <input type="hidden" {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}" />
            </div>

            <div x-show="editing" class="flex items-center" style="display: none;">
                <x-filament::icon-button
                    color="danger"
                    x-on:click="original = state; editing = false;"
                    icon="heroicon-s-x"
                    label="{{ __('trov::buttons.cancel') }}"
                />
                <x-filament::icon-button
                    color="primary"
                    x-on:click="state = original; editing = false;"
                    icon="heroicon-s-check"
                    label="{{ __('trov::buttons.save') }}"
                />
            </div>

        </div>
    </div>
</x-forms::field-wrapper>
