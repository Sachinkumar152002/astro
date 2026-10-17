<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Wedding;

class WeddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Wedding::count()==0){
            $pages =  weddingAr();
            foreach($pages as $page){
                $newWedding = new Wedding();
                $newWedding->name = $page['name'];
                $newWedding->slug = str_slug($page['slug']);
                $newWedding->type = $page['type'];
                $newWedding->sub_type = $page['sub_type'];
                $newWedding->save();
            }
            echo "Weding Entries done";
        }
    }
}
