<?php

namespace App\Common\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Billing\Voucher;
use App\Models\Core\Admin;
use App\Models\Dairy\Plant;
use App\Models\Dairy\Supplier;
use App\Models\Dairy\Worker;
use App\Models\Learning\Instructor;
use App\Models\Learning\Course;
use App\Models\Learning\Training;
use App\Models\Learning\ProgramDelivery;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureMorphMap();

        View::addLocation(resource_path('pdf/templates'));
    }

    /**
     * Morph map for polymorphic relationships (table names for portability)
     */
    private function configureMorphMap(): void
    {
        Relation::enforceMorphMap([
            'core_admins'                 => Admin::class,
            'dairy_plants'                => Plant::class,
            'dairy_suppliers'             => Supplier::class,
            'dairy_workers'               => Worker::class,
            'learning_instructors'        => Instructor::class,
            'learning_courses'            => Course::class,
            'learning_trainings'          => Training::class,
            'learning_program_deliveries' => ProgramDelivery::class,
            'billing_vouchers'            => Voucher::class,
        ]);
    }
}
