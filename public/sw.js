self.addEventListener('install', event => {
    console.log('eSIR Service Worker Installed. Ready for Mobile PWA.');
});

self.addEventListener('fetch', event => {
    // Basic service worker pass-through (No heavy caching yet)
    // event.respondWith(fetch(event.request));
});
