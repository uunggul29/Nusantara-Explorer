<script setup>
import { Head } from '@inertiajs/vue3';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';
import { onMounted } from 'vue';

const props = defineProps({
    provinces: Array,
});

// Leaflet's default marker icon paths assume a non-bundled asset layout;
// under Vite the images must be imported and re-registered explicitly.
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

onMounted(() => {
    const map = L.map('map', { zoomControl: false, attributionControl: false }).setView([-2.5489, 118.0149], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    const bounds = L.latLngBounds(L.latLng(-11, 95), L.latLng(6, 141));
    map.setMaxBounds(bounds);
    map.setMinZoom(map.getBoundsZoom(bounds));

    props.provinces.forEach((province) => {
        const marker = L.marker([Number(province.map_lat), Number(province.map_lng)]).addTo(map);
        marker.bindPopup(
            `
            <div class="nx-popup__body">
                <p class="nx-popup__eyebrow">${province.capital_city}</p>
                <h3 class="nx-popup__title">${province.name}</h3>
                <a class="nx-popup__btn" href="/province/${province.slug}">
                    Explore
                </a>
            </div>
        `,
            { className: 'nx-popup', closeButton: true, maxWidth: 220 },
        );
    });

    const homeIcon = L.divIcon({
        html: '<div class="nx-home-marker"><i class="bi bi-house-door-fill"></i><span>Home</span></div>',
        className: 'nx-home-marker-wrap',
        iconSize: [110, 44],
        iconAnchor: [55, 22],
    });
    L.marker([6.970049, 142.294922], { icon: homeIcon })
        .addTo(map)
        .on('click', () => {
            window.location.href = '/';
        });
});
</script>

<template>
    <Head title="Peta - Nusantara Explorer">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    </Head>
    <div id="map"></div>
</template>

<style scoped>
#map {
    height: 100vh;
    width: 100%;
}
</style>

<style>
body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* "Back to home" marker */
.nx-home-marker-wrap {
    background: transparent !important;
    border: none !important;
}
.nx-home-marker {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    height: 100%;
    background: #d6982e;
    color: #14283f;
    font-family: 'Space Mono', monospace;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.03em;
    border-radius: 999px;
    box-shadow: 0 6px 16px rgba(20, 40, 63, 0.35);
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}
.nx-home-marker:hover {
    background: #f0c878;
    transform: translateY(-2px);
}
.nx-home-marker i {
    font-size: 1rem;
}

/* Popup chrome: replace Leaflet's plain white box with the site's indigo/gold identity */
.nx-popup .leaflet-popup-content-wrapper {
    background: #14283f;
    color: #fff;
    border-radius: 14px;
    box-shadow: 0 12px 30px rgba(20, 40, 63, 0.4);
}
.nx-popup .leaflet-popup-content {
    margin: 1rem 1.1rem;
    line-height: 1.3;
}
.nx-popup .leaflet-popup-tip {
    background: #14283f;
}
.nx-popup .leaflet-popup-close-button {
    color: rgba(255, 255, 255, 0.7) !important;
}
.nx-popup .leaflet-popup-close-button:hover {
    color: #fff !important;
}

.nx-popup__body {
    text-align: center;
}
.nx-popup__eyebrow {
    margin: 0;
    font-family: 'Space Mono', monospace;
    font-size: 0.68rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #f0c878;
}
.nx-popup__title {
    margin: 0.15rem 0 0.85rem;
    font-family: 'Fraunces', serif;
    font-size: 1.15rem;
    font-weight: 600;
}
.nx-popup__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #d6982e;
    color: #14283f;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.85rem;
    padding: 0.5rem 1.1rem;
    border-radius: 999px;
    transition: background 0.2s ease;
}
.nx-popup__btn:hover {
    background: #f0c878;
}
</style>
