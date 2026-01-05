import '../css/app.css';

import { createInertiaApp, router } from '@inertiajs/react';
import { LaravelReactI18nProvider as I18nProvider } from 'laravel-react-i18n';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import React from 'react';
import { createRoot } from 'react-dom/client';

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
            <Application>
                <App {...props} />
            </Application>,
        );
    },

    /**
     * Set the page's title.
     */
    title: (title) => (title ? `${title} - ${import.meta.env.VITE_APP_NAME}` : import.meta.env.VITE_APP_NAME),
});

/**
 * The application's wrapper.
 */
const Application = ({ children }: { children: React.ReactNode }) => {
    return (
        <React.StrictMode>
            <I18nProvider
                locale={document.documentElement.getAttribute('lang') as string}
                fallbackLocale="en"
                files={import.meta.glob('/lang/*.json')}
            >
                {children}
            </I18nProvider>
        </React.StrictMode>
    );
};
