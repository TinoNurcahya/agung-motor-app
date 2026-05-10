/**
 * Main UI Logic for Agung Motor App
 * Handles: Mobile Menu, Scroll Navbar, User Dropdown, and Theme Toggle
 */

document.addEventListener("DOMContentLoaded", () => {
    // 0. Initialize Lenis Smooth Scroll Globally
    if (typeof Lenis !== 'undefined') {
        const lenis = new Lenis({
            duration: 1.5,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            orientation: "vertical",
            gestureOrientation: "vertical",
            smoothWheel: true,
            wheelMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
            infinite: false,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);

        // Connect Lenis to ScrollTrigger if available
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            lenis.on("scroll", ScrollTrigger.update);
            gsap.ticker.add((time) => {
                lenis.raf(time * 1000);
            });
            gsap.ticker.lagSmoothing(0);
        }
    }

    // Mobile menu toggle
    const mobToggle = document.getElementById("mob-toggle");
    const mobMenu = document.getElementById("mob-menu");
    const navbar = document.getElementById("navbar");

    if (mobToggle && mobMenu) {
        mobToggle.addEventListener("click", () => {
            const isOpen = !mobMenu.classList.contains("hidden");
            mobMenu.classList.toggle("hidden");

            // Toggle icon bars to xmark
            const icon = mobToggle.querySelector("i");
            if (isOpen) {
                icon.classList.replace("fa-xmark", "fa-bars");
            } else {
                icon.classList.replace("fa-bars", "fa-xmark");
            }
        });
    }

    // Scroll effect for navbar
    window.addEventListener("scroll", () => {
        if (window.scrollY > 20) {
            navbar?.classList.add("py-2", "shadow-lg");
        } else {
            navbar?.classList.remove("py-2", "shadow-lg");
        }
    });

    // User Dropdown toggle
    const userMenuButton = document.getElementById("user-menu-button");
    const userDropdownMenu = document.getElementById("user-dropdown-menu");
    const userDropdownWrapper = document.getElementById("user-dropdown-wrapper");

    if (userMenuButton && userDropdownMenu) {
        userMenuButton.addEventListener("click", (e) => {
            e.stopPropagation();
            userDropdownMenu.classList.toggle("hidden");
        });

        document.addEventListener("click", (e) => {
            if (!userDropdownMenu.classList.contains("hidden")) {
                if (
                    userDropdownWrapper &&
                    !userDropdownWrapper.contains(e.target)
                ) {
                    userDropdownMenu.classList.add("hidden");
                }
            }
        });
    }

    // Theme Toggle Logic
    const themeToggle = document.getElementById("theme-toggle");
    const html = document.documentElement;
    const themeIconDark = document.getElementById("theme-icon-dark");
    const themeIconLight = document.getElementById("theme-icon-light");

    function updateThemeUI(theme) {
        if (theme === "light") {
            html.classList.add("light");
            if (themeIconDark) themeIconDark.classList.add("hidden");
            if (themeIconLight) themeIconLight.classList.remove("hidden");
        } else {
            html.classList.remove("light");
            if (themeIconDark) themeIconDark.classList.remove("hidden");
            if (themeIconLight) themeIconLight.classList.add("hidden");
        }
    }

    const savedTheme = localStorage.getItem("theme") || "dark";
    updateThemeUI(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            const isLight = html.classList.contains("light");
            const newTheme = isLight ? "dark" : "light";
            localStorage.setItem("theme", newTheme);
            updateThemeUI(newTheme);
        });
    }
});
