<template>
    <div class="divine-container">
        <!-- Halo/Aureola behind text -->
        <div class="halo"></div>
        <div class="halo-outer"></div>
        
        <!-- Angel Wings -->
        <div class="wings-container">   
            <div class="wing left-wing">
                <div class="feather" v-for="n in 7" :key="'l'+n"></div>
            </div>
            <div class="wing right-wing">
                <div class="feather" v-for="n in 7" :key="'r'+n"></div>
            </div>
        </div>
        
        <!-- Main Text with Divine Glow -->
        <h1 class="divine-text">
            <span class="letter" v-for="(char, index) in 'SALE'" :key="index" :style="{ animationDelay: index * 0.2 + 's' }">
                <span class="letter-inner">{{ char }}</span>
                <span class="letter-glow"></span>
            </span>
        </h1>
        
        <!-- Divine Rays -->
        <div class="rays-container">
            <div class="ray" v-for="n in 12" :key="n" :style="{ '--ray-delay': n * 0.3 + 's', '--ray-rotation': (n * 30) + 'deg' }"></div>
        </div>
        
        <!-- Sacred Geometry -->
        <div class="sacred-geometry">
            <div class="circle-outer"></div>
            <div class="circle-middle"></div>
            <div class="circle-inner"></div>
            <div class="cross-vertical"></div>
            <div class="cross-horizontal"></div>
        </div>
        
        <!-- Light Particles (Divine Sparkles) -->
        <div class="divine-particles">
            <span v-for="n in 30" :key="n" class="sparkle" :style="{ 
                '--delay': Math.random() * 3 + 's',
                '--x': Math.random() * 100 + '%',
                '--y': Math.random() * 100 + '%',
                '--size': Math.random() * 8 + 4 + 'px'
            }"></span>
        </div>
        
        <!-- Floating Holy Orbs -->
        <div class="holy-orb" v-for="n in 5" :key="'orb'+n" :style="{ 
            '--orb-delay': n * 0.8 + 's',
            '--orb-x': (n * 20 + 10) + '%',
            '--orb-y': (n * 15 + 10) + '%'
        }"></div>
    </div>
</template>

<script setup>
</script>

<style scoped>
.divine-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100%;
    background: radial-gradient(ellipse at center, #1a1a2e 0%, #0d0d1a 50%, #000000 100%);
    position: relative;
    overflow: hidden;
}

/* === HALO / AUREOLA === */
.halo {
    position: absolute;
    width: 600px;
    height: 150px;
    background: radial-gradient(ellipse at center, rgba(255, 215, 0, 0.6) 0%, rgba(255, 215, 0, 0.3) 30%, transparent 70%);
    border-radius: 50%;
    top: 25%;
    left: 50%;
    transform: translateX(-50%);
    animation: halo-pulse 2s ease-in-out infinite, halo-rotate 10s linear infinite;
    z-index: 1;
}

.halo-outer {
    position: absolute;
    width: 800px;
    height: 200px;
    background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.3) 0%, rgba(255, 215, 0, 0.2) 40%, transparent 70%);
    border-radius: 50%;
    top: 22%;
    left: 50%;
    transform: translateX(-50%);
    animation: halo-pulse 2.5s ease-in-out infinite reverse;
    z-index: 0;
}

@keyframes halo-pulse {
    0%, 100% { opacity: 0.6; transform: translateX(-50%) scale(1); }
    50% { opacity: 1; transform: translateX(-50%) scale(1.1); }
}

@keyframes halo-rotate {
    0% { filter: hue-rotate(0deg); }
    100% { filter: hue-rotate(360deg); }
}

/* === ANGEL WINGS === */
.wings-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 1200px;
    display: flex;
    justify-content: space-between;
    z-index: 0;
    pointer-events: none;
}

.wing {
    display: flex;
    gap: 8px;
    animation: wing-flutter 3s ease-in-out infinite;
}

.left-wing {
    transform: scaleX(-1);
}

.right-wing {
    animation-delay: 0.1s;
}

.feather {
    width: 60px;
    height: 200px;
    background: linear-gradient(to top, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.8) 50%, rgba(255, 215, 0, 0.3) 100%);
    border-radius: 50% 50% 20% 20%;
    transform-origin: bottom center;
    animation: feather-wave 2s ease-in-out infinite;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.3), 0 0 40px rgba(255, 215, 0, 0.2);
}

.feather:nth-child(1) { height: 180px; animation-delay: 0s; }
.feather:nth-child(2) { height: 220px; animation-delay: 0.1s; }
.feather:nth-child(3) { height: 250px; animation-delay: 0.2s; }
.feather:nth-child(4) { height: 260px; animation-delay: 0.3s; }
.feather:nth-child(5) { height: 250px; animation-delay: 0.4s; }
.feather:nth-child(6) { height: 220px; animation-delay: 0.5s; }
.feather:nth-child(7) { height: 180px; animation-delay: 0.6s; }

@keyframes wing-flutter {
    0%, 100% { transform: scaleX(-1) translateY(0); }
    50% { transform: scaleX(-1) translateY(-10px); }
}

@keyframes feather-wave {
    0%, 100% { transform: rotate(-5deg); }
    50% { transform: rotate(5deg); }
}

/* === DIVINE TEXT === */
.divine-text {
    font-size: 14rem;
    font-weight: 900;
    display: flex;
    gap: 30px;
    z-index: 10;
    position: relative;
    margin: 0;
}

