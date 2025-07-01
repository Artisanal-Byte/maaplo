<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => Hash::make('user@123')
        ]);

        $this->call([
            MeasurementSeeder::class,
        ]);
        $this->call(UsersTableSeeder::class);
        $this->call(BodyPartValueTableSeeder::class);
        $this->call(DesignDetailsTableSeeder::class);
        $this->call(TemplatesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(MeasurementsTableSeeder::class);
        $this->call(UsersCustomersTableSeeder::class);
        $this->call(TemplatesMeasurementsTableSeeder::class);
        // $this->call(OrderSeeder::class);
    }
}
