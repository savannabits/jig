<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUser;
use App\Http\Requests\User\StoreUser;
use App\Http\Requests\User\UpdateUser;
use App\Http\Requests\User\DestroyUser;
use App\Models\User;
use App\Repositories\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Yajra\DataTables\Html\Column;
use \Spatie\Permission\Models\Role;

class UserController  extends Controller
{
    private Users $repo;
    public function __construct(Users $repo)
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
        $this->authorize('viewAny', User::class);
        return Inertia::render('Users/Index',[
            "can" => [
                "viewAny" => \Auth::user()->can('viewAny', User::class),
                "create" => \Auth::user()->can('create', User::class),
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
        $this->authorize('create', User::class);
        $roles = Role::all()->map(function ($role) {
            $role->checked = false;
            return $role->only(['id', 'name', 'title', 'checked']);
        })->keyBy('name');
        return Inertia::render("Users/Create", [
            "can" => [
                "viewAny" => \Auth::user()->can('viewAny', User::class),
                "create" => \Auth::user()->can('create', User::class),
            ],
            "roles" => $roles,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param StoreUser $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(StoreUser $request)
    {
        try {
            $data = $request->sanitizedObject();
            $user = $this->repo::store($data);
            return back()->with(['success' => "The User was created succesfully."]);
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
    * @param User $user
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function show(Request $request, User $user)
    {
        try {
            $this->authorize('view', $user);
            $model = $this->repo::init($user)->show($request);
            return Inertia::render("Users/Show", ["model" => $model]);
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
    * @param User $user
    * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function edit(Request $request, User $user)
    {
        try {
            $this->authorize('update', $user);
            $roles = Role::all()->map(function ($role) use($user) {
                $checked = $user->hasRole([$role]);
                $role->checked = $checked;
                $role->title = $role->title ?? Str::title(str_replace("-"," ",Str::slug($role->name)));
                return $role->only(['id','name','title', 'checked']);
            })->keyBy('name');
            //Fetch relationships
            

                        return Inertia::render("Users/Edit", ["model" => $user,"roles" => $roles]);
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
    * @param UpdateUser $request
    * @param {$modelBaseName} $user
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(UpdateUser $request, User $user)
    {
        try {
            $data = $request->sanitizedObject();
            $res = $this->repo::init($user)->update($data);
            return back()->with(['success' => "The User was updated succesfully."]);
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
    * @param User $user
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(DestroyUser $request, User $user)
    {
        $res = $this->repo::init($user)->destroy();
        if ($res) {
            return back()->with(['success' => "The User was deleted succesfully."]);
        }
        else {
            return back()->with(['error' => "The User could not be deleted."]);
        }
    }
}
