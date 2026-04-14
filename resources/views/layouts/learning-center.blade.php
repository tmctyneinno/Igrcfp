<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - {{ $title ?? 'Dashboard' }}</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-slate-50 text-gray-900">
        <div class="min-h-screen">
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-gray-400">Dashboard</p>
                        <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Learning Center' }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-blue-900 hover:text-blue-700">Back to Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-gray-600 hover:text-gray-900 mt-3">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="py-10">
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                        <div class="rounded-xl border border-slate-200 bg-white p-4 text-gray-700">
                            {{ session('info') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </body>
</html>
