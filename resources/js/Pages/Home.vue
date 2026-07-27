<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    teamMembers: Array,
    homeStats: Array,
});

const storageUrl = (path) => (path ? `/storage/${path}` : '');

/* Back to top button */
const showBackToTop = ref(false);
const onScroll = () => {
    showBackToTop.value = window.scrollY > 300;
};
const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });

/* Animated stat counters, triggered once each stat card scrolls into view */
const statValues = ref(props.homeStats.map(() => 0));
const statRefs = ref([]);
const animateCounter = (index, target) => {
    const duration = 2000;
    const start = performance.now();
    const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        statValues.value[index] = Math.floor(target * progress);
        if (progress < 1) {
            requestAnimationFrame(step);
        } else {
            statValues.value[index] = target;
        }
    };
    requestAnimationFrame(step);
};

let statObserver;
onMounted(() => {
    window.addEventListener('scroll', onScroll);

    statObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const index = Number(entry.target.dataset.statIndex);
                    const target = parseInt(props.homeStats[index].value.replace(/[^0-9]/g, ''), 10);
                    animateCounter(index, target);
                    statObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 },
    );
    statRefs.value.forEach((el) => el && statObserver.observe(el));
});
onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
    statObserver?.disconnect();
});

const formattedStatValue = (index) => statValues.value[index].toLocaleString('en-US');

/* Team testimonial carousel, native Vue (no jQuery/OwlCarousel) */
const itemsPerView = ref(window.innerWidth >= 768 ? 2 : 1);
const updateItemsPerView = () => {
    itemsPerView.value = window.innerWidth >= 768 ? 2 : 1;
};
window.addEventListener('resize', updateItemsPerView);

const teamIndex = ref(0);
const maxTeamIndex = computed(() => Math.max(props.teamMembers.length - itemsPerView.value, 0));
const visibleTeamMembers = computed(() =>
    props.teamMembers.slice(teamIndex.value, teamIndex.value + itemsPerView.value),
);
const nextTeam = () => {
    teamIndex.value = teamIndex.value >= maxTeamIndex.value ? 0 : teamIndex.value + 1;
};
const prevTeam = () => {
    teamIndex.value = teamIndex.value <= 0 ? maxTeamIndex.value : teamIndex.value - 1;
};

/* Hero carousel, native Vue (no Bootstrap JS bundle needed) */
const heroSlides = [
    { image: 'home/home-page-1.png', eyebrow: 'Digital Tour Guide', title: 'Exploring Indonesia' },
    { image: 'home/home-page-2.png', eyebrow: 'Nusantara Explorer', subtitle: 'Cultural Expedition For Young People', title: 'Honoring Heritage' },
    { image: 'home/home-page-3.png', eyebrow: 'Nusantara Explorer', subtitle: 'Cultural Expedition For Young People', title: 'Embracing Traditions' },
];
const heroIndex = ref(0);
let heroTimer;
onMounted(() => {
    heroTimer = setInterval(() => {
        heroIndex.value = (heroIndex.value + 1) % heroSlides.length;
    }, 3000);
});
onUnmounted(() => clearInterval(heroTimer));

const heroSlideClass = (index) => (index === heroIndex.value ? 'active' : '');

/* Team carousel auto-advance */
let teamTimer;
onMounted(() => {
    teamTimer = setInterval(nextTeam, 3000);
});
onUnmounted(() => clearInterval(teamTimer));
</script>

