/**
 * Cinematic About Page Animations
 */

document.addEventListener("DOMContentLoaded", () => {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // 1. Hero Reveal (SplitType)
    if (typeof SplitType !== 'undefined') {
        const heroTitle = new SplitType("#hero-title", { types: "words,chars" });
        
        gsap.from(heroTitle.chars, {
            opacity: 0,
            y: 50,
            rotateX: -45,
            duration: 1.5,
            stagger: 0.03,
            ease: "expo.out",
            delay: 0.2
        });

        gsap.from("#hero-subtitle", {
            opacity: 0,
            y: 20,
            duration: 1.5,
            ease: "power2.out",
            delay: 1.2
        });
    }

    // 2. Storytelling Fade Up
    const storyElements = document.querySelectorAll(".story-reveal");
    storyElements.forEach((el) => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: "top 85%",
            },
            opacity: 0,
            y: 40,
            duration: 1.2,
            ease: "power3.out"
        });
    });

    // 3. Image Parallax Effect
    const parallaxImages = document.querySelectorAll(".parallax-inner");
    parallaxImages.forEach((img) => {
        gsap.to(img, {
            scrollTrigger: {
                trigger: img.parentElement,
                start: "top bottom",
                end: "bottom top",
                scrub: true
            },
            yPercent: 20,
            ease: "none"
        });
    });

    // 4. Statistics Stagger Reveal
    const stats = document.querySelectorAll(".stat-card");
    if(stats.length > 0) {
        gsap.from(stats, {
            scrollTrigger: {
                trigger: "#stats-container",
                start: "top 85%",
            },
            opacity: 0,
            y: 50,
            duration: 1,
            stagger: 0.15,
            ease: "back.out(1.2)"
        });
    }

    // 5. Team Card Hover Effect (Custom handling if needed, though mostly handled by Tailwind)
    const teamCards = document.querySelectorAll(".team-card");
    if(teamCards.length > 0) {
        gsap.from(teamCards, {
            scrollTrigger: {
                trigger: "#team-container",
                start: "top 85%",
            },
            opacity: 0,
            y: 60,
            duration: 1.2,
            stagger: 0.2,
            ease: "power4.out"
        });
    }

    // 6. Glow Line Expansion
    const glowLines = document.querySelectorAll(".glow-line");
    glowLines.forEach((line) => {
        gsap.from(line, {
            scrollTrigger: {
                trigger: line,
                start: "top 80%",
            },
            scaleY: 0,
            transformOrigin: "top center",
            duration: 1.5,
            ease: "power3.inOut"
        });
    });
});
