<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Yoga;

class YogaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Yoga::count()==0){
            $pages =  yogaAr();
            foreach($pages as $page){
                $newYoga = new Yoga();
                $newYoga->name = $page['name'];
                $newYoga->slug = str_slug($page['slug']);
                $newYoga->type = $page['type'];
                $newYoga->save();
            }
            echo "Yoga Entries done";
        }
    }
}
