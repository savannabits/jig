<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\IndexRole;
use App\Http\Requests\Role\StoreRole;
use App\Http\Requests\Role\UpdateRole;
use App\Http\Requests\Role\DestroyRole;
use App\Models\Role;
use App\Repositories\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Yajra\DataTables\Html\Column;
use App\Models\Permission;

class RoleController  extends Controller
{
    private Roles $repo;
    public function __construct(Roles $repo)
    {
        $this->repo = $repo;
    }

    /**
    * Display a listing of the resource.
    *
    * @param  Request $request
    * @return    \Inertia\Response
    * @throws  \Illuminate\Auth\Access\AuthorizationException
    */
    public function index(Request $request): \Inertia\Response
    {
        $this->authorize('viewAny', Role::class);
        return Inertia::render('Roles/Index',[
            "can" => [
                "viewAny" => \Auth::user()->can('viewAny', Role::class),
                "create" => \Auth::user()->can('create', Role::class),
            ],
            "columns" => $this->repo::dtColumns(),
        ]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return  \Inertia\Response
    */
    public function create()
    {
        $this->authorize('create', Role::class);
        return Inertia::render("Roles/Create",[
            "can" => [
            "viewAny" => \Auth::user()->can('viewAny', Role::class),
            "create" => \Auth::user()->can('create', Role::class),
            ]
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param StoreRole $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(StoreRole $request)
    {
        try {
            $data = $request->sanitizedObject();
            $role = $this->repo::store($data);
            return back()->with(['success' => "The Role was created succesfully."]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Display the specified resource.
    *
    * @param Request $request
    * @param Role $role
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function show(Request $request, Role $role)
    {
        try {
            $this->authorize('view', $role);
            $model = $this->repo::init($role)->show($request);
            return Inertia::render("Roles/Show", ["model" => $model]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Show Edit Form for the specified resource.
    *
    * @param Request $request
    * @param Role $role
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function edit(Request $request, Role $role)
    {
        try {
            $this->authorize('update', $role);
            //Fetch relationships
            

            
            $permissions = Permission::all(['id','name','title'])->map(function($perm) use($role) {
                $perm->checked = $role->hasDirectPermission($perm);
                $perm->append('group');
                return $perm->toArray();
            })->groupBy('group');
            return Inertia::render("Roles/Edit", [
                "model" => $role,
                "permissions" => $permissions,
            ]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param UpdateRole $request
    * @param {$modelBaseName} $role
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(UpdateRole $request, Role $role)
    {
        try {
            $data = $request->sanitizedObject();
            $res = $this->repo::init($role)->update($data);
            return back()->with(['success' => "The Role was updated succesfully."]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param Role $role
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(DestroyRole $request, Role $role)
    {
        $res = $this->repo::init($role)->destroy();
        if ($res) {
            return back()->with(['success' => "The Role was deleted succesfully."]);
        }
        else {
            return back()->with(['error' => "The Role could not be deleted."]);
        }
    }
}
