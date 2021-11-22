<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\IndexPermission;
use App\Http\Requests\Permission\StorePermission;
use App\Http\Requests\Permission\UpdatePermission;
use App\Http\Requests\Permission\DestroyPermission;
use App\Models\Permission;
use App\Repositories\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Yajra\DataTables\Html\Column;

class PermissionController  extends Controller
{
    private Permissions $repo;
    public function __construct(Permissions $repo)
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
        $this->authorize('viewAny', Permission::class);
        return Inertia::render('Permissions/Index',[
            "can" => [
                "viewAny" => \Auth::user()->can('viewAny', Permission::class),
                "create" => \Auth::user()->can('create', Permission::class),
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
        $this->authorize('create', Permission::class);
        return Inertia::render("Permissions/Create",[
            "can" => [
            "viewAny" => \Auth::user()->can('viewAny', Permission::class),
            "create" => \Auth::user()->can('create', Permission::class),
            ]
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param StorePermission $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(StorePermission $request)
    {
        try {
            $data = $request->sanitizedObject();
            $permission = $this->repo::store($data);
            return back()->with(['success' => "The Permission was created succesfully."]);
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
    * @param Permission $permission
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function show(Request $request, Permission $permission)
    {
        try {
            $this->authorize('view', $permission);
            $model = $this->repo::init($permission)->show($request);
            return Inertia::render("Permissions/Show", ["model" => $model]);
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
    * @param Permission $permission
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function edit(Request $request, Permission $permission)
    {
        try {
            $this->authorize('update', $permission);
            //Fetch relationships
            

                        return Inertia::render("Permissions/Edit", ["model" => $permission]);
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
    * @param UpdatePermission $request
    * @param {$modelBaseName} $permission
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(UpdatePermission $request, Permission $permission)
    {
        try {
            $data = $request->sanitizedObject();
            $res = $this->repo::init($permission)->update($data);
            return back()->with(['success' => "The Permission was updated succesfully."]);
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
    * @param Permission $permission
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(DestroyPermission $request, Permission $permission)
    {
        $res = $this->repo::init($permission)->destroy();
        if ($res) {
            return back()->with(['success' => "The Permission was deleted succesfully."]);
        }
        else {
            return back()->with(['error' => "The Permission could not be deleted."]);
        }
    }
}
