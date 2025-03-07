import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
//
// window.Pusher = Pusher;
//
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
//
// // Listen for notifications
// if (window.userId) {
//     window.Echo.private(`App.Models.User.${window.userId}`)
//         .notification((notification) => {
//             // This will trigger the Livewire component's handleNewNotification method
//             Livewire.dispatch('refresh-notifications');
//         });
// }
