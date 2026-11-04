
// Page Speed Optimization & Instant Navigation Prefetch
document.addEventListener('DOMContentLoaded', function() {
    // Lazy load images with data-src
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-src]');
        if (images.length) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                        }
                        imageObserver.unobserve(img);
                    }
                });
            }, { rootMargin: '100px 0px' });

            images.forEach(img => imageObserver.observe(img));
        }
    }

    // Prefetch internal navigation links on hover/touch for instant page transitions
    const prefetchedUrls = new Set();
    const navLinks = document.querySelectorAll('.header-nav a[href], .site-footer a[href]');
    
    function prefetchPage(href) {
        if (!href || prefetchedUrls.has(href)) return;
        if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('http') || href.startsWith('javascript')) return;
        
        prefetchedUrls.add(href);
        const linkElement = document.createElement('link');
        linkElement.rel = 'prefetch';
        linkElement.href = href;
        document.head.appendChild(linkElement);
    }

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && !href.startsWith('#')) {
            link.addEventListener('mouseenter', () => prefetchPage(href), { passive: true, once: true });
            link.addEventListener('touchstart', () => prefetchPage(href), { passive: true, once: true });
        }
    });
});

// Performance monitoring
if ('performance' in window) {
    window.addEventListener('load', function() {
        setTimeout(function() {
            const perfData = performance.getEntriesByType('navigation')[0];
            const loadTime = perfData.loadEventEnd - perfData.navigationStart;
            
            if (loadTime > 3000) {
                console.log('Page load time: ' + Math.round(loadTime) + 'ms - Consider optimization.');
                
                // Log performance metrics
                console.log('Performance breakdown:', {
                    'DNS Lookup': Math.round(perfData.domainLookupEnd - perfData.domainLookupStart),
                    'Connection': Math.round(perfData.connectEnd - perfData.connectStart),
                    'Request': Math.round(perfData.responseStart - perfData.requestStart),
                    'Response': Math.round(perfData.responseEnd - perfData.responseStart),
                    'DOM Processing': Math.round(perfData.domComplete - perfData.domLoading)
                });
            }
        }, 100);
    });
}

// Add resource hints for better performance
if (document.head) {
    // Preconnect to Google Fonts
    const preconnectGoogle = document.createElement('link');
    preconnectGoogle.rel = 'preconnect';
    preconnectGoogle.href = 'https://fonts.googleapis.com';
    document.head.appendChild(preconnectGoogle);
    
    const preconnectGstatic = document.createElement('link');
    preconnectGstatic.rel = 'preconnect';
    preconnectGstatic.href = 'https://fonts.gstatic.com';
    preconnectGstatic.crossOrigin = 'anonymous';
    document.head.appendChild(preconnectGstatic);
}
