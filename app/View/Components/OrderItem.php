<?php

namespace App\View\Components;

use Closure;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;

class OrderItem extends Repeater
{
    protected string $breakPoint = 'md';

    protected array|Closure $columnWidths = [];

    protected null|bool|string|Closure $emptyLabel = null;

    protected Closure|array $headers = [];

    protected bool|Closure $showLabels = true;

    protected string $view = 'components.order-item';

    protected bool|Closure $withoutHeader = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultItems(0);
    }

    public function breakPoint(string $breakPoint = 'md'): static
    {
        $this->breakPoint = $breakPoint;

        return $this;
    }

    public function columnWidths(array|Closure $widths = []): static
    {
        $this->columnWidths = $widths;

        return $this;
    }

    public function emptyLabel(bool|string|Closure $label = null): static
    {
        $this->emptyLabel = $label;

        return $this;
    }

    public function getBreakPoint(): string
    {
        return $this->breakPoint;
    }

    public function getColumnWidths(): array
    {
        return $this->evaluate($this->columnWidths);
    }

    public function getChildComponents(): array
    {
        $components = parent::getChildComponents();

        if ($this->shouldShowLabels()) {
            return $components;
        }

        foreach ($components as $component) {
            if (
                method_exists($component, 'hiddenLabel') &&
                ! $component instanceof Placeholder
            ) {
                $component->hiddenLabel();
            }
        }

        return $components;
    }

    public function getAddAction(): Action
    {
        $action = Action::make($this->getAddActionName())
            ->label(fn (Repeater $component) => $component->getAddActionLabel())
            ->color('gray')
            ->action(function (Repeater $component): void {
                $newUuid = (string) Str::uuid();

                $items = $component->getState();
                
                $items[$newUuid] = [];

                $component->state($items);

                $component->getChildComponentContainers()[$newUuid]->fill();

                $component->collapsed(false, shouldMakeComponentCollapsible: false);
            })
            ->button()
            ->size(ActionSize::Small)
            ->visible(fn (): bool => $this->isAddable());

        if ($this->modifyAddActionUsing) {
            $action = $this->evaluate($this->modifyAddActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function getEmptyLabel(): bool|string|null
    {
        return $this->evaluate($this->emptyLabel);
    }

    public function getHeaders(): array
    {
        $mergedHeaders = [];

        $customHeaders = $this->evaluate($this->headers);

        foreach ($this->getChildComponents() as $field) {
            if ($field instanceof Hidden || $field->isHidden()) {
                continue;
            }

            $key = method_exists($field, 'getName') ? $field->getName() : $field->getId();

            $isRequired = false;

            if (property_exists($field, 'isRequired') && is_bool($field->isRequired)) {
                $isRequired = $field->isRequired;

                if (property_exists($field, 'isMarkedAsRequired')) {
                    $isRequired = $field->isRequired && $field->isMarkedAsRequired;
                }
            }

            $item = [
                'label' => $customHeaders[$key] ?? $field->getLabel(),
                'width' => $this->getColumnWidths()[$key] ?? null,
                'required' => $isRequired,
            ];

            $mergedHeaders[method_exists($field, 'getName') ? $field->getName() : $field->getId()] = $item;
        }

        $this->headers = $mergedHeaders;

        return $this->evaluate($this->headers);
    }

    public function headers(array|Closure $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    public function hideLabels(): static
    {
        $this->showLabels = false;

        return $this;
    }

    protected function shouldShowLabels(): bool
    {
        return $this->evaluate($this->showLabels);
    }

    public function shouldHideHeader(): bool
    {
        return $this->evaluate($this->withoutHeader);
    }

    public function showLabels(bool|Closure $show = true): static
    {
        $this->showLabels = $show;

        return $this;
    }

    public function withoutHeader(bool|Closure $condition = true): static
    {
        $this->withoutHeader = $condition;

        return $this;
    }
}
