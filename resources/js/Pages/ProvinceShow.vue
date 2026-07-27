<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    province: Object,
    cultureItemsByCategory: Object,
    collectionIndex: Number,
    collectionTotal: Number,
});

const storageUrl = (path) => (path ? `/storage/${path}` : '');

const isNavOpen = ref(false);
const isScrolled = ref(false);
const onScroll = () => (isScrolled.value = window.scrollY > 40);
onMounted(() => window.addEventListener('scroll', onScroll));
onUnmounted(() => window.removeEventListener('scroll', onScroll));

const categories = [
    { key: 'artifact', label: 'Artefak', icon: 'bi-gem' },
    { key: 'food', label: 'Kuliner', icon: 'bi-egg-fried' },
    { key: 'tradition', label: 'Tradisi', icon: 'bi-mask' },
    { key: 'arts', label: 'Kesenian', icon: 'bi-stars' },
];

const activeFilter = ref('all');

const allCultureItems = computed(() =>
    categories.flatMap((c) => props.cultureItemsByCategory[c.key] ?? []),
);

const filteredItems = computed(() => {
    if (activeFilter.value === 'all') return allCultureItems.value;
    return props.cultureItemsByCategory[activeFilter.value] ?? [];
});

const categoryLabel = (key) => categories.find((c) => c.key === key)?.label ?? key;

const paddedIndex = computed(() => String(props.collectionIndex).padStart(2, '0'));
</script>

<template>
    <Head>
        <title>{{ province.name }} | Nusantara Explorer</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    </Head>

    <div class="prov">
        <!-- Navbar -->
        <nav class="prov-nav" :class="{ 'prov-nav--solid': isScrolled }">
            <div class="prov-nav__inner">
                <Link href="/" class="prov-nav__brand">
                    <img src="/favicon.svg" alt="" class="prov-nav__logo" />
                    Nusantara Explorer
                </Link>

                <button class="prov-nav__toggle" @click="isNavOpen = !isNavOpen" aria-label="Buka menu">
                    <span></span><span></span><span></span>
                </button>

                <div class="prov-nav__links" :class="{ 'prov-nav__links--open': isNavOpen }">
                    <a href="#profil">Description</a>
                    <a href="#budaya">Culture</a>
                    <a href="#kuis">Mini Game</a>
                    <Link href="/islands" class="prov-nav__back"><i class="bi bi-arrow-left"></i><span>Map</span></Link>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <header class="prov-hero" :style="{ backgroundImage: `url(${storageUrl(province.hero_image_path)})` }">
            <div class="prov-hero__scrim"></div>
            <div class="prov-hero__content">
                <p class="prov-eyebrow prov-eyebrow--light">Digital Tour Guide &middot; Nusantara Explorer</p>
                <h1 class="prov-hero__title">{{ province.display_name_html || province.name }}</h1>
                <p class="prov-hero__capital">Ibu kota {{ province.capital_city }}</p>
            </div>

            <div class="prov-stamp" aria-hidden="true">
                <svg viewBox="0 0 120 120" class="prov-stamp__ring">
                    <circle cx="60" cy="60" r="56" />
                </svg>
                <div class="prov-stamp__body">
                    <span class="prov-stamp__no">{{ paddedIndex }}</span>
                    <span class="prov-stamp__of">dari {{ collectionTotal }}</span>
                    <span class="prov-stamp__label">provinsi dijelajahi</span>
                </div>
            </div>
        </header>

        <!-- Profil / Description -->
        <section id="profil" class="prov-section">
            <div class="prov-container prov-grid-2">
                <div class="prov-prose prov-prose--intro" v-html="province.about_intro_html"></div>

                <div class="prov-history">
                    <div v-if="province.history_top_images?.length" class="prov-history__frames">
                        <figure v-for="(img, i) in province.history_top_images" :key="i" class="prov-frame">
                            <img :src="storageUrl(img)" alt="" loading="lazy" />
                        </figure>
                    </div>
                    <div class="prov-prose" v-html="province.history_article_html"></div>
                    <figure v-if="province.history_bottom_image" class="prov-frame prov-frame--wide">
                        <img :src="storageUrl(province.history_bottom_image)" alt="" loading="lazy" />
                    </figure>
                </div>
            </div>
        </section>

        <!-- Budaya / Culture gallery -->
        <section id="budaya" class="prov-section prov-section--tinted">
            <div class="prov-container">
                <p class="prov-eyebrow">Jelajahi Warisan</p>
                <h2 class="prov-heading">Budaya &amp; Kekayaan {{ province.name }}</h2>

                <div class="prov-filters">
                    <button
                        class="prov-filter"
                        :class="{ 'prov-filter--active': activeFilter === 'all' }"
                        @click="activeFilter = 'all'"
                    >
                        Semua
                    </button>
                    <button
                        v-for="c in categories"
                        :key="c.key"
                        class="prov-filter"
                        :class="{ 'prov-filter--active': activeFilter === c.key }"
                        @click="activeFilter = c.key"
                    >
                        <i :class="['bi', c.icon]"></i> {{ c.label }}
                    </button>
                </div>

                <TransitionGroup name="prov-card" tag="div" class="prov-gallery">
                    <article v-for="item in filteredItems" :key="item.id" class="prov-card">
                        <img :src="storageUrl(item.image_path)" :alt="item.title" loading="lazy" />
                        <div class="prov-card__overlay">
                            <span class="prov-card__tag">{{ categoryLabel(item.category) }}</span>
                            <h3 class="prov-card__title">{{ item.title }}</h3>
                        </div>
                    </article>
                </TransitionGroup>
            </div>
        </section>

        <!-- Mini Game -->
        <section id="kuis" class="prov-section">
            <div class="prov-container prov-container--narrow">
                <p class="prov-eyebrow">Uji Wawasanmu</p>
                <h2 class="prov-heading">Kuis Budaya {{ province.name }}</h2>
                <div class="prov-quiz-frame">
                    <iframe
                        class="prov-quiz-frame__iframe"
                        :src="`https://p.interacty.me/${province.mini_game_embed_id}/iframe.html`"
                    ></iframe>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="prov-footer">
            <p>Copyright &copy; 2025 &middot; Nusantara Explorer: Cultural Expedition for Young People</p>
        </footer>
    </div>
