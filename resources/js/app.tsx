import '../css/app.css';

import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { Wrapper } from './wrapper';

// Make sure the intercepted pages are not
// shown in an ungly modal.
router.on('invalid', (event) => {
    event.preventDefault();
    const response = event.detail.response;
    window.location.href = response.request.responseURL;
});

// Create the Inertia app.
createInertiaApp({
    progress: {
        color: '#4B5563',
        delay: 0,
    },

    /**
     * Resolve the component with the provided path.
     */
    resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),

    /**
     * Setup and render the app.
     */
    setup({ el, App, props }) {
        el.removeAttribute('data-page');

        createRoot(el).render(
            <Wrapper>
                <App {...props} />
            </Wrapper>,
        );
    },

    /**
     * Set the page's title.
     */
    title: (title) => (title ? `${title} - ${import.meta.env.VITE_APP_NAME}` : import.meta.env.VITE_APP_NAME),
});
