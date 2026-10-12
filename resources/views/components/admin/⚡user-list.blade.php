
<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public array $roles = [];

    public function with(): array
    {
        $users = User::query()->latest()->paginate(10);

        foreach ($users as $user) {
            $this->roles[$user->id] ??= $user->role;
        }

        return [
            'users' => $users,
        ];
    }

    public function updateRole(int $userId, string $role): void
    {
        // $this->authorize('viewAny', User::class);

        if (! in_array($role, ['admin', 'staff'], true)) {
            abort(422, 'Invalid role.');
        }

        $user = User::findOrFail($userId);

        // Prevent removing your own admin access.
        if ($user->is(auth()->user()) && $role !== 'admin') {
            session()->flash('error', 'You cannot remove your own admin role.');
            return;
        }

        // Prevent demoting the last administrator.
        if (
            $user->role === 'admin'
            && $role !== 'admin'
            && User::where('role', 'admin')->count() <= 1
        ) {
            session()->flash('error', 'You cannot demote the last administrator.');
            return;
        }

        $user->role = $role;
        $user->save();

        session()->flash('success', 'User role updated successfully.');
    }

};
?>

<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        User Management
    </h1>

    @if (session()->has('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded bg-red-100 p-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Role
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Registered
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $user->role === 'admin'
                                    ? 'bg-purple-100 text-purple-800'
                                    : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select
                                wire:model="roles.{{ $user->id }}"
                                class="rounded-md border-gray-300 text-sm"
                            >
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>

                            <button
                                type="button"
                                wire:click="updateRole({{ $user->id }}, $wire.get('roles.{{ $user->id }}'))"
                                class="ml-2 rounded-md bg-indigo-600 px-3 py-1 text-sm text-white hover:bg-indigo-700"
                            >
                                Update
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
