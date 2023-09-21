<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Resources\Pages\Page;

class PersediaanProduk extends Page
{
    protected static string $resource = ProdukResource::class;

    protected static string $view = 'filament.resources.produk-resource.pages.persediaan-produk';
}
