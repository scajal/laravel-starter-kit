import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import React from 'react';
import { createRoot } from 'react-dom/client';
import '../css/app.css';

createInertiaApp({
    /**
     * Resolve the component with the provided path.
     */
    resolve: (path) => resolvePageComponent(`./pages/${path}/page.tsx`, import.meta.glob('./pages/**/page.tsx')),

    /**
     * Setup and render the app.
     */
    setup: ({ App, el, props }) =>
        createRoot(el).render(
            <Application>
                <App {...props} />
            </Application>,
        ),

    /**
     * Set the page's title.
     */
    title: (title) => `${title} | ${import.meta.env.VITE_APP_NAME}`,
});

function Application({ children }: { children: React.ReactNode }) {
    return <React.StrictMode>{children}</React.StrictMode>;
}
