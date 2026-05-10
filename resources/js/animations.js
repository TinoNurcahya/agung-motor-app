/**
 * Cinematic GSAP + SplitType + Lenis Animations for Agung Motor
 */

document.addEventListener("DOMContentLoaded", () => {
    // Register GSAP Plugins
    gsap.registerPlugin(ScrollTrigger);

    // 1. Cinematic Hero Parallax
    gsap.to("#hero-image", {
        yPercent: 20,
        ease: "none",
        scrollTrigger: {
            trigger: "#hero",
            start: "top top",
            end: "bottom top",
            scrub: true
        }
    });

    // 2. Text Reveal Animation (SplitType)
    const splitTypes = document.querySelectorAll(".reveal-text");
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

    // 3. Cinematic Card Reveal
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

    // 4. Feature Image Parallax
    const parallaxImg = document.querySelector("#parallax-img");
    if (parallaxImg) {
        gsap.to(parallaxImg, {
            yPercent: -20,
            ease: "none",
            scrollTrigger: {
                trigger: "#parallax-container",
                start: "top bottom",
                end: "bottom top",
                scrub: true
            }
        });
    }

    // 5. Fade-in for sub-content
    const fadeElements = document.querySelectorAll(".text-muted, #hero-actions, .hero-bg p");
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

    // 6. Stats Animation
    const stats = document.querySelectorAll(".py-40 .text-4xl");
    stats.forEach((stat) => {
        const value = parseInt(stat.innerText.replace(/\D/g,''));
        const suffix = stat.innerText.replace(/[0-9]/g, '');
        
        gsap.from(stat, {
            scrollTrigger: {
                trigger: stat,
                start: "top 90%",
            },
            textContent: 0,
            duration: 2,
            ease: "power2.out",
            onUpdate: function() {
                this.targets()[0].innerText = Math.ceil(this.targets()[0].textContent) + suffix;
            }
        });
    });
});
