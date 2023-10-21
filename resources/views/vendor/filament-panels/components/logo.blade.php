<div class="flex items-center gap-4">
    @if(apotekLogo() && Storage::disk('files-apotek')->exists(apotekLogo()))
        <img src="{{ Storage::disk('files-apotek')->url(apotekLogo()) }}" alt="Logo" class="h-10">
    @else
        <img src="{{ asset('/images/default.png') }}" alt="Logo" class="h-10">
    @endif
    @if(trim(apotekName()))
        <span class="whitespace-nowrap text-lg text-primary-600 font-semibold">{{apotekName()}}</span>
    @else
        <span class="whitespace-nowrap text-lg text-primary-600 font-semibold">Kelola Apotek</span>
    @endif
</div>