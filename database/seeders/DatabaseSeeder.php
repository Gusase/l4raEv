<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Foundation\Auth\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Asuka',
            'username' => 'Shikinami',
            'email' => 'strike@gmail.com',
            'password' => bcrypt('asukaaaa')
        ]);

        User::factory(5)->create();

        Category::create([
            'name' => 'Unit 00',
            'slug' => 'unit-00',
        ]);

        Category::create([
            'name' => 'Unit 02',
            'slug' => 'unit-02',
        ]);

        Category::create([
            'name' => 'Unit 01',
            'slug' => 'unit-01',
        ]);

        Post::factory(25)->create();
    }
}
