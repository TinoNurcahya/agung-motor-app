/**
 * Generic Page Animations for Agung Motor internal pages
 */

document.addEventListener("DOMContentLoaded", () => {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    // Register GSAP Plugins
    gsap.registerPlugin(ScrollTrigger);

    // 1. Text Reveal Animation (SplitType)
    const splitTypes = document.querySelectorAll(".reveal-text");
    if (typeof SplitType !== 'undefined') {
        splitTypes.forEach((element) => {
            const text = new SplitType(element, { types: "words,chars" });

            gsap.from(text.chars, {
                scrollTrigger: {
                    trigger: element,
                    start: "top 90%",
                    toggleActions: "play none none none",
                },
                opacity: 0,
                y: 50,
                rotateX: -45,
                duration: 1.2,
                stagger: 0.02,
                ease: "expo.out",
            });
        });
    }

    // 2. Generic Card Reveal
    const serviceCards = document.querySelectorAll(".glass-card");
    serviceCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top 90%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 1,
            delay: (index % 3) * 0.15, // Stagger effect per row
            ease: "power3.out",
        });
    });

    // 3. Fade-in for sub-content
    const fadeElements = document.querySelectorAll(".text-muted");
    fadeElements.forEach((el) => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: "top 95%",
            },
            opacity: 0,
            y: 20,
            duration: 1,
            ease: "power2.out",
            delay: 0.2
        });
    });
});
