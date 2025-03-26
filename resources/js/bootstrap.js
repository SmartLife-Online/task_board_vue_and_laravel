/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

//window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]').content;

axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

async function refreshCsrfToken() {
    try {
        const response = await axios.get('/refresh-csrf');
        const newToken = response.data.csrf_token;

        axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;

        document.head.querySelector('meta[name="csrf-token"]').content = newToken;
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
    }
}

axios.interceptors.response.use(
    response => response,
    async error => {
      if (error.response.status === 419) {
        await refreshCsrfToken();
        
        error.config.headers['X-CSRF-TOKEN'] = axios.defaults.headers.common['X-CSRF-TOKEN'];

        return axios(error.config);
      }

      return Promise.reject(error);
    }
);

export default axios;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
