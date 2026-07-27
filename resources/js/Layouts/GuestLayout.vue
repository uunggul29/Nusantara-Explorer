<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Work+Sans:wght@400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    </Head>

    <div id="admin-app">
        <div
            class="flex h-screen items-center justify-center overflow-hidden bg-[#14283f] px-4 py-6"
            style="background-image: radial-gradient(circle at 15% 15%, rgba(214,152,46,0.14), transparent 40%), radial-gradient(circle at 85% 85%, rgba(214,152,46,0.1), transparent 40%)"
        >
            <div class="grid max-h-full w-full overflow-hidden rounded-2xl shadow-2xl sm:max-w-xl md:grid-cols-2">
                <!-- Branding panel -->
                <div
                    class="relative hidden flex-col justify-between p-6 text-white md:flex"
                    style="background: linear-gradient(160deg, #1e3a5f 0%, #14283f 70%)"
                >
                    <div
                        class="pointer-events-none absolute inset-0 opacity-40"
                        style="background-image: radial-gradient(circle at 30% 20%, rgba(214,152,46,0.35), transparent 45%)"
                    ></div>

                    <Link href="/" class="relative z-10 flex items-center gap-2">
                        <img src="/favicon.svg" alt="" class="h-7 w-auto" />
                        <span class="font-['Space_Mono'] text-[11px] uppercase tracking-[0.2em] text-[#f0c878]">Nusantara Explorer</span>
                    </Link>

                    <div class="relative z-10">
                        <p class="font-['Space_Mono'] text-[11px] uppercase tracking-[0.14em] text-[#f0c878]">Panel Admin</p>
                        <h1 class="mt-2 font-['Fraunces'] text-xl font-semibold leading-snug">
                            Kelola warisan budaya 38 provinsi Nusantara
                        </h1>
                        <p class="mt-2 text-xs leading-relaxed text-white/70">
                            Perbarui profil provinsi, galeri budaya, tim, dan statistik beranda dari satu tempat.
                        </p>
                    </div>

                    <p class="relative z-10 font-['Space_Mono'] text-[10px] text-white/40">
                        Cultural Expedition for Young People
                    </p>
                </div>

                <!-- Form panel -->
                <div class="overflow-y-auto bg-white px-6 py-6 sm:px-8">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
