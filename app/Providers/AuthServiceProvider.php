<?php

namespace App\Providers;

use App\Models\BusinessProfile;
use App\Models\Room;
use App\Models\ResortRoom;
use App\Models\Cottage;
use App\Policies\BusinessProfilePolicy;
use App\Policies\RoomPolicy;
use App\Policies\ResortRoomPolicy;
use App\Policies\CottagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        BusinessProfile::class => BusinessProfilePolicy::class,
        Room::class => RoomPolicy::class,
        ResortRoom::class => ResortRoomPolicy::class,
        Cottage::class => CottagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define admin gate
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        // Define business owner gate
        Gate::define('business-owner', function ($user) {
            return $user->isBusinessOwner();
        });
    }
}
