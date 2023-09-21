<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }
        </style>
         @vite('resources/css/filament/admin/theme.css')
    </head>
    <body class="antialiased">
    
        <div class="flex h-[calc(100vh-80px)] items-center justify-center p-5 bg-white w-full">
            <div class="text-center">
                
                @yield('icon')
                    
                <h2 class="mt-5 text-3xl font-bold text-slate-800 lg:text-5xl">
                    @yield('code')
                </h1>
                <h1 class="mt-5 text-3xl font-bold text-slate-800 lg:text-5xl">
                    @yield('message')
                </h1>
                <div class="mt-5">
                    <div class="w-full flex flex-col items-center justify-center">
                        <span class="text-slate-600 lg:text-lg">
                            @yield('description')
                        </span>
                        <div class="mt-5 flex flex-col sm:flex-row sm:space-x-3">
                            <a class="text-white bg-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium text-xs lg:text-sm px-3 py-2 lg:px-5 lg:py-2.5 mb-2 dark:bg-teal-600 dark:hover:bg-teal-700 focus:outline-none dark:focus:ring-teal-800" href="javascript:history.back()">Kembali ke halaman sebelumnya</a>
                            <a class="text-teal-700 hover:text-white border border-teal-700 hover:bg-teal-800 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium text-xs lg:text-sm px-3 py-2 lg:px-5 lg:py-2.5 text-center mb-2 dark:border-teal-500 dark:text-teal-500 dark:hover:text-white dark:hover:bg-teal-500 dark:focus:ring-teal-800" href="{{route('filament.admin.pages.dashboard')}}">Kembali ke halaman utama</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