<template>
    <Head>
        <title>Nusantara Explorer</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="/legacy/home/bootstrap.min.css" rel="stylesheet" />
        <link href="/legacy/home/style.css" rel="stylesheet" />
    </Head>

    <div class="container-xxl bg-white p-0">
        <!-- Carousel -->
        <div class="container-fluid p-0 mb-5">
            <div id="header-carousel" class="carousel slide">
                <div class="carousel-inner">
                    <div
                        v-for="(slide, index) in heroSlides"
                        :key="slide.image"
                        class="carousel-item"
                        :class="heroSlideClass(index)"
                    >
                        <img :src="storageUrl(slide.image)" :alt="slide.title" style="width: 100vw; height: 100vh; object-fit: cover" />
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px">
                                <h6 v-if="slide.subtitle" class="section-title text-white text-uppercase mb-3">{{ slide.eyebrow }}</h6>
                                <h4 v-else class="section-title text-white text-uppercase mb-3">{{ slide.eyebrow }}</h4>
                                <br />
                                <h6 v-if="slide.subtitle" class="text-white">{{ slide.subtitle }}</h6>
                                <h1 class="display-3 text-white mb-4">{{ slide.title }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <h1>Welcome to <span class="text-primary text-uppercase"><br />Nusantara Explorer</span></h1>
                        <p class="mb-4" style="text-align: justify">
                            Nusantara Explorer is here for you, Indonesia's youth! Uncover the cultural treasures from
                            Sabang to Merauke through a captivating interactive map. Delve into history, experience
                            the melodies of traditional music, marvel at the architecture of traditional homes,
                            immerse yourself in legendary folklore, and test your knowledge with interactive quizzes.
                            With Nusantara Explorer, preserve Indonesian heritage in a modern and enjoyable way!
                        </p>
                        <div class="row g-3 pb-4">
                            <div
                                v-for="(stat, index) in homeStats"
                                :key="stat.id"
                                class="col-sm-4"
                                :ref="(el) => (statRefs[index] = el)"
                                :data-stat-index="index"
                            >
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <img
                                            v-if="!stat.icon_path?.startsWith('fa ')"
                                            :src="storageUrl(stat.icon_path)"
                                            width="36"
                                            height="36"
                                            alt=""
                                        />
                                        <i v-else :class="[stat.icon_path, 'fa-2x text-primary mb-2']"></i>
                                        <h2 class="mb-1">{{ formattedStatValue(index) }}</h2>
                                        <b class="mb-0">{{ stat.label }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Link class="btn btn-primary py-3 px-5 mt-2" style="color: white; font-weight: bold" href="/islands">
                            Explore More
                        </Link>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3 align-items-end">
                            <div class="col-6 text-end">
                                <img
                                    class="img-fluid rounded w-75"
                                    src="/storage/home/about-1.png"
                                    alt="Traditional Indonesian House"
                                />
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100" src="/storage/home/about-2.png" alt="Indonesian Cultural Performance" />
                            </div>
                        </div>
                        <div class="row g-3 align-items-start mt-3">
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-50" src="/storage/home/about-3.png" alt="Indonesian Landscape" />
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75" src="/storage/home/about-4.png" alt="Indonesian Traditional Art" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Team -->
        <div class="container-xxl testimonial my-5 py-5 bg-dark">
            <div class="container">
                <div class="text-center">
                    <h6 class="section-title text-center text-primary text-uppercase">About Us</h6>
                    <h1 class="mb-5" style="color: white">INTRODUCTION <span class="text-primary text-uppercase">Our Team</span></h1>
                </div>

                <div class="d-flex align-items-center justify-content-center gap-3 py-3">
                    <button class="btn btn-outline-light rounded-circle" @click="prevTeam"><i class="bi bi-arrow-left"></i></button>

                    <Transition name="team-fade" mode="out-in">
                        <div :key="teamIndex" class="d-flex gap-4 flex-grow-1 justify-content-center">
                            <div
                                v-for="member in visibleTeamMembers"
                                :key="member.id"
                                class="testimonial-item position-relative bg-white rounded overflow-hidden p-4"
                                style="max-width: 420px"
                            >
                                <p>{{ member.quote }}</p>
                                <div class="d-flex align-items-center">
                                    <img class="img-fluid flex-shrink-0 rounded" :src="storageUrl(member.photo_path)" style="width: 45px; height: 45px" />
                                    <div class="ps-3">
                                        <h6 class="fw-bold mb-1">{{ member.name }}</h6>
                                        <small>{{ member.role }}</small>
                                    </div>
                                </div>
                                <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
                            </div>
                        </div>
                    </Transition>

                    <button class="btn btn-outline-light rounded-circle" @click="nextTeam"><i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <p class="tm-copyright-text">Copyright &copy; 2025 | Nusantara Explorer: Cultural Expedition for Young People</p>
                </div>
            </div>
        </footer>

        <!-- Back to Top -->
        <a
            href="#"
            class="btn btn-lg btn-primary btn-lg-square back-to-top"
            :style="{ display: showBackToTop ? 'flex' : 'none' }"
            @click.prevent="scrollToTop"
        >
            <i class="bi bi-arrow-up"></i>
        </a>
    </div>
</template>

<style scoped>
/* Hero carousel: simple crossfade driven by heroIndex */
#header-carousel {
    height: 100vh;
}
#header-carousel .carousel-item {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    display: block !important;
    opacity: 0;
    transition: opacity 0.9s ease-in-out;
    z-index: 1;
}
#header-carousel .carousel-item.active {
    opacity: 1;
    z-index: 2;
}
#header-carousel .carousel-inner {
    position: relative;
    height: 100%;
    overflow: hidden;
}

/* Team carousel: simple crossfade between card groups */
.team-fade-enter-active,
.team-fade-leave-active {
    transition: opacity 0.4s ease;
}
.team-fade-enter-from,
.team-fade-leave-to {
    opacity: 0;
}

@media (prefers-reduced-motion: reduce) {
    #header-carousel .carousel-item,
    .team-fade-enter-active,
    .team-fade-leave-active {
        transition: none !important;
    }
}
</style>
