<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <div class="flex h-screen">
        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col overflow-auto">
            @include('layouts.partials.header')

            <main class="flex-1 p-6 bg-base-200">
                @include('layouts.partials.breadcumbs')
                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    @include('layouts.partials.modal')
    @include('layouts.partials.scripts')
</body>
</html>
