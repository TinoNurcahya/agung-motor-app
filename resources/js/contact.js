/**
 * Cinematic Contact Page Animations
 */

document.addEventListener("DOMContentLoaded", () => {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // 1. Hero Reveal (SplitType)
    if (typeof SplitType !== 'undefined') {
        const contactTitle = new SplitType("#contact-title", { types: "words,chars" });
        
        gsap.from(contactTitle.chars, {
            opacity: 0,
            y: 50,
            rotateX: -45,
            duration: 1.5,
            stagger: 0.03,
            ease: "expo.out",
            delay: 0.2
        });

        gsap.from("#contact-subtitle", {
            opacity: 0,
            y: 20,
            duration: 1.5,
            ease: "power2.out",
            delay: 1.2
        });
    }

    // 2. Info Cards Reveal
    const infoCards = document.querySelectorAll(".contact-card");
    if(infoCards.length > 0) {
        gsap.from(infoCards, {
            scrollTrigger: {
                trigger: "#contact-info-container",
                start: "top 80%",
            },
            opacity: 0,
            x: window.innerWidth < 640 ? 0 : -50,
            duration: 1.2,
            stagger: 0.15,
            ease: "power3.out"
        });
    }

    // 3. Map Reveal & Subtle Parallax
    const mapWrap = document.querySelector(".map-wrap");
    if(mapWrap) {
        gsap.from(mapWrap, {
            scrollTrigger: {
                trigger: mapWrap,
                start: "top 85%",
            },
            opacity: 0,
            scale: 0.95,
            duration: 1.5,
            ease: "power4.out"
        });

        // Subtle map container parallax
        gsap.to(mapWrap, {
            scrollTrigger: {
                trigger: "#contact-section",
                start: "top bottom",
                end: "bottom top",
                scrub: 1
            },
            y: -30,
            ease: "none"
        });
    }

    // 4. CTA Reveal
    const ctaWrap = document.querySelector("#contact-cta");
    if(ctaWrap) {
        gsap.from(ctaWrap, {
            scrollTrigger: {
                trigger: "#contact-info-container",
                start: "top 70%",
            },
            opacity: 0,
            y: 30,
            duration: 1.2,
            delay: 0.6,
            ease: "back.out(1.5)"
        });
    }
});
