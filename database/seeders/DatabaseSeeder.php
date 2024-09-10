<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Nabilah',
            'email' => 'nabilah@example.com',
            'password' => bcrypt('123'),
            'telp' => '098987676545',
            'alamat' => 'tasikmalaya',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Siti',
            'email' => 'siti@example.com',
            'password' => bcrypt('123'),
            'telp' => '098987676578',
            'alamat' => 'tasikmalaya',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Isna',
            'email' => 'isna@example.com',
            'password' => bcrypt('123'),
            'telp' => '098987656545',
            'alamat' => 'tasikmalaya',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Kanaya',
            'email' => 'kanaya@example.com',
            'password' => bcrypt('123'),
            'telp' => '098956786545',
            'alamat' => 'tasikmalaya',
        ]);
    }
}
