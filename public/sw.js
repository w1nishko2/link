// Кеширование отключено для предотвращения проблем со стилизацией на хостинге

// Установка Service Worker
self.addEventListener('install', event => {
  // Пропускаем фазу ожидания для немедленной активации
  self.skipWaiting();
});

// Активация Service Worker
self.addEventListener('activate', event => {
  event.waitUntil(
    // Очищаем все существующие кеши
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          console.log('Очищаем кеш:', cacheName);
          return caches.delete(cacheName);
        })
      );
    }).then(() => {
      // Берем под контроль все вкладки
      return self.clients.claim();
    })
  );
});

// Обработка запросов - всегда запрашиваем из сети
self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Возвращаем ответ напрямую из сети без кеширования
        return response;
      })
      .catch(error => {
        console.log('Ошибка загрузки:', error);
        // В случае ошибки возвращаем стандартный ответ
        return new Response('Network error', { status: 408 });
      })
  );
});

// Обработка push уведомлений (если понадобится в будущем)
self.addEventListener('push', event => {
  const options = {
    body: event.data ? event.data.text() : 'Новое уведомление',
    icon: '/icons/icon-192x192.svg',
    badge: '/icons/icon-72x72.svg',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Открыть сайт',
        icon: '/icons/icon-192x192.svg'
      },
      {
        action: 'close',
        title: 'Закрыть',
        icon: '/icons/icon-192x192.svg'
      }
    ]
  };

  event.waitUntil(
    self.registration.showNotification('Linkink', options)
  );
});

// Обработка клика по уведомлению
self.addEventListener('notificationclick', event => {
  event.notification.close();

  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow('/')
    );
  }
});