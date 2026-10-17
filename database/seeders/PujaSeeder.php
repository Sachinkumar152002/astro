<?php

use Illuminate\Database\Seeder;
use App\Model\Puja;
class PujaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Puja::count()==0){
            $pages =  pujaAr();
            foreach($pages as $page){
                $newPuja = new Puja();
                $newPuja->name = $page['name'];
                $newPuja->slug = str_slug($page['slug']);
                $newPuja->type = $page['type'];
                $newPuja->save();
            }
            echo "Puja Entries done";
        }
    }
}
