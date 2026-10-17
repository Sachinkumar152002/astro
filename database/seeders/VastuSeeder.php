<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Vastu;
class VastuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Vastu::count()==0){
            $pages =  vastuAr();
            foreach($pages as $page){
                $newVastu = new Vastu();
                $newVastu->name = $page['name'];
                $newVastu->slug = str_slug($page['slug']);
                $newVastu->type = $page['type'];
                $newVastu->save();
            }
            echo "Vastu Entries done";
        }
    }
}
