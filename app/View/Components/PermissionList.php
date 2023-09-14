<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\CheckboxList;

class PermissionList extends CheckboxList
{
    protected string $view = 'components.permission.list';
}
