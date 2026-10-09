<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

new class extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(
            $credentials,
            $this->remember
        )) {
            Session::regenerate();

            return $this->redirect(
                route('dashboard'),
                navigate: true
            );
        }

        $this->addError(
            'email',
            'The provided credentials do not match our records.'
        );
    }
};
?>

<div>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-xl shadow-sm border p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">
                    Login
                </h1>

                <p class="text-sm text-gray-500 mt-2">
                    Sign in to access your dashboard
                </p>
            </div>

            <form wire:submit="login" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email address
                    </label>

                    <input
                        id="email"
                        type="email"
                        wire:model="email"
                        autocomplete="username"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        wire:model="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input
                        type="checkbox"
                        wire:model="remember"
                        class="rounded border-gray-300"
                    >
                    Remember me
                </label>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="login">Sign In</span>
                    <span wire:loading wire:target="login">Signing in...</span>
                </button>
            </form>
        </div>
    </div>
</div>