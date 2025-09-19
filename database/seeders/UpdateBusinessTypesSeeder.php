<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\User;

class UpdateBusinessTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all businesses with their owners
        $businesses = Business::with('owner.businessProfile')->get();
        
        $updated = 0;
        
        foreach ($businesses as $business) {
            if ($business->owner && $business->owner->businessProfile) {
                $businessType = $business->owner->businessProfile->business_type;
                
                // Only update if the business type is different
                if ($business->business_type !== $businessType) {
                    $business->business_type = $businessType;
                    $business->save();
                    $updated++;
                }
            }
        }
        
        $this->command->info("Updated business types for {$updated} businesses.");
    }
}
