@props([
    'collapsible' => false,
    'description' => null,
    'label' => null,
    'title',
])

<div
    @if ($collapsible)
        x-on:click="toggleCollapseGroup(@js($title))"
    @endif
    {{
        $attributes->class([
            'flex w-full items-center gap-x-3 bg-gray-100 px-3 py-3.5 dark:bg-white/5 sm:px-6',
            'cursor-pointer' => $collapsible,
        ])
    }}
>
    @if ($collapsible)
        <x-filament::icon-button
            color="gray"
            icon-alias="tables::grouping.collapse-button"
            icon="heroicon-m-chevron-up"
            size="sm"
            :x-bind:class="'isGroupCollapsed(' . \Illuminate\Support\Js::from($title) . ') && \'rotate-180\''"
            class="-m-1.5"
        />
    @endif

    <div class="flex flex-col md:flex-row md:justify-between w-full">
        <h4 class="text-sm font-medium text-gray-950 dark:text-white">
            @if (filled($label))
                {{ $label }}:
            @endif

            {!! $title !!}
        </h4>

        @if (filled($description))
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {!! $description !!}
            </p>
        @endif
    </div>
</div>
