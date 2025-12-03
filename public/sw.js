const CACHE_NAME = 'mdrrmo-driver-v2';
const CORE_ASSETS = [
  '/',
  '/driver/send-location',
  '/manifest.webmanifest',
  '/icons/icon-192.svg',
  '/icons/icon-512.svg'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(CORE_ASSETS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))).then(() => self.clients.claim())
  );
});

// Network-first for GET requests; ignore POSTs like /update-location
self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return; // let network handle POST/others
  event.respondWith(
    fetch(req).then((res) => {
      const resClone = res.clone();
      caches.open(CACHE_NAME).then((cache) => cache.put(req, resClone));
      return res;
    }).catch(() => caches.match(req))
  );
});


