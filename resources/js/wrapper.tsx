import { LaravelReactI18nProvider as I18nProvider } from 'laravel-react-i18n';
import React from 'react';

export const Wrapper = ({ children }: { children: React.ReactNode }) => {
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
