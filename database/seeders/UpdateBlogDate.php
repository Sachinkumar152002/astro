<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Model\Article;

class UpdateBlogDate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Article::get();
        foreach($posts as $post){
          $post->posted_on = date('Y-m-d', strtotime($post->created_at));
          $post->save();
        }
    }
}
