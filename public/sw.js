// Service Worker for PWA
const CACHE_NAME = 'absensi-siswa-v1.0.0';
const OFFLINE_URL = '/offline.html';

// Assets to cache on install
const STATIC_CACHE = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/favicon.png',
    '/pwa-icons/icon-192x192.png',
    '/pwa-icons/icon-512x512.png'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing service worker...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Caching static assets');
                return cache.addAll(STATIC_CACHE);
            })
            .then(() => {
                console.log('[SW] Service worker installed');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('[SW] Installation failed:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating service worker...');
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== CACHE_NAME) {
                            console.log('[SW] Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('[SW] Service worker activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip cross-origin requests
    if (url.origin !== location.origin) {
        return;
    }
    
    // Skip API calls for face recognition (always need fresh data)
    if (url.pathname.includes('/api/') || 
        url.pathname.includes('/face-attendance') ||
        url.pathname.includes('/livewire/')) {
        return;
    }
    
    // Network first strategy for HTML pages
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone and cache the response
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    // If network fails, try cache
                    return caches.match(request)
                        .then((cachedResponse) => {
                            if (cachedResponse) {
                                return cachedResponse;
                            }
                            // If not in cache, show offline page
                            return caches.match(OFFLINE_URL);
                        });
                })
        );
        return;
    }
    
    // Cache first strategy for static assets
    event.respondWith(
        caches.match(request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                
                // If not in cache, fetch from network
                return fetch(request)
                    .then((response) => {
                        // Don't cache non-successful responses
                        if (!response || response.status !== 200 || response.type === 'error') {
                            return response;
                        }
                        
                        // Clone and cache the response
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                        
                        return response;
                    })
                    .catch(() => {
                        // Return offline page for navigation requests
                        if (request.mode === 'navigate') {
                            return caches.match(OFFLINE_URL);
                        }
                    });
            })
    );
});

// Background sync for offline attendance submissions
self.addEventListener('sync', (event) => {
    console.log('[SW] Background sync triggered:', event.tag);
    
    if (event.tag === 'sync-attendance') {
        event.waitUntil(syncAttendance());
    }
});

async function syncAttendance() {
    try {
        // Get pending attendance submissions from IndexedDB
        const db = await openDB();
        const pendingSubmissions = await getPendingSubmissions(db);
        
        if (pendingSubmissions.length === 0) {
            console.log('[SW] No pending submissions to sync');
            return;
        }
        
        console.log('[SW] Syncing', pendingSubmissions.length, 'pending submissions');
        
        // Submit each pending attendance
        for (const submission of pendingSubmissions) {
            try {
                const response = await fetch('/face-attendance', {
                    method: 'POST',
                    body: submission.data
                });
                
                if (response.ok) {
                    // Remove from pending queue
                    await removePendingSubmission(db, submission.id);
                    console.log('[SW] Synced submission:', submission.id);
                }
            } catch (error) {
                console.error('[SW] Failed to sync submission:', error);
            }
        }
        
    } catch (error) {
        console.error('[SW] Background sync failed:', error);
    }
}

// IndexedDB helpers (simplified)
function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('AbsensiDB', 1);
        
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
        
        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('pendingAttendance')) {
                db.createObjectStore('pendingAttendance', { keyPath: 'id', autoIncrement: true });
            }
        };
    });
}

function getPendingSubmissions(db) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['pendingAttendance'], 'readonly');
        const store = transaction.objectStore('pendingAttendance');
        const request = store.getAll();
        
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
    });
}

function removePendingSubmission(db, id) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['pendingAttendance'], 'readwrite');
        const store = transaction.objectStore('pendingAttendance');
        const request = store.delete(id);
        
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve();
    });
}

// Push notification handler
self.addEventListener('push', (event) => {
    console.log('[SW] Push notification received');
    
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'Sistem Absensi';
    const options = {
        body: data.body || 'Anda memiliki notifikasi baru',
        icon: '/pwa-icons/icon-192x192.png',
        badge: '/pwa-icons/icon-72x72.png',
        vibrate: [200, 100, 200],
        data: data.url || '/',
        actions: [
            {
                action: 'open',
                title: 'Buka'
            },
            {
                action: 'close',
                title: 'Tutup'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked:', event.action);
    
    event.notification.close();
    
    if (event.action === 'open' || !event.action) {
        const urlToOpen = event.notification.data || '/';
        
        event.waitUntil(
            clients.matchAll({ type: 'window', includeUncontrolled: true })
                .then((clientList) => {
                    // Check if there's already a window open
                    for (const client of clientList) {
                        if (client.url === urlToOpen && 'focus' in client) {
                            return client.focus();
                        }
                    }
                    // Open new window
                    if (clients.openWindow) {
                        return clients.openWindow(urlToOpen);
                    }
                })
        );
    }
});

console.log('[SW] Service worker loaded');
