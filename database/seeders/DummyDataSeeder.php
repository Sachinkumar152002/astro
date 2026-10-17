<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // 1. Insert 5 Dummy Users
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name' => 'Test User ' . $i,
                'email' => "user{$i}@example.com",
                'phone' => '99999999' . $i,
                'phone_code' => '+91',
                'password' => Hash::make('password123'),
                'created_at' => $now,
                'updated_at' => $now,
                'status' => 1,
            ];
        }
        DB::table('users')->insert($users);

        // 2. Insert 5 Dummy Astrologers
        $astrologers = [];
        for ($i = 1; $i <= 5; $i++) {
            $astrologers[] = [
                'firstname' => 'Astro',
                'lastname' => 'Expert ' . $i,
                'display_name' => 'Astro Expert ' . $i,
                'email' => "astro{$i}@example.com",
                'phone' => '88888888' . $i,
                'phone_code' => '+91',
                'password' => Hash::make('password123'),
                'created_at' => $now,
                'updated_at' => $now,
                'status' => 1,
                'is_verified' => 1,
                'type' => 'Astrologer',
                'chat_price' => rand(10, 50),
                'call_price' => rand(10, 50),
                'experience' => rand(2, 15) . ' Years',
                'wallet' => 0,
            ];
        }
        DB::table('astrologers')->insert($astrologers);

        // 3. Insert 5 Dummy Products
        $products = [];
        for ($i = 1; $i <= 5; $i++) {
            $products[] = [
                'sku' => 'PROD-' . Str::upper(Str::random(6)),
                'title' => 'Dummy Product ' . $i,
                'slug' => 'dummy-product-' . $i,
                'base_price' => rand(500, 2000),
                'status' => '1',
                'avaibility' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('products')->insert($products);

        // 4. Insert 5 Dummy Courses
        $courses = [];
        for ($i = 1; $i <= 5; $i++) {
            $courses[] = [
                'name' => 'Astrology Course ' . $i,
                'slug' => 'astrology-course-' . $i,
                'description' => 'This is a dummy course description for learning astrology basics.',
                'price' => rand(1000, 5000),
                'status' => 1,
                'type' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('courses')->insert($courses);

        echo "Dummy Users, Astrologers, Products, and Courses inserted successfully!\n";
    }
}
