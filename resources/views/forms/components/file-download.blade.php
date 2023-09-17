<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div class="py-2">
        <a href="{{$getUrl()}}" class="flex justify-center text-white bg-primary-500 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
            Download
        </a>
    </div>
</x-dynamic-component>