.letter {
    position: relative;
    display: inline-block;
    animation: divine-appear 2s ease-in-out infinite;
}

.letter-inner {
    display: block;
    background: linear-gradient(180deg, #fff 0%, #ffd700 30%, #ff8c00 60%, #ffd700 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: none;
    filter: drop-shadow(0 0 30px rgba(255, 215, 0, 0.9)) drop-shadow(0 0 60px rgba(255, 215, 0, 0.6));
}

.letter-glow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.8) 0%, transparent 70%);
    border-radius: 50%;
    animation: glow-pulse 1.5s ease-in-out infinite;
    z-index: -1;
}

@keyframes divine-appear {
    0%, 100% {
        transform: translateY(0) scale(1) rotate(0deg);
        filter: drop-shadow(0 0 30px rgba(255, 215, 0, 0.8));
    }
    25% {
        transform: translateY(-40px) scale(1.1) rotate(-3deg);
        filter: drop-shadow(0 0 50px rgba(255, 255, 255, 0.9));
    }
    50% {
        transform: translateY(-60px) scale(1.15) rotate(0deg);
        filter: drop-shadow(0 0 70px rgba(255, 215, 0, 1));
    }
    75% {
        transform: translateY(-40px) scale(1.1) rotate(3deg);
        filter: drop-shadow(0 0 50px rgba(255, 255, 255, 0.9));
    }
}

@keyframes glow-pulse {
    0%, 100% { opacity: 0.5; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.2); }
}

/* === DIVINE RAYS === */
.rays-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 1000px;
    height: 1000px;
    pointer-events: none;
    z-index: 0;
}

.ray {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 4px;
    height: 500px;
    background: linear-gradient(to top, transparent 0%, rgba(255, 215, 0, 0.6) 50%, rgba(255, 255, 255, 0.8) 100%);
    transform-origin: bottom center;
    animation: ray-rotate 12s linear infinite;
    animation-delay: var(--ray-delay);
}

@keyframes ray-rotate {
    0% { transform: rotate(var(--ray-rotation)) translateY(0); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: rotate(calc(var(--ray-rotation) + 360deg)) translateY(0); opacity: 0; }
}

/* === SACRED GEOMETRY === */
.sacred-geometry {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 600px;
    height: 600px;
    pointer-events: none;
    z-index: 0;
    animation: sacred-rotate 30s linear infinite;
}

.circle-outer, .circle-middle, .circle-inner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50%;
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.circle-outer {
    width: 600px;
    height: 600px;
    animation: circle-pulse 4s ease-in-out infinite;
}

.circle-middle {
    width: 400px;
    height: 400px;
    border-color: rgba(255, 255, 255, 0.3);
    animation: circle-pulse 4s ease-in-out infinite 0.5s;
}

.circle-inner {
    width: 200px;
    height: 200px;
    border-color: rgba(255, 215, 0, 0.5);
    animation: circle-pulse 4s ease-in-out infinite 1s;
}

.cross-vertical, .cross-horizontal {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(to bottom, transparent 0%, rgba(255, 215, 0, 0.4) 50%, transparent 100%);
}

.cross-vertical {
    width: 2px;
    height: 600px;
}

.cross-horizontal {
    width: 600px;
    height: 2px;
    transform: translate(-50%, -50%) rotate(90deg);
}

@keyframes sacred-rotate {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

@keyframes circle-pulse {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.8; }
}

/* === DIVINE SPARKLES === */
.divine-particles {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
    z-index: 5;
}

.sparkle {
    position: absolute;
    width: var(--size);
    height: var(--size);
    left: var(--x);
    top: var(--y);
    background: radial-gradient(circle, #fff 0%, #ffd700 50%, transparent 100%);
    border-radius: 50%;
    animation: sparkle-float var(--delay) ease-in-out infinite;
}

@keyframes sparkle-float {
    0%, 100% { 
        transform: scale(0) translateY(0); 
        opacity: 0; 
    }
    25% { 
        transform: scale(1) translateY(-20px); 
        opacity: 1; 
    }
    50% { 
        transform: scale(0.5) translateY(-40px); 
        opacity: 0.8; 
    }
    75% { 
        transform: scale(1.2) translateY(-60px); 
        opacity: 0.6; 
    }
}

/* === HOLY ORBS === */
.holy-orb {
    position: absolute;
    width: 30px;
    height: 30px;
    left: var(--orb-x);
    top: var(--orb-y);
    background: radial-gradient(circle, #fff 0%, #ffd700 40%, transparent 70%);
    border-radius: 50%;
    animation: holy-orb-float var(--orb-delay) ease-in-out infinite;
    box-shadow: 0 0 20px #ffd700, 0 0 40px #ffa500;
}

@keyframes holy-orb-float {
    0%, 100% { 
        transform: translate(0, 0) scale(1); 
        opacity: 0.6;
    }
    50% { 
        transform: translate(30px, -50px) scale(1.5); 
        opacity: 1;
    }
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .divine-text {
        font-size: 5rem;
        gap: 15px;
    }
    
    .halo, .halo-outer {
        width: 300px;
        height: 80px;
    }
    
    .wing {
        gap: 4px;
    }
    
    .feather {
        width: 30px;
        height: 100px;
    }
    
    .sacred-geometry {
        width: 300px;
        height: 300px;
    }
    
    .circle-outer { width: 300px; height: 300px; }
    .circle-middle { width: 200px; height: 200px; }
    .circle-inner { width: 100px; height: 100px; }
}
</style>