</template>

<style scoped>
.prov {
    --ink: #211b17;
    --indigo: #1e3a5f;
    --indigo-deep: #14283f;
    --gold: #d6982e;
    --gold-light: #f0c878;
    --sage: #566d5a;
    --clay: #a23e33;
    --parchment: #f3ead9;
    --parchment-dim: #e8dcc3;

    font-family: 'Work Sans', sans-serif;
    color: var(--ink);
    background: var(--parchment);
    overflow-x: hidden;
}

.prov :is(h1, h2, h3) {
    font-family: 'Fraunces', serif;
    margin: 0;
}

/* Navbar */
.prov-nav {
    position: fixed;
    inset: 0 0 auto 0;
    z-index: 40;
    transition: background-color 0.3s ease, backdrop-filter 0.3s ease;
}
.prov-nav--solid {
    background: rgba(20, 40, 63, 0.92);
    backdrop-filter: blur(8px);
    box-shadow: 0 1px 0 rgba(214, 152, 46, 0.25);
}
.prov-nav__inner {
    max-width: 1180px;
    margin: 0 auto;
    padding: 1.1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.prov-nav__brand {
    font-family: 'Space Mono', monospace;
    font-size: 0.85rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.prov-nav__logo {
    height: 26px;
    width: auto;
}
.prov-nav__toggle {
    display: none;
    flex-direction: column;
    gap: 4px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
}
.prov-nav__toggle span {
    width: 22px;
    height: 2px;
    background: #fff;
}
.prov-nav__links {
    display: flex;
    align-items: center;
    gap: 2rem;
}
.prov-nav__links a {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    font-size: 0.88rem;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    padding-bottom: 2px;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s, color 0.2s;
}
.prov-nav__links a:hover {
    color: var(--gold-light);
    border-color: var(--gold-light);
}
.prov-nav__links a.prov-nav__back {
    display: inline-flex;
    align-items: center;
    line-height: 1;
    gap: 0.4rem;
    background: var(--gold);
    color: var(--indigo-deep);
    border-radius: 999px;
    padding: 0.6rem 1.2rem 0.6rem 1rem;
    font-weight: 600;
    border-bottom: none;
}
.prov-nav__links a.prov-nav__back:hover {
    color: var(--indigo-deep);
    background: var(--gold-light);
}
.prov-nav__back i {
    line-height: 1;
}

/* Hero */
.prov-hero {
    position: relative;
    min-height: 92vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    color: #fff;
    overflow: hidden;
}
.prov-hero__scrim {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(20, 40, 63, 0.55) 0%, rgba(20, 40, 63, 0.55) 40%, rgba(20, 40, 63, 0.92) 100%);
}
.prov-hero__content {
    position: relative;
    max-width: 1180px;
    margin: 0 auto;
    padding: 0 1.5rem;
    width: 100%;
}
.prov-eyebrow {
    font-family: 'Space Mono', monospace;
    font-size: 0.78rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--indigo);
    margin: 0 0 0.75rem;
}
.prov-eyebrow--light {
    color: var(--gold-light);
}
.prov-hero__title {
    font-size: clamp(2.4rem, 6vw, 4.4rem);
    font-weight: 600;
    line-height: 1.05;
    max-width: 16ch;
}
.prov-hero__capital {
    margin-top: 1rem;
    font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.82);
}

