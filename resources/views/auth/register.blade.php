
<!-- resources/views/auth/register.blade.php -->
@extends('layouts.auth')

@section('content')
<div class="h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-6 space-y-4">
            <!-- Header -->
            <div class="text-center space-y-1">
                <i data-lucide="user-plus" class="w-8 h-8 text-indigo-600 mx-auto"></i>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Create account</h2>
                <p class="text-sm text-gray-500">Join us today and get started</p>
            </div>

            <form method="POST" action="/register" class="space-y-4">
                @csrf

                <div class="space-y-3">
                    <div class="space-y-1">
                        <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                            <span>Full name</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors text-gray-800 placeholder-gray-400"
                            placeholder="Enter your name"
                            required
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                            <span>Email address</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors text-gray-800 placeholder-gray-400"
                            placeholder="Enter your email"
                            required
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i data-lucide="key" class="w-4 h-4 text-gray-400"></i>
                            <span>Password</span>
                        </label>
                        <input
                            type="password"
                            name="password"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors text-gray-800 placeholder-gray-400"
                            placeholder="Create a password"
                            required
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i data-lucide="check-circle" class="w-4 h-4 text-gray-400"></i>
                            <span>Confirm password</span>
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors text-gray-800 placeholder-gray-400"
                            placeholder="Confirm your password"
                            required
                        >
                    </div>
                </div>

                @if ($errors->any())
                <div class="flex items-center space-x-2 text-red-500 bg-red-50 p-2 rounded-lg text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    <p>{{ $errors->first() }}</p>
                </div>
                @endif

                <button
                    type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2"
                >
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Create account</span>
                </button>
            </form>

            <p class="text-center text-sm text-gray-500">
                Already have an account?
                <a href="/login" class="text-indigo-600 hover:text-indigo-700 font-medium">Sign in</a>
            </p>
        </div>
    </div>
</div>
