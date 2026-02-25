/**
 * Main Frontend JavaScript
 * News/Blog CMS - Vanilla JS, no dependencies
 */
document.addEventListener('DOMContentLoaded', () => {

    /* -------------------------------------------------------
     * a) Mobile Navigation Toggle
     * ----------------------------------------------------- */
    const navToggle = document.querySelector('.nav-toggle');
    const navOverlay = document.querySelector('.nav-overlay');
    const navClose = document.querySelector('.nav-overlay__close');
    const navBackdrop = document.querySelector('.nav-overlay__backdrop');

    function openMobileNav() {
        navToggle.classList.add('active');
        navOverlay.classList.add('active');
        navToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
        navToggle.classList.remove('active');
        navOverlay.classList.remove('active');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    if (navToggle && navOverlay) {
        navToggle.addEventListener('click', () => {
            const isOpen = navToggle.classList.contains('active');
            isOpen ? closeMobileNav() : openMobileNav();
        });

        // Close button inside the panel
        if (navClose) {
            navClose.addEventListener('click', closeMobileNav);
        }

        // Close on backdrop click
        if (navBackdrop) {
            navBackdrop.addEventListener('click', closeMobileNav);
        }

        // Close when a navigation link inside the overlay is clicked
        navOverlay.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeMobileNav);
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navToggle.classList.contains('active')) {
                closeMobileNav();
            }
        });
    }

    /* -------------------------------------------------------
     * b) Copy URL / Share Buttons
     * ----------------------------------------------------- */
    document.querySelectorAll('[data-share="copy"]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const url = window.location.href;

            try {
                await navigator.clipboard.writeText(url);
            } catch {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = url;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }

            showCopyTooltip(btn, 'Copied!');
        });
    });

    /**
     * Display a brief tooltip near the target element.
     */
    function showCopyTooltip(target, message) {
        // Remove any existing tooltip on this element first
        const existing = target.querySelector('.copy-tooltip');
        if (existing) existing.remove();

        const tooltip = document.createElement('span');
        tooltip.className = 'copy-tooltip';
        tooltip.textContent = message;
        tooltip.style.cssText =
            'position:absolute;top:-32px;left:50%;transform:translateX(-50%);' +
            'background:#333;color:#fff;padding:4px 10px;border-radius:4px;' +
            'font-size:12px;white-space:nowrap;pointer-events:none;z-index:1000;' +
            'opacity:1;transition:opacity .3s ease;';

        // Make sure parent can anchor the tooltip
        const parentPos = getComputedStyle(target).position;
        if (parentPos === 'static') {
            target.style.position = 'relative';
        }

        target.appendChild(tooltip);

        setTimeout(() => {
            tooltip.style.opacity = '0';
        }, 1200);
        setTimeout(() => {
            tooltip.remove();
        }, 1600);
    }

    /* -------------------------------------------------------
     * c) Table of Contents Active State (IntersectionObserver)
     * ----------------------------------------------------- */
    const tocLinks = document.querySelectorAll('.toc a[href^="#"]');
    const HEADER_OFFSET = 60;

    if (tocLinks.length > 0) {
        // Collect the heading IDs that the TOC references
        const headingIds = Array.from(tocLinks)
            .map(link => link.getAttribute('href').substring(1))
            .filter(Boolean);

        const headings = headingIds
            .map(id => document.getElementById(id))
            .filter(Boolean);

        if (headings.length > 0) {
            const observerOptions = {
                rootMargin: `-${HEADER_OFFSET}px 0px -40% 0px`,
                threshold: 0,
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        setActiveTocLink(id);
                    }
                });
            }, observerOptions);

            headings.forEach(heading => observer.observe(heading));
        }
    }

    function setActiveTocLink(activeId) {
        tocLinks.forEach(link => {
            link.classList.toggle(
                'active',
                link.getAttribute('href') === `#${activeId}`
            );
        });
    }

    /* -------------------------------------------------------
     * d) Smooth Scroll for TOC Links (with header offset)
     * ----------------------------------------------------- */
    document.querySelectorAll('.toc a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const targetId = link.getAttribute('href').substring(1);
            const target = document.getElementById(targetId);
            if (!target) return;

            e.preventDefault();
            const top = target.getBoundingClientRect().top + window.scrollY - HEADER_OFFSET;
            window.scrollTo({ top, behavior: 'smooth' });

            // Update URL hash without jumping
            history.pushState(null, '', `#${targetId}`);
        });
    });

    /* -------------------------------------------------------
     * e) SEO / Collapsible Section Toggle
     * ----------------------------------------------------- */
    document.querySelectorAll('.seo-toggle').forEach(toggle => {
        toggle.addEventListener('click', () => {
            const section = toggle.closest('.seo-section');
            if (section) {
                section.classList.toggle('open');
            }
        });
    });

    /* -------------------------------------------------------
     * f) Flash Message Auto-Dismiss
     * ----------------------------------------------------- */
    document.querySelectorAll('.flash-message').forEach(flash => {
        // Close button
        const closeBtn = flash.querySelector('.flash-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => dismissFlash(flash));
        }

        // Auto-dismiss after 5 seconds
        setTimeout(() => dismissFlash(flash), 5000);
    });

    function dismissFlash(el) {
        if (!el || el.dataset.dismissed) return;
        el.dataset.dismissed = 'true';
        el.style.transition = 'opacity .4s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    }

    /* -------------------------------------------------------
     * g) reCAPTCHA v3 Form Handler
     * ----------------------------------------------------- */
    if (typeof grecaptcha !== 'undefined' && typeof RECAPTCHA_SITE_KEY !== 'undefined') {
        document.querySelectorAll('.g-recaptcha-response').forEach(function(input) {
            var form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (input.value) return; // Token already set
                    e.preventDefault();
                    var f = this;
                    grecaptcha.ready(function() {
                        grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: input.dataset.action || 'submit'
                        }).then(function(token) {
                            input.value = token;
                            f.submit();
                        });
                    });
                });
            }
        });
    }

    /* -------------------------------------------------------
     * h) Lazy Load Images - Error Handling for Broken Images
     * ----------------------------------------------------- */
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        img.addEventListener('error', () => {
            img.classList.add('img-broken');
            // Replace with a lightweight placeholder SVG
            img.src =
                'data:image/svg+xml,' +
                encodeURIComponent(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" ' +
                    'viewBox="0 0 300 200"><rect fill="#e2e8f0" width="300" height="200"/>' +
                    '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" ' +
                    'fill="#94a3b8" font-family="sans-serif" font-size="14">Image not available</text></svg>'
                );
            img.alt = 'Image not available';
        });
    });

});
