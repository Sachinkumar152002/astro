<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Remedie;
class RemediesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Remedie::count()==0){
            $pages =  remediesAr();
            foreach($pages as $page){
                $newRemedies = new Remedie();
                $newRemedies->name = $page['name'];
                $newRemedies->slug = str_slug($page['slug']);
                $newRemedies->type = $page['type'];
                $newRemedies->save();
            }
            echo "Remdies updated";
        }

    }
}
