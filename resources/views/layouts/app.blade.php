<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    @include('layouts.partials.head')
</head>

<body>
    @if (session('success'))
        <div class="toast">
            <div class="alert alert-info">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast">
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="toast">
            <div class="alert alert-warning">
                <span>{{ session('warning') }}</span>
            </div>
        </div>
    @endif
    <div class="flex h-screen">
        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col overflow-auto">
            @include('layouts.partials.header')

            <main class="flex-1 p-6 bg-base-200">

                @yield('content')
            </main>


            @include('layouts.partials.footer')
        </div>
    </div>

    @include('layouts.partials.modal')
    @include('layouts.partials.scripts')
</body>

</html>
