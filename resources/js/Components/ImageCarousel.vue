<script setup>
// Import images directly
const track1Images = [
    '/resources/assets/1.jpg',
    '/resources/assets/2.jpg',
    '/resources/assets/3.gif',
    '/resources/assets/4.gif',
    '/resources/assets/5.gif',
    '/resources/assets/6.gif',
    '/resources/assets/7.gif',
    '/resources/assets/8.gif',
    '/resources/assets/21.jpg',
    '/resources/assets/29.gif'
];

const track2Images = [
    '/resources/assets/2111.gif',
    '/resources/assets/running girl.gif',
    '/resources/assets/swimming loop.gif',
    '/resources/assets/Winning.gif',
    '/resources/assets/Salah Animation Gif.gif',
    '/resources/assets/PingPong.gif',
    '/resources/assets/2123.gif',
    '/resources/assets/3.gif',
    '/resources/assets/5.gif',
    '/resources/assets/7.gif'
];
</script>

<template>
    <div class="carousel-wrapper">
        <div class="carousel-container flex gap-4 h-full overflow-hidden relative">
            <!-- First Track -->
            <div class="carousel-track animate-slide-1" style="will-change: transform; transform: translateZ(0); backface-visibility: hidden;">
                <template v-for="(image, index) in track1Images" :key="'track1-'+index">
                    <div class="carousel-item p-2">
                        <img :src="image" loading="lazy" class="w-20 h-20 rounded-lg shadow-lg object-cover" :alt="'Sports image '+index">
                    </div>
                    <!-- Duplicate first few items for seamless loop -->
                    <div v-if="index < 3" class="carousel-item p-2">
                        <img :src="image" loading="lazy" class="w-20 h-20 rounded-lg shadow-lg object-cover" :alt="'Sports image '+index">
                    </div>
                </template>
            </div>

            <!-- Second Track -->
            <div class="carousel-track animate-slide-2" style="will-change: transform; transform: translateZ(0); backface-visibility: hidden;">
                <template v-for="(image, index) in track2Images" :key="'track2-'+index">
                    <div class="carousel-item p-2">
                        <img :src="image" loading="lazy" class="w-20 h-20 rounded-lg shadow-lg object-cover" :alt="'Sports image '+index">
                    </div>
                    <!-- Duplicate first few items for seamless loop -->
                    <div v-if="index < 3" class="carousel-item p-2">
                        <img :src="image" loading="lazy" class="w-20 h-20 rounded-lg shadow-lg object-cover" :alt="'Sports image '+index">
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>
.carousel-wrapper {
    height: 400px;
    width: 200px;
    position: relative;
    contain: content;
    overflow: hidden;
}

.carousel-container {
    height: 100%;
    width: 100%;
    contain: content;
}

.carousel-track {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    height: fit-content;
    contain: content;
    transform: translateZ(0);
    will-change: transform;
    backface-visibility: hidden;
}

@keyframes slideVertical {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-50%);
    }
}

.animate-slide-1 {
    animation: slideVertical 30s linear infinite;
}

.animate-slide-2 {
    animation: slideVertical 30s linear infinite reverse;
}

@media (prefers-reduced-motion: reduce) {
    .animate-slide-1,
    .animate-slide-2 {
        animation-duration: 60s;
    }
}

.carousel-track:hover {
    animation-play-state: paused;
}

.carousel-item img {
    transform: translateZ(0);
    will-change: transform;
}
</style>
