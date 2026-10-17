<?php

use Illuminate\Database\Seeder;
use App\Model\Festival;

class FestivalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Festival::count()==0){
            $pages =  festivalAr();
            foreach($pages as $page){
                $newFestival = new Festival();
                $newFestival->name = $page['name'];
                $newFestival->slug = str_slug($page['slug']);
                $newFestival->type = $page['type'];
                $newFestival->save();
            }
            echo "Festival Entries done";
        }
    }
}
