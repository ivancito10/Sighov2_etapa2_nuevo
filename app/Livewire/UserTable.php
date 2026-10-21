<?php

namespace App\Livewire;


use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Console\View\Components\Component;

class UserTable extends Component
{
    /*
    protected $model = User::class;

    public function configure(): void
    {
        $this ->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable()->searchable(),
            Column::make("Email", "email")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
    */
}
