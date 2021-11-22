<?php
namespace App\Repositories;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\Permission;

class Roles
{
    private Role $model;
    public static function init(Role $model): Roles
    {
        $instance = new self;
        $instance->model = $model;
        return $instance;
    }

    public static function store(object $data): Role
    {
        $model = new Role((array) $data);
        $model->title = $data->title;
        $model->guard_name = 'web';
        $model->name = Str::slug($model->title);
        $model->saveOrFail();
        return $model;
    }

    public function show(Request $request): Role {
        //Fetch relationships
            return $this->model;
    }
    public function update(object $data): Role
    {
        $this->model->update((array) $data);
        
        // Save Relationships
                        

        $this->model->saveOrFail();
        return $this->model;
    }

    public function destroy(): bool
    {
        return !!$this->model->delete();
    }
    public static function dtColumns() {
        $columns = [
            Column::make('id')->title('ID')->className('all text-right'),
            Column::make("name")->className('all'),
            Column::make("title")->className('all'),
            Column::make("guard_name")->className('min-desktop-lg'),
            Column::make("created_at")->className('min-tv'),
            Column::make("updated_at")->className('min-tv'),
            Column::make('actions')->className('min-desktop text-right')->orderable(false)->searchable(false),
        ];
        return $columns;
    }
    public static function dt($query) {
        return DataTables::of($query)
            ->editColumn('actions', function (Role $model) {
                $actions = '';
                if (\Auth::user()->can('view',$model)) $actions .= '<button class="bg-primary hover:bg-primary-600 p-2 px-3 focus:ring-0 focus:outline-none text-white action-button" title="View Details" data-action="show-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-eye"></i></button>';
                if (\Auth::user()->can('update',$model)) $actions .= '<button class="bg-secondary hover:bg-secondary-600 p-2 px-3 focus:ring-0 focus:outline-none action-button" title="Edit Record" data-action="edit-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-edit"></i></button>';
                if (\Auth::user()->can('delete',$model)) $actions .= '<button class="bg-danger hover:bg-danger-600 p-2 px-3 text-white focus:ring-0 focus:outline-none action-button" title="Delete Record" data-action="delete-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
                return "<div class='gap-x-1 flex w-full justify-end'>".$actions."</div>";
            })
            ->rawColumns(['actions'])
            ->make();
    }

    public function assignPermission(array $data): bool {
        $perm = Permission::whereId($data['id'])->firstOrFail();
        if ($data['checked']) {
            $this->model->givePermissionTo($perm);
        } else  {
            if ($this->model->hasPermissionTo($perm)) {
                $this->model->revokePermissionTo($perm);
            }
        }
        return $this->model->hasPermissionTo($perm);
    }
}
