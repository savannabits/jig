<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\IndexRole;
use App\Http\Requests\Role\StoreRole;
use App\Http\Requests\Role\UpdateRole;
use App\Http\Requests\Role\DestroyRole;
use App\Models\Role;
use App\Repositories\Roles;
use Illuminate\Http\Request;
use Savannabits\JetstreamInertiaGenerator\Helpers\ApiResponse;
use Savannabits\Pagetables\Column;
use Savannabits\Pagetables\Pagetables;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class RoleController  extends Controller
{
    private ApiResponse $api;
    private Roles $repo;
    public function __construct(ApiResponse $apiResponse, Roles $repo)
    {
        $this->api = $apiResponse;
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource (paginated).
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRole $request)
    {
        $query = Role::query(); // You can extend this however you want.
        $cols = [
            Column::name('id')->title('Id')->sort()->searchable(),
            Column::name('name')->title('Name')->sort()->searchable(),
            Column::name('title')->title('Title')->sort()->searchable(),
            Column::name('guard_name')->title('Guard Name')->sort()->searchable(),
            Column::name('updated_at')->title('Updated At')->sort()->searchable(),
            
            Column::name('actions')->title('')->raw()
        ];
        $data = Pagetables::of($query)->columns($cols)->make(true);
        return $this->api->success()->message("List of Roles")->payload($data)->send();
    }

    public function dt(Request $request) {
        $query = Role::query()->select(Role::getModel()->getTable().'.*'); // You can extend this however you want.
        return $this->repo::dt($query);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRole $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRole $request)
    {
        try {
            $data = $request->sanitizedObject();
            $role = $this->repo::store($data);
            return $this->api->success()->message('Role Created')->payload($role)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->payload([])->code(500)->send();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Role $role)
    {
        try {
            $payload = $this->repo::init($role)->show($request);
            return $this->api->success()
                        ->message("Role $role->id")
                        ->payload($payload)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->send();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRole $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRole $request, Role $role)
    {
        try {
            $data = $request->sanitizedObject();
            $res = $this->repo::init($role)->update($data);
            return $this->api->success()->message("Role has been updated")->payload($res)->code(200)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->code(400)->message($exception->getMessage())->send();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DestroyRole $request, Role $role)
    {
        $res = $this->repo::init($role)->destroy();
        return $this->api->success()->message("Role has been deleted")->payload($res)->code(200)->send();
    }

    /**
     * The API Function
     * @throws  AuthorizationException
     */
    public function assignPermission(Request $request, Role $role): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update',$role);
        $validated = $request->validate([
            'permission' => ["required","array"],
            'permission.id' =>['required','numeric'],
            'permission.checked' =>['required','boolean']
        ]);
        $res = Roles::init($role)->assignPermission($validated['permission']);
        return $this->api->success()->message("Role assignment updated")->payload($res)->send();
    }

}
