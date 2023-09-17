<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class FileDownload extends Field
{
    protected string $view = 'forms.components.file-download';
    
    protected string | Closure | null $url = null;

    public function url(string | Closure | null $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->evaluate($this->url);
    }
}
