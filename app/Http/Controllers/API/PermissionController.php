<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\IndexPermission;
use App\Http\Requests\Permission\StorePermission;
use App\Http\Requests\Permission\UpdatePermission;
use App\Http\Requests\Permission\DestroyPermission;
use App\Models\Permission;
use App\Repositories\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Savannabits\JetstreamInertiaGenerator\Helpers\ApiResponse;
use Savannabits\Pagetables\Column;
use Savannabits\Pagetables\Pagetables;
use Yajra\DataTables\DataTables;

class PermissionController  extends Controller
{
    private ApiResponse $api;
    private Permissions $repo;
    public function __construct(ApiResponse $apiResponse, Permissions $repo)
    {
        $this->api = $apiResponse;
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource (paginated).
     * @retcolumnsToQueryurn \Illuminate\Http\JsonResponse
     */
    public function index(IndexPermission $request)
    {
        $query = Permission::query(); // You can extend this however you want.
        $cols = [
            Column::name('id')->title('Id')->sort()->searchable(),
            Column::name('name')->title('Name')->sort()->searchable(),
            Column::name('title')->title('Title')->sort()->searchable(),
            Column::name('guard_name')->title('Guard Name')->sort()->searchable(),
            Column::name('updated_at')->title('Updated At')->sort()->searchable(),
            
            Column::name('actions')->title('')->raw()
        ];
        $data = Pagetables::of($query)->columns($cols)->make(true);
        return $this->api->success()->message("List of Permissions")->payload($data)->send();
    }

    public function dt(Request $request) {
        $query = Permission::query()->select(Permission::getModel()->getTable().'.*'); // You can extend this however you want.
        return $this->repo::dt($query);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param StorePermission $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePermission $request)
    {
        try {
            $data = $request->sanitizedObject();
            $permission = $this->repo::store($data);
            return $this->api->success()->message('Permission Created')->payload($permission)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->payload([])->code(500)->send();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Permission $permission)
    {
        try {
            $payload = $this->repo::init($permission)->show($request);
            return $this->api->success()
                        ->message("Permission $permission->id")
                        ->payload($payload)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->send();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermission $request
     * @param {$modelBaseName} $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePermission $request, Permission $permission)
    {
        try {
            $data = $request->sanitizedObject();
            $res = $this->repo::init($permission)->update($data);
            return $this->api->success()->message("Permission has been updated")->payload($res)->code(200)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->code(400)->message($exception->getMessage())->send();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DestroyPermission $request, Permission $permission)
    {
        $res = $this->repo::init($permission)->destroy();
        return $this->api->success()->message("Permission has been deleted")->payload($res)->code(200)->send();
    }

}