.prov-stamp {
    position: absolute;
    right: clamp(1rem, 5vw, 4rem);
    bottom: clamp(1rem, 5vw, 3.5rem);
    width: 128px;
    height: 128px;
    display: grid;
    place-items: center;
    transform: rotate(-8deg);
}
.prov-stamp__ring {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    fill: none;
    stroke: var(--gold-light);
    stroke-width: 2;
    stroke-dasharray: 4 3;
}
.prov-stamp__body {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    color: var(--gold-light);
    font-family: 'Space Mono', monospace;
}
.prov-stamp__no {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1;
}
.prov-stamp__of {
    font-size: 0.62rem;
    letter-spacing: 0.05em;
    margin-top: 0.15rem;
}
.prov-stamp__label {
    font-size: 0.56rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    max-width: 8ch;
    margin-top: 0.2rem;
    opacity: 0.85;
}

/* Sections */
.prov-section {
    padding: clamp(3.5rem, 8vw, 6rem) 0;
}
.prov-section--tinted {
    background: var(--parchment-dim);
}
.prov-container {
    max-width: 1180px;
    margin: 0 auto;
    padding: 0 1.5rem;
}
.prov-container--narrow {
    max-width: 620px;
}
.prov-heading {
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 600;
    margin-bottom: 2rem;
    max-width: 24ch;
}

.prov-grid-2 {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
}
@media (min-width: 900px) {
    .prov-grid-2 {
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
    }
}

.prov-prose {
    line-height: 1.75;
    color: #4a3f36;
}
.prov-prose :deep(h2) {
    font-size: 1.6rem;
    margin-bottom: 0.75rem;
    color: var(--indigo);
}
.prov-prose :deep(h3) {
    font-size: 1.25rem;
    margin: 1.75rem 0 0.5rem;
    color: var(--indigo);
}
.prov-prose :deep(h4) {
    font-family: 'Space Mono', monospace;
    font-size: 0.9rem;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: var(--clay);
    margin: 1.75rem 0 0.5rem;
}
.prov-prose :deep(p) {
    margin: 0 0 1rem;
    text-align: justify !important;
}
.prov-prose :deep(ul) {
    margin: 0 0 1rem;
    padding-left: 1.2rem;
    text-align: justify;
}
.prov-prose :deep(li) {
    text-align: justify !important;
}
.prov-prose :deep(b) {
    color: var(--ink);
}
.prov-prose--intro :deep(p:first-of-type) {
    font-family: 'Fraunces', serif;
    font-size: 1.3rem;
    font-weight: 500;
    color: var(--indigo);
    line-height: 1.4;
}

