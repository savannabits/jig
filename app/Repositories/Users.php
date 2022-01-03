<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use \Spatie\Permission\Models\Role;

class Users
{
    private User $model;
    public static function init(User $model): Users
    {
        $instance = new self;
        $instance->model = $model;
        return $instance;
    }

    public static function store(object $data): User
    {
        $model = new User((array) $data);
                // Save Relationships
        if (isset($password) && $data->password) {
            $model->password = \Hash::make($data->password);
        }
        $model->saveOrFail();
        // $assignedRoles = collect($data->assigned_roles)->where("checked", true)->pluck('name');
        return $model;
    }

    public function show(Request $request): User {
        //Fetch relationships
            return $this->model;
    }
    public function update(object $data): User
    {
        $this->model->update((array) $data);
        
        // Save Relationships
                        

        if (isset($data->password) && $data->password) {
            $this->model->password = \Hash::make($data->password);
        }
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
            Column::make("email")->className('min-desktop-lg'),
            Column::make("profile_photo_path")->className('min-desktop-lg'),
            Column::make("email_verified_at")->className('min-desktop-lg'),
            Column::make("created_at")->className('min-tv'),
            Column::make("updated_at")->className('min-tv'),
            Column::make('actions')->className('min-desktop text-right')->orderable(false)->searchable(false),
        ];
        return $columns;
    }
    public static function dt($query) {
        return DataTables::of($query)
            ->editColumn('actions', function (User $model) {
                $actions = '';
                if (\Auth::user()->can('view',$model)) $actions .= '<button class="bg-primary hover:bg-primary-600 p-2 px-3 focus:ring-0 focus:outline-none text-white action-button" title="View Details" data-action="show-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-eye"></i></button>';
                if (\Auth::user()->can('update',$model)) $actions .= '<button class="bg-secondary hover:bg-secondary-600 p-2 px-3 focus:ring-0 focus:outline-none action-button" title="Edit Record" data-action="edit-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-edit"></i></button>';
                if (\Auth::user()->can('delete',$model)) $actions .= '<button class="bg-danger hover:bg-danger-600 p-2 px-3 text-white focus:ring-0 focus:outline-none action-button" title="Delete Record" data-action="delete-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
                return "<div class='gap-x-1 flex w-full justify-end'>".$actions."</div>";
            })
            ->rawColumns(['actions'])
            ->make();
    }
    public function assignRole(array $data): bool {
        $role = Role::whereName($data['name'])->firstOrFail();
        if ($data['checked']) {
            $this->model->assignRole([$role]);
        } else  {
            if ($this->model->hasRole($role->name)) {
                $this->model->removeRole($role);
            }
        }
        return $this->model->hasRole($role->name);
    }
}
