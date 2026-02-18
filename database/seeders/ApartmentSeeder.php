<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\ApartmentImage;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Apartment::factory()->count(10)->create()->each(function ($apartment) {
            ApartmentImage::factory()->count(3)->create([
                'apartment_id' => $apartment->id,
            ]);
        });
    }
}
