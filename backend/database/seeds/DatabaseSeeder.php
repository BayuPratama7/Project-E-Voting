<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            PemilihSeeder::class,
            KandidatSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