.prov-history__frames {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 2rem;
}
.prov-frame {
    margin: 0;
    border: 6px solid #fff;
    box-shadow: 0 8px 24px rgba(33, 27, 23, 0.18);
    border-radius: 2px;
    overflow: hidden;
    aspect-ratio: 4 / 3;
}
.prov-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.prov-frame--wide {
    margin-top: 1.5rem;
    aspect-ratio: 16 / 9;
}

/* Filters */
.prov-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin-bottom: 2.5rem;
}
.prov-filter {
    font-family: 'Space Mono', monospace;
    font-size: 0.82rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1.1rem;
    border-radius: 999px;
    border: 1px solid rgba(33, 27, 23, 0.2);
    background: transparent;
    color: var(--ink);
    cursor: pointer;
    transition: all 0.2s ease;
}
.prov-filter:hover {
    border-color: var(--indigo);
}
.prov-filter--active {
    background: var(--indigo);
    border-color: var(--indigo);
    color: #fff;
}

/* Gallery */
.prov-gallery {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}
@media (min-width: 640px) {
    .prov-gallery {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (min-width: 960px) {
    .prov-gallery {
        grid-template-columns: repeat(4, 1fr);
    }
}
.prov-card {
    position: relative;
    aspect-ratio: 3 / 4;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(33, 27, 23, 0.14);
    transition: transform 0.25s ease;
}
.prov-card:hover {
    transform: translateY(-4px);
}
.prov-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.prov-card__overlay {
    position: absolute;
    inset: auto 0 0 0;
    padding: 0.9rem 0.85rem;
    background: linear-gradient(0deg, rgba(20, 40, 63, 0.92) 10%, rgba(20, 40, 63, 0) 100%);
    color: #fff;
}
.prov-card__tag {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--gold-light);
}
.prov-card__title {
    font-size: 0.92rem;
    font-weight: 600;
    margin: 0.15rem 0 0;
    line-height: 1.25;
}
.prov-card-move,
.prov-card-enter-active,
.prov-card-leave-active {
    transition: all 0.25s ease;
}
.prov-card-enter-from,
.prov-card-leave-to {
    opacity: 0;
    transform: scale(0.92);
}
.prov-card-leave-active {
    position: absolute;
}

/* Quiz */
.prov-quiz-frame {
    border: 1px solid rgba(33, 27, 23, 0.15);
    border-radius: 12px;
    padding: 0.5rem;
    background: #fff;
    box-shadow: 0 10px 30px rgba(33, 27, 23, 0.12);
}
.prov-quiz-frame__iframe {
    width: 100%;
    height: 460px;
    border: none;
    border-radius: 6px;
    display: block;
}

/* Footer */
.prov-footer {
    background: var(--indigo-deep);
    color: rgba(255, 255, 255, 0.7);
    text-align: center;
    padding: 1.75rem 1.5rem;
    font-size: 0.85rem;
}

@media (max-width: 767px) {
    .prov-nav__toggle {
        display: flex;
    }
    .prov-nav__links {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(20, 40, 63, 0.97);
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        display: none;
    }
    .prov-nav__links--open {
        display: flex;
    }
    .prov-history__frames {
        grid-template-columns: 1fr;
    }
    .prov-stamp {
        width: 96px;
        height: 96px;
        right: 1rem;
        bottom: 1rem;
    }
    .prov-stamp__no {
        font-size: 1.2rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .prov-card,
    .prov-nav,
    .prov-card-move,
    .prov-card-enter-active,
    .prov-card-leave-active {
        transition: none !important;
    }
}
</style>
