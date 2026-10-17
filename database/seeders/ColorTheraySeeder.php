<?php

use Illuminate\Database\Seeder;
use App\Model\ColorTherapy;

class ColorTheraySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(ColorTherapy::count()==0){
            $pages =  colorAr();
            foreach($pages as $page){
                $newColorTherapy = new ColorTherapy();
                $newColorTherapy->name = $page['name'];
                $newColorTherapy->slug = str_slug($page['slug']);
                $newColorTherapy->type = $page['type'];
                $newColorTherapy->save();
            }
            echo "ColorTherapy Entries done";
        }
    }
}
