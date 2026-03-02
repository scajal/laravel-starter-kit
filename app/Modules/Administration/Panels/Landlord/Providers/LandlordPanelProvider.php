<?php

declare(strict_types=1);

namespace App\Modules\Administration\Panels\Landlord\Providers;

use App\Modules\Administration\Panels\Landlord\Pages\Dashboard;
use App\Modules\Administration\Providers\PanelProvider;
use Filament\Panel;

class LandlordPanelProvider extends PanelProvider
{
    /**
     * Configure the administration panel.
     */
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('administration-landlord')
            ->colors(['primary' => '#009045'])
            ->discoverClusters(in: app_path('Modules/Administration/Panels/Landlord/Clusters'), for: 'App\Modules\Administration\Panels\Landlord\Clusters')
            ->discoverLivewireComponents(in: app_path('Modules/Administration/Panels/Landlord/Livewire/Components'), for: 'App\Modules\Administration\Panels\Landlord\Livewire\Components')
            ->discoverPages(in: app_path('Modules/Administration/Panels/Landlord/Pages'), for: 'App\Modules\Administration\Panels\Landlord\Pages')
            ->discoverResources(in: app_path('Modules/Administration/Panels/Landlord/Resources'), for: 'App\Modules\Administration\Panels\Landlord\Resources')
            ->discoverWidgets(in: app_path('Modules/Administration/Panels/Landlord/Widgets'), for: 'App\Modules\Administration\Panels\Landlord\Widgets')
            ->pages([Dashboard::class])
            ->path('administration/landlord');
    }
}
