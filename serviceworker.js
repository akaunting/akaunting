var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    'public/img/icons/akaunting-72x72.png',
    'public/img/icons/akaunting-96x96.png',
    'public/img/icons/akaunting-120x120.png',
    'public/img/icons/akaunting-128x128.png',
    'public/img/icons/akaunting-144x144.png',
    'public/img/icons/akaunting-152x152.png',
    'public/img/icons/akaunting-180x180.png',
    'public/img/icons/akaunting-192x192.png',
    'public/img/icons/akaunting-384x384.png',
    'public/img/icons/akaunting-512x512.png',
    'public/img/icons/akaunting-640x1136.png'
];

/*
// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});
*/
/*
// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});*/

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});
