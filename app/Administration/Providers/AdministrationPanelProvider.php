<?php

declare(strict_types=1);

namespace App\Administration\Providers;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Actions;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Components;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use LaravelLang\Locales\Facades\Locales;

class AdministrationPanelProvider extends PanelProvider
{
    /**
     * Bootstrap any panel services.
     */
    public function boot(): void
    {
        $this->configureComponents();
        $this->configurePlugins();
    }

    /**
     * Configure the administration panel.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('administration')
            ->assets([Css::make('instrument-sans', 'https://fonts.bunny.net/css?family=instrument-sans:400,500,600')])
            ->authMiddleware([\Filament\Http\Middleware\Authenticate::class])
            ->colors(['primary' => '#4B5563'])
            ->databaseNotifications()
            ->default()
            ->discoverClusters(in: app_path('Administration/Clusters'), for: 'App\Administration\Clusters')
            ->discoverLivewireComponents(in: app_path('Administration/Livewire/Components'), for: 'App\Administration\Livewire\Components')
            ->discoverPages(in: app_path('Administration/Pages'), for: 'App\Administration\Pages')
            ->discoverResources(in: app_path('Administration/Resources'), for: 'App\Administration\Resources')
            ->discoverWidgets(in: app_path('Administration/Widgets'), for: 'App\Administration\Widgets')
            ->favicon('favico.ico')
            ->login()
            ->maxContentWidth(Width::Full)
            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Filament\Http\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
            ])
            ->pages([\Filament\Pages\Dashboard::class])
            ->path('administration')
            ->sidebarFullyCollapsibleOnDesktop()
            ->spa()
            ->unsavedChangesAlerts()
            ->viteTheme('resources/css/administration/theme.css');
    }

    /**
     * Configure the administration panel components.
     */
    protected function configureComponents(): void
    {
        Actions\ActionGroup::configureUsing(
            fn (Actions\ActionGroup $group): Actions\ActionGroup => $group
                ->button()
                ->label('Actions')
                ->icon(Heroicon::OutlinedChevronDown)
                ->iconPosition(IconPosition::After)
        );

        Actions\BulkActionGroup::configureUsing(
            fn (Actions\BulkActionGroup $group): Actions\BulkActionGroup => $group
                ->button()
                ->label('Actions')
                ->icon(Heroicon::OutlinedChevronDown)
                ->iconPosition(IconPosition::After)
        );

        Tables\Table::configureUsing(
            fn (Tables\Table $table): Tables\Table => $table
                ->deferFilters()
                ->deferLoading()
                ->extremePaginationLinks()
                ->filtersFormWidth(Width::FourExtraLarge)
                ->filtersFormColumns(2)
                ->defaultSort('created_at', 'desc')
        );

        Tables\Filters\BaseFilter::configureUsing(function (Tables\Filters\BaseFilter $filter): void {
            /** @var string $label */
            $label = $filter->getLabel();

            $filter->indicator(trans($label));
        });

        Components\ViewComponent::configureUsing(function (Components\ViewComponent $component): void {
            if (method_exists($component, 'translateLabel')) {
                $component->translateLabel();
            }
        });
    }

    /**
     * Configure the administration panel plugins.
     */
    protected function configurePlugins(): void
    {
        /** @todo: Enable after plugin supports Filament v5. */
        // LanguageSwitch::configureUsing(
        // fn (LanguageSwitch $plugin): LanguageSwitch => $plugin->locales(
        // Locales::installed()->pluck('code')->toArray()
        // )
        // );
    }
}
