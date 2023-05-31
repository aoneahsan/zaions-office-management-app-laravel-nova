<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Default\AttachmentSeeder;
use Database\Seeders\Default\CommentSeeder;
use Database\Seeders\Default\RolePermissionsSeeder;
use Database\Seeders\Default\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Default DB Seeders
            RolePermissionsSeeder::class,
            UserSeeder::class,
            CommentSeeder::class,
            AttachmentSeeder::class,

        ]);
    }
}
