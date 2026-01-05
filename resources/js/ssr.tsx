import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import ReactDOMServer from 'react-dom/server';
import { Wrapper } from './wrapper';

// Create the server-side renderer.
createServer((page) =>
    // Create the Inertia app.
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,

        /**
         * Resolve the page component.
         */
        resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),

        /**
         * Setup and render the app.
         */
        setup: ({ App, props }) => (
            <Wrapper>
                <App {...props} />
            </Wrapper>
        ),

        /**
         * Set the page's title.
         */
        title: (title) => (title ? `${title} - ${import.meta.env.VITE_APP_NAME}` : import.meta.env.VITE_APP_NAME),
    }),
);
