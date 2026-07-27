<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        <style>
            /* Tailwind's Preflight is disabled project-wide (see tailwind.config.js) so the admin
               pages aren't reset by it — replicate the resets Preflight would normally provide,
               since Tailwind's utilities (spacing, sizing, bg-transparent-by-default buttons, etc.)
               assume this baseline exists everywhere. */
            *, *::before, *::after { box-sizing: border-box; }
            body { margin: 0; padding: 0; }
            button, input, optgroup, select, textarea {
                font-family: inherit;
                font-size: 100%;
                line-height: inherit;
                color: inherit;
                margin: 0;
                padding: 0;
            }
            button, select { text-transform: none; }
            button, [type='button'], [type='reset'], [type='submit'] {
                -webkit-appearance: button;
                background-color: transparent;
                background-image: none;
                border: 0;
            }
        </style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
