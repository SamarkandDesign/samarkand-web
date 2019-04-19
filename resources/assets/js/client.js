import axios from 'axios';

const metaTag = document.querySelector('meta[name=csrf-token]');
const csrfToken = metaTag ? metaTag.getAttribute('content') : undefined;

export const client = axios.create({
  headers: {
    'X-CSRF-TOKEN': csrfToken,
  },
});
