<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Playlist;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(9)->create();
        User::factory()->create([
            'firstname'=>'Ahmad',
            'lastname'=>'Mohammed',
            'password'=>'123456789',
            'is_admin'=>'1',
        ]);
        Project::factory(10)->create();
        Task::factory(10)->create();
    }
}
