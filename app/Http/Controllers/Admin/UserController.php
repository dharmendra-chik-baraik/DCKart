<?php
// App/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request): View
    {
        $filters = [
            'role' => $request->get('role'),
            'search' => $request->get('search'),
            'status' => $request->get('status'),
        ];

        $users = $this->userService->getAllUsers($filters);

        return view('admin.users.index', compact('users', 'filters'));
    }

    public function show(string $id): View
    {
        $user = $this->userService->getUserById($id);
        
        if (!$user) {
            abort(404);
        }

        return view('admin.users.show', compact('user'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,vendor,customer',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            $this->userService->createUser($request->all());
            
            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id): View
    {
        $user = $this->userService->getUserById($id);
        
        if (!$user) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:admin,vendor,customer',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            $this->userService->updateUser($id, $request->all());
            
            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->userService->deleteUser($id);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function changeStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            $this->userService->changeUserStatus($id, $request->status);
            
            return back()->with('success', 'User status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}