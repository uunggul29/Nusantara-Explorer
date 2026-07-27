<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Head, Link } from '@inertiajs/vue3';

const isSidebarOpen = ref(false);

const navItems = [
    { label: 'Dashboard', route: 'admin.dashboard', icon: 'bi-speedometer2' },
    { label: 'Provinsi', route: 'admin.provinces.index', icon: 'bi-map' },
    { label: 'Tim', route: 'admin.team-members.index', icon: 'bi-people' },
    { label: 'Statistik Home', route: 'admin.home-stats.index', icon: 'bi-bar-chart-line' },
];

const isActive = (item) =>
    route().current(item.route) || route().current(item.route.replace('.index', '.*'));
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    </Head>

    <div id="admin-app">
        <div class="min-h-screen bg-gray-50">
            <!-- Mobile backdrop -->
            <div
                v-if="isSidebarOpen"
                class="fixed inset-0 z-30 bg-black/40 md:hidden"
                @click="isSidebarOpen = false"
            ></div>

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-40 flex w-56 flex-col justify-between text-white transition-transform md:translate-x-0"
                :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                style="background: linear-gradient(180deg, #1e3a5f 0%, #14283f 100%)"
            >
                <div>
                    <div class="flex items-center gap-2 px-6 py-6">
                        <img src="/favicon.svg" alt="" class="h-8 w-auto" />
                        <span class="font-['Space_Mono'] text-[11px] uppercase leading-tight tracking-[0.15em] text-[#f0c878]">
                            Nusantara<br />Explorer
                        </span>
                    </div>

                    <nav class="mt-2 space-y-1 px-3">
                        <Link
                            v-for="item in navItems"
                            :key="item.route"
                            :href="route(item.route)"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                            :class="isActive(item) ? 'bg-[#d6982e] text-[#14283f]' : 'text-white/75 hover:bg-white/10 hover:text-white'"
                        >
                            <i :class="['bi', item.icon, 'text-base']"></i>
                            {{ item.label }}
                        </Link>
                    </nav>
                </div>

                <div class="border-t border-white/10 p-4">
                    <Link href="/" class="mb-3 flex items-center gap-2 px-2 text-xs text-white/60 hover:text-white">
                        <i class="bi bi-box-arrow-up-right"></i> Lihat Situs
                    </Link>
                    <Dropdown align="left" width="48" direction="up">
                        <template #trigger>
                            <button type="button" class="flex w-full items-center gap-2 overflow-hidden rounded-lg px-2 py-2 text-left hover:bg-white/10">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#d6982e]/20">
                                    <i class="bi bi-person-fill text-sm text-[#f0c878]"></i>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-[13px] font-medium text-white">{{ $page.props.auth.user.name }}</span>
                                    <span class="block truncate text-[11px] text-white/50">{{ $page.props.auth.user.email }}</span>
                                </span>
                                <i class="bi bi-chevron-expand shrink-0 text-xs text-white/40"></i>
                            </button>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </aside>

            <!-- Main column -->
            <div class="md:pl-56">
                <div class="flex h-14 items-center gap-3 border-b border-gray-200 bg-white px-4 md:hidden">
                    <button type="button" class="text-gray-500" @click="isSidebarOpen = !isSidebarOpen">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                </div>

                <header class="border-b border-gray-200 bg-white" v-if="$slots.header">
                    <div class="px-4 py-6 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <main class="px-4 py-8 sm:px-6 lg:px-8">
                    <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ $page.props.flash.success }}
                    </div>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
