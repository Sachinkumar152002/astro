<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Punchang;

class PunchangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Punchang::count()==0){
            $pages =  punchangAr();
            foreach($pages as $page){
                $newFestival = new Punchang();
                $newFestival->name = $page['name'];
                $newFestival->slug = str_slug($page['slug']);
                $newFestival->type = $page['type'];
                $newFestival->save();
            }
            echo "Punchange entries done";
        }
    }
}
