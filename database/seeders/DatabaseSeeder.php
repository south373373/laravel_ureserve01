<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Event用
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // 追記分(ダミーデータの登録)
        Event::factory(100)->create();

        $this->call([
            UserSeeder::class,
            ReservationSeeder::class
        ]);
    }
}
