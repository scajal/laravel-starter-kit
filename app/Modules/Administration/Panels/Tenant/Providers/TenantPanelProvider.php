<?php

declare(strict_types=1);

namespace App\Modules\Administration\Panels\Tenant\Providers;

use App\Modules\Administration\Panels\Tenant\Pages\Dashboard;
use App\Modules\Administration\Providers\PanelProvider;
use App\Modules\Core\Models\Tenant;
use Filament\Panel;

class TenantPanelProvider extends PanelProvider
{
    /**
     * Configure the administration panel.
     */
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('administration-tenant')
            ->colors(['primary' => '#009045'])
            ->discoverClusters(in: app_path('Modules/Administration/Panels/Tenant/Clusters'), for: 'App\Modules\Administration\Panels\Tenant\Clusters')
            ->discoverLivewireComponents(in: app_path('Modules/Administration/Panels/Tenant/Livewire/Components'), for: 'App\Modules\Administration\Panels\Tenant\Livewire\Components')
            ->discoverPages(in: app_path('Modules/Administration/Panels/Tenant/Pages'), for: 'App\Modules\Administration\Panels\Tenant\Pages')
            ->discoverResources(in: app_path('Modules/Administration/Panels/Tenant/Resources'), for: 'App\Modules\Administration\Panels\Tenant\Resources')
            ->discoverWidgets(in: app_path('Modules/Administration/Panels/Tenant/Widgets'), for: 'App\Modules\Administration\Panels\Tenant\Widgets')
            ->pages([Dashboard::class])
            ->path('administration/tenant')
            ->tenant(Tenant::class);
    }
}
