(function () {
    'use strict';

    const motionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');

    /* =============================================
       HEADER — reserve its height so the page never jumps
       ============================================= */
    /* Sections are no longer hidden until they scroll into view (that caused flicker). */
    function reserveHeaderSpace() {
        const bar = document.querySelector('.site-header .sticky-header .main-bar');
        if (!bar) return;
        const root = document.documentElement;
        let last = 0;
        const measure = () => {
            const height = Math.round(bar.getBoundingClientRect().height);
            if (height > 0 && height !== last) {
                last = height;
                root.style.setProperty('--vk-header-h', height + 'px');
            }
        };
        measure();
        // border-box: padding/border changes must update the reserved height too
        if ('ResizeObserver' in window) new ResizeObserver(measure).observe(bar, { box: 'border-box' });
        else window.addEventListener('resize', measure, { passive: true });
        window.addEventListener('load', measure, { once: true });
    }

    /* =============================================
       TESTIMONIAL VIDEO PLAYER
       ============================================= */
    function initializeTestimonialVideos() {
        let requestedVideo = null;

        function showPreview(video) {
            const wrapper = video.closest('.video-wrapper');
            const button = wrapper && wrapper.querySelector('.testimonial-play-button');
            if (wrapper) wrapper.classList.remove('loading');
            if (button) {
                button.disabled = false;
                button.hidden = false;
            }
        }

        function pauseOtherTestimonialVideos(activeVideo) {
            document.querySelectorAll('.testimonials-carousel .video-content').forEach((candidate) => {
                if (candidate !== activeVideo) {
                    candidate.pause();
                    showPreview(candidate);
                }
            });
        }

        function restorePreview(video) {
            showPreview(video);
            if (requestedVideo === video) requestedVideo = null;
        }

        document.addEventListener('play', (event) => {
            const playingVideo = event.target;
            if (!(playingVideo instanceof HTMLVideoElement) || !playingVideo.matches('.testimonials-carousel .video-content')) return;
            if (requestedVideo !== playingVideo) {
                playingVideo.pause();
                return;
            }
            requestedVideo = playingVideo;
            pauseOtherTestimonialVideos(playingVideo);
            if (window.jQuery) window.jQuery('.testimonials-carousel').trigger('stop.owl.autoplay');
        }, true);

        if (window.jQuery) {
            window.jQuery('.testimonials-carousel').on('changed.owl.carousel', () => {
                requestedVideo = null;
                pauseOtherTestimonialVideos(null);
            });
        }

        pauseOtherTestimonialVideos(null);

        document.addEventListener('ended', (event) => {
            const video = event.target;
            if (!(video instanceof HTMLVideoElement) || !video.matches('.testimonials-carousel .video-content')) return;
            restorePreview(video);
        }, true);

        // Delegate clicks so playback keeps working on Owl Carousel's looped/cloned cards too.
        document.addEventListener('click', (event) => {
            const button = event.target.closest && event.target.closest('.testimonials-carousel .testimonial-play-button');
            if (!button) return;
            const wrapper = button.closest('.video-wrapper');
            const video = wrapper && wrapper.querySelector('.video-content');
            const source = video && video.querySelector('source[data-src]');
            if (!wrapper || !video || !source) return;

            requestedVideo = video;
            button.disabled = true;
            wrapper.classList.add('loading');
            pauseOtherTestimonialVideos(video);
            if (window.jQuery) window.jQuery('.testimonials-carousel').trigger('stop.owl.autoplay');

            if (!source.getAttribute('src')) {
                source.setAttribute('src', source.dataset.src);
                video.load();
            }

            const playback = video.play();
            if (playback && typeof playback.then === 'function') {
                playback.then(() => {
                    wrapper.classList.remove('loading');
                    button.hidden = true;
                }).catch(() => restorePreview(video));
            }
        });
    }

    /* Alura Apartments: create and control the original 360 tour only after a click. */
    function initializeAluraWalkthrough() {
        const dialogs = Array.from(document.querySelectorAll('.alura-walkthrough-dialog'));
        if (!dialogs.length) return;

        const loadingTimers = new WeakMap();
        const readyFrames = new WeakSet();

        function getFrame(dialog) {
            return dialog.querySelector('[data-alura-walkthrough-frame]');
        }

        function setSceneButtonsEnabled(dialog, enabled) {
            dialog.querySelectorAll('[data-alura-scene-name], [data-alura-scene-step]').forEach((button) => {
                button.disabled = !enabled;
            });
        }

        function getCurrentSceneIndex(dialog) {
            const frame = getFrame(dialog);
            try {
                const getIndex = frame && frame.contentWindow && frame.contentWindow.getCurrentMediaIndex;
                const currentIndex = typeof getIndex === 'function' ? Number(getIndex.call(frame.contentWindow)) : NaN;
                if (Number.isInteger(currentIndex) && currentIndex >= 0) return currentIndex;
            } catch (error) {
                // Use the last selected room if the embedded tour is temporarily unavailable.
            }

            const storedIndex = Number(dialog.dataset.aluraSceneIndex);
            return Number.isInteger(storedIndex) && storedIndex >= 0 ? storedIndex : 0;
        }

        function updateSceneControls(dialog, index, scrollToSelected) {
            const sceneButtons = Array.from(dialog.querySelectorAll('[data-alura-scene-name]'));
            if (!sceneButtons.length) return;

            const sceneIndex = Math.max(0, Math.min(sceneButtons.length - 1, Number(index) || 0));
            const frame = getFrame(dialog);
            const isReady = Boolean(frame && readyFrames.has(frame));
            dialog.dataset.aluraSceneIndex = String(sceneIndex);

            sceneButtons.forEach((button, buttonIndex) => {
                const isCurrent = buttonIndex === sceneIndex;
                button.classList.toggle('is-current', isCurrent);
                if (isCurrent) button.setAttribute('aria-current', 'true');
                else button.removeAttribute('aria-current');
            });

            const previousButton = dialog.querySelector('[data-alura-scene-step="-1"]');
            const nextButton = dialog.querySelector('[data-alura-scene-step="1"]');
            if (previousButton) previousButton.disabled = !isReady || sceneIndex === 0;
            if (nextButton) nextButton.disabled = !isReady || sceneIndex === sceneButtons.length - 1;

            if (!scrollToSelected) return;
            const list = dialog.querySelector('.alura-room-selector-options');
            const selectedButton = sceneButtons[sceneIndex];
            if (!list || !selectedButton) return;

            const listRect = list.getBoundingClientRect();
            const buttonRect = selectedButton.getBoundingClientRect();
            if (buttonRect.left < listRect.left || buttonRect.right > listRect.right) {
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const targetLeft = list.scrollLeft + buttonRect.left - listRect.left - Math.max(0, (list.clientWidth - buttonRect.width) / 2);
                list.scrollTo({ left: Math.max(0, targetLeft), behavior: reducedMotion ? 'auto' : 'smooth' });
            }
        }

        function stopTimer(dialog) {
            const timer = loadingTimers.get(dialog);
            if (timer) window.clearTimeout(timer);
            loadingTimers.delete(dialog);
        }

        function setStatus(dialog, message) {
            const status = dialog.querySelector('[data-walkthrough-status]');
            if (!status) return;
            status.textContent = message;
            status.hidden = !message;
            status.classList.remove('is-ready');
        }

        function markReady(dialog) {
            const frame = getFrame(dialog);
            const errorPanel = dialog.querySelector('[data-walkthrough-error]');
            if (frame) readyFrames.add(frame);
            setSceneButtonsEnabled(dialog, true);
            updateSceneControls(dialog, getCurrentSceneIndex(dialog), false);
            stopTimer(dialog);
            dialog.dataset.walkthroughState = 'ready';
            if (errorPanel) errorPanel.hidden = true;
            setStatus(dialog, 'Walkthrough ready. Use touch or mouse controls to explore.');

            const status = dialog.querySelector('[data-walkthrough-status]');
            if (status) window.setTimeout(() => status.classList.add('is-ready'), 2600);
        }

        function markFailed(dialog) {
            if (dialog.dataset.walkthroughState === 'ready') return;
            stopTimer(dialog);
            dialog.dataset.walkthroughState = 'failed';
            setSceneButtonsEnabled(dialog, false);
            setStatus(dialog, '');
            const errorPanel = dialog.querySelector('[data-walkthrough-error]');
            if (errorPanel) errorPanel.hidden = false;
        }

        function startLoading(dialog, retry) {
            const frame = getFrame(dialog);
            if (!frame || !frame.dataset.src) return;

            stopTimer(dialog);
            if (retry) {
                if (frame.contentWindow) frame.contentWindow.postMessage('pauseTour', window.location.origin);
                readyFrames.delete(frame);
            }

            const errorPanel = dialog.querySelector('[data-walkthrough-error]');
            if (errorPanel) errorPanel.hidden = true;
            setSceneButtonsEnabled(dialog, false);
            setStatus(dialog, 'Loading the interactive walkthrough…');
            dialog.dataset.walkthroughState = 'loading';
            loadingTimers.set(dialog, window.setTimeout(() => markFailed(dialog), 60000));
            frame.setAttribute('src', frame.dataset.src);
        }

        function pauseAndCollapse(dialog) {
            const frame = getFrame(dialog);
            if (frame && frame.getAttribute('src') && frame.contentWindow) {
                frame.contentWindow.postMessage('pauseTour', window.location.origin);
            }
            document.querySelectorAll('[data-alura-walkthrough-trigger]').forEach((button) => {
                if (button.getAttribute('aria-controls') === dialog.id) button.setAttribute('aria-expanded', 'false');
            });
        }

        dialogs.forEach((dialog) => {
            const frame = getFrame(dialog);
            if (frame) {
                frame.addEventListener('error', () => markFailed(dialog));
                frame.addEventListener('load', () => {
                    if (!dialog.open && frame.contentWindow) frame.contentWindow.postMessage('pauseTour', window.location.origin);
                });
            }
            dialog.addEventListener('close', () => pauseAndCollapse(dialog));
        });

        window.addEventListener('message', (event) => {
            if (event.origin !== window.location.origin) return;
            dialogs.forEach((dialog) => {
                const frame = getFrame(dialog);
                if (!frame || frame.contentWindow !== event.source) return;

                if (event.data === 'tourLoaded') {
                    markReady(dialog);
                    if (!dialog.open && frame.contentWindow) {
                        frame.contentWindow.postMessage('pauseTour', window.location.origin);
                    }
                } else if (event.data && event.data.type === 'tourSceneChanged') {
                    updateSceneControls(dialog, Number(event.data.index), true);
                }
            });
        });

        document.addEventListener('click', (event) => {
            const trigger = event.target.closest && event.target.closest('[data-alura-walkthrough-trigger]');
            if (trigger) {
                const dialog = document.getElementById(trigger.getAttribute('data-walkthrough-target'));
                if (!dialog) return;

                if (!dialog.open) {
                    if (typeof dialog.showModal === 'function') dialog.showModal();
                    else dialog.setAttribute('open', '');
                    trigger.setAttribute('aria-expanded', 'true');
                }

                const frame = getFrame(dialog);
                if (frame && readyFrames.has(frame)) {
                    frame.contentWindow.postMessage('resumeTour', window.location.origin);
                    setStatus(dialog, 'Walkthrough ready. Use touch or mouse controls to explore.');
                    window.setTimeout(() => {
                        const status = dialog.querySelector('[data-walkthrough-status]');
                        if (status) status.classList.add('is-ready');
                    }, 2600);
                } else if (dialog.dataset.walkthroughState !== 'loading') {
                    startLoading(dialog, dialog.dataset.walkthroughState === 'failed');
                }
                return;
            }

            const closeButton = event.target.closest && event.target.closest('[data-alura-walkthrough-close]');
            if (closeButton) {
                const dialog = closeButton.closest('dialog');
                if (dialog && dialog.open && typeof dialog.close === 'function') dialog.close();
                else if (dialog) {
                    dialog.removeAttribute('open');
                    pauseAndCollapse(dialog);
                }
                return;
            }

            const retryButton = event.target.closest && event.target.closest('[data-alura-walkthrough-retry]');
            if (retryButton) {
                const dialog = retryButton.closest('dialog');
                if (dialog) startLoading(dialog, true);
                return;
            }

            const stepButton = event.target.closest && event.target.closest('[data-alura-scene-step]');
            if (stepButton) {
                const dialog = stepButton.closest('.alura-walkthrough-dialog');
                const frame = dialog && getFrame(dialog);
                if (!dialog || !frame || !readyFrames.has(frame) || !frame.contentWindow) return;

                const sceneButtons = Array.from(dialog.querySelectorAll('[data-alura-scene-name]'));
                const targetIndex = getCurrentSceneIndex(dialog) + Number(stepButton.dataset.aluraSceneStep);
                if (targetIndex < 0 || targetIndex >= sceneButtons.length) return;

                try {
                    const chooseScene = frame.contentWindow.setMediaByIndex;
                    if (typeof chooseScene === 'function') {
                        chooseScene.call(frame.contentWindow, targetIndex);
                        updateSceneControls(dialog, targetIndex, true);
                    }
                } catch (error) {
                    setStatus(dialog, 'Room selection is temporarily unavailable. Use the controls inside the walkthrough.');
                }
                return;
            }

            const sceneButton = event.target.closest && event.target.closest('[data-alura-scene-name]');
            if (sceneButton) {
                const dialog = sceneButton.closest('.alura-walkthrough-dialog');
                const frame = dialog && getFrame(dialog);
                if (!frame || !readyFrames.has(frame) || !frame.contentWindow) return;

                try {
                    const chooseScene = frame.contentWindow.setMediaByName;
                    if (typeof chooseScene === 'function') {
                        chooseScene.call(frame.contentWindow, sceneButton.dataset.aluraSceneName);
                        updateSceneControls(dialog, Number(sceneButton.dataset.aluraSceneIndex), true);
                    } else {
                        setStatus(dialog, 'Room selection is temporarily unavailable. Use the controls inside the walkthrough.');
                    }
                } catch (error) {
                    setStatus(dialog, 'Room selection is temporarily unavailable. Use the controls inside the walkthrough.');
                }
                return;
            }

            if (typeof HTMLDialogElement !== 'undefined' && event.target instanceof HTMLDialogElement && event.target.matches('.alura-walkthrough-dialog')) {
                event.target.close();
            }
        });
    }

    /* Load the project brochure only when its preview is opened. */
    function initializeAluraBrochure() {
        document.addEventListener('click', (event) => {
            const trigger = event.target.closest && event.target.closest('[data-alura-brochure-trigger]');
            if (trigger) {
                const dialog = document.getElementById(trigger.getAttribute('aria-controls'));
                if (!dialog) return;

                const frame = dialog.querySelector('iframe[data-src]');
                if (frame && !frame.getAttribute('src')) frame.setAttribute('src', frame.dataset.src);

                if (typeof dialog.showModal === 'function') dialog.showModal();
                else dialog.setAttribute('open', '');
                return;
            }

            const closeButton = event.target.closest && event.target.closest('[data-alura-brochure-close]');
            if (closeButton) {
                const dialog = closeButton.closest('dialog');
                if (dialog && typeof dialog.close === 'function') dialog.close();
                else if (dialog) dialog.removeAttribute('open');
            }
        });

        document.addEventListener('click', (event) => {
            if (typeof HTMLDialogElement !== 'undefined' && event.target instanceof HTMLDialogElement && event.target.matches('.alura-brochure-dialog')) {
                event.target.close();
            }
        });
    }

    /* =============================================
       REDUCED-MOTION — stop carousel auto-play
       ============================================= */
    function stopAutomaticCarouselMotion() {
        if (!motionPreference.matches || !window.jQuery) return;
        window.jQuery('.owl-carousel').trigger('stop.owl.autoplay');
    }

    /* =============================================
       WHATSAPP FLOAT BUTTON — accessibility
       ============================================= */
    function initWhatsAppButton() {
        const btn = document.getElementById('vk-whatsapp-float');
        if (!btn) return;

        // Keyboard: allow Enter/Space to follow the link
        btn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                btn.click();
            }
        });

        // Respect reduced motion — disable entrance animation
        if (motionPreference.matches) {
            btn.style.animation = 'none';
            btn.style.opacity = '1';
            btn.style.transform = 'none';
        }
    }

    /* =============================================
       HEADER & NAVIGATION OPTIMIZATIONS
       ============================================= */
    function markActiveNavItem() {
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
    }

    /* =============================================
       MAIN INIT
       ============================================= */
    /* Alura images: open in an on-page viewer (close, prev/next, Esc) instead of a new tab */
    function initAluraLightbox() {
        const links = Array.from(document.querySelectorAll('.alura-image-open'));
        if (!links.length || typeof HTMLDialogElement === 'undefined') return;
        const dlg = document.createElement('dialog');
        dlg.className = 'vk-lightbox';
        dlg.setAttribute('aria-label', 'Image viewer');
        dlg.innerHTML =
            '<button type="button" class="vk-lb-close" aria-label="Close image"><i class="fa fa-times" aria-hidden="true"></i></button>' +
            '<button type="button" class="vk-lb-prev" aria-label="Previous image"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>' +
            '<img alt="">' +
            '<button type="button" class="vk-lb-next" aria-label="Next image"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>' +
            '<p class="vk-lb-caption"></p>';
        document.body.appendChild(dlg);
        const img = dlg.querySelector('img');
        const caption = dlg.querySelector('.vk-lb-caption');
        let current = 0;
        const show = (n) => {
            current = (n + links.length) % links.length;
            const link = links[current];
            const thumb = link.querySelector('img');
            const figcaption = link.closest('figure') && link.closest('figure').querySelector('figcaption');
            img.src = link.getAttribute('href');
            img.alt = thumb ? thumb.alt : '';
            caption.textContent = figcaption ? figcaption.textContent.trim() : '';
        };
        links.forEach((link, n) => link.addEventListener('click', (event) => {
            event.preventDefault();
            show(n);
            dlg.showModal();
        }));
        dlg.querySelector('.vk-lb-close').addEventListener('click', () => dlg.close());
        dlg.querySelector('.vk-lb-prev').addEventListener('click', () => show(current - 1));
        dlg.querySelector('.vk-lb-next').addEventListener('click', () => show(current + 1));
        dlg.addEventListener('click', (event) => { if (event.target === dlg) dlg.close(); });
        dlg.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') show(current - 1);
            if (event.key === 'ArrowRight') show(current + 1);
        });
    }

    function initialize() {
        reserveHeaderSpace();
        initAluraLightbox();
        initializeTestimonialVideos();
        initializeAluraWalkthrough();
        initializeAluraBrochure();
        stopAutomaticCarouselMotion();
        initWhatsAppButton();
        markActiveNavItem();

        window.addEventListener('load', stopAutomaticCarouselMotion, { once: true });

        if (motionPreference.addEventListener) {
            motionPreference.addEventListener('change', (event) => {
                if (event.matches) stopAutomaticCarouselMotion();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
    } else {
        initialize();
    }
})();
