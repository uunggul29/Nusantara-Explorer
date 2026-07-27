import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    // Tailwind is loaded globally by app.js, but only the admin/auth pages use it —
    // the public site (Home/Islands/ProvinceShow) relies on legacy Bootstrap CSS.
    // Disabling Preflight and scoping utilities under #admin-app keeps Tailwind's
    // reset from stripping Bootstrap's default element styling on public pages.
    important: '#admin-app',
    corePlugins: {
        preflight: false,
    },

    plugins: [forms],
};
