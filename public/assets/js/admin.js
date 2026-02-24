/**
 * Admin Panel JavaScript
 * News/Blog CMS - Vanilla JS, no dependencies
 */
document.addEventListener('DOMContentLoaded', () => {

    /* -------------------------------------------------------
     * a) Sidebar Toggle (Mobile)
     * ----------------------------------------------------- */
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('open');
        if (sidebarOverlay) sidebarOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            const isOpen = sidebar && sidebar.classList.contains('open');
            isOpen ? closeSidebar() : openSidebar();
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    /* -------------------------------------------------------
     * b) Auto-Generate Slug from Title
     * ----------------------------------------------------- */
    const titleInput = document.getElementById('post-title');
    const slugInput = document.getElementById('post-slug');
    let slugManuallyEdited = false;

    if (titleInput && slugInput) {
        // Detect if the user manually edits the slug field
        slugInput.addEventListener('input', () => {
            slugManuallyEdited = true;
        });

        // Only treat it as manually edited if user actually changes the value
        slugInput.addEventListener('focus', () => {
            slugInput.dataset.focusValue = slugInput.value;
        });

        slugInput.addEventListener('blur', () => {
            // If the value is the same as what auto-slug would produce, reset flag
            if (slugInput.value === generateSlug(titleInput.value)) {
                slugManuallyEdited = false;
            }
        });

        titleInput.addEventListener('keyup', () => {
            if (!slugManuallyEdited) {
                slugInput.value = generateSlug(titleInput.value);
            }
        });
    }

    /**
     * Convert text to a URL-safe slug.
     */
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')   // Remove special characters
            .replace(/\s+/g, '-')        // Replace spaces with hyphens
            .replace(/-+/g, '-')         // Collapse multiple hyphens
            .replace(/^-+|-+$/g, '');    // Trim leading/trailing hyphens
    }

    /* -------------------------------------------------------
     * c) SEO Section Toggle
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
     * d) Flash Message Auto-Dismiss
     * ----------------------------------------------------- */
    document.querySelectorAll('.flash-message').forEach(flash => {
        const closeBtn = flash.querySelector('.flash-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => dismissFlash(flash));
        }

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
     * e) Confirm Delete
     * ----------------------------------------------------- */
    document.querySelectorAll('.delete-form, [data-confirm]').forEach(form => {
        form.addEventListener('submit', (e) => {
            const message =
                form.dataset.confirm || 'Are you sure you want to delete this item? This action cannot be undone.';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    /* -------------------------------------------------------
     * f) Media URL Copy
     * ----------------------------------------------------- */
    document.querySelectorAll('.media-item[data-url]').forEach(item => {
        item.addEventListener('click', async () => {
            const url = item.dataset.url;
            if (!url) return;

            try {
                await navigator.clipboard.writeText(url);
            } catch {
                const textarea = document.createElement('textarea');
                textarea.value = url;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }

            showToast('Copied!');
        });
    });

    /**
     * Display a brief toast notification at the bottom of the screen.
     */
    function showToast(message, duration = 2000) {
        // Remove any current toast
        const existing = document.querySelector('.admin-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = 'admin-toast';
        toast.textContent = message;
        toast.style.cssText =
            'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);' +
            'background:#333;color:#fff;padding:10px 24px;border-radius:6px;' +
            'font-size:14px;z-index:9999;opacity:1;transition:opacity .3s ease;' +
            'box-shadow:0 2px 8px rgba(0,0,0,.2);';

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
        }, duration - 300);
        setTimeout(() => {
            toast.remove();
        }, duration);
    }

    /* -------------------------------------------------------
     * g) Character Counter for SEO Fields
     * ----------------------------------------------------- */
    initCharCounter('#seo-meta-title', 160);
    initCharCounter('#seo-meta-description', 320);

    /**
     * Attach a live character counter beneath an input/textarea.
     *
     * @param {string} selector  CSS selector for the field
     * @param {number} max       Maximum recommended character count
     */
    function initCharCounter(selector, max) {
        const field = document.querySelector(selector);
        if (!field) return;

        const counter = document.createElement('small');
        counter.className = 'char-counter';
        counter.style.cssText = 'display:block;margin-top:4px;font-size:12px;color:#64748b;';
        field.parentNode.insertBefore(counter, field.nextSibling);

        function update() {
            const len = field.value.length;
            counter.textContent = `${len} / ${max}`;
            if (len > max) {
                counter.style.color = '#ef4444';
            } else if (len > max * 0.85) {
                counter.style.color = '#f59e0b';
            } else {
                counter.style.color = '#64748b';
            }
        }

        field.addEventListener('input', update);
        // Set initial count
        update();
    }

    /* -------------------------------------------------------
     * h) TinyMCE Initialization
     * ----------------------------------------------------- */
    const editorArea = document.querySelector('#editor');
    if (editorArea && typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#editor',
            menubar: false,
            height: 400,
            skin: 'oxide',
            plugins: 'lists link image code table',
            toolbar:
                'undo redo | formatselect | bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image table | code | removeformat',
            branding: false,
            promotion: false,
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 15px; line-height: 1.7; }',
            setup: (editor) => {
                editor.on('change', () => {
                    editor.save(); // Sync content back to the textarea
                });
            },
        });
    }

});
