<?php

declare(strict_types=1);

return [
    App\Modules\Core\Providers\CoreServiceProvider::class,
    App\Modules\Administration\Panels\Landlord\Providers\LandlordPanelProvider::class,
    App\Modules\Administration\Panels\Tenant\Providers\TenantPanelProvider::class,
];
