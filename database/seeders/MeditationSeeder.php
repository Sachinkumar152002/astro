<?php

use Illuminate\Database\Seeder;
use App\Model\Meditation;

class MeditationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Meditation::count()==0){
            $pages =  meditationAr();
            foreach($pages as $page){
                $newMeditation = new Meditation();
                $newMeditation->name = $page['name'];
                $newMeditation->slug = str_slug($page['slug']);
                $newMeditation->type = $page['type'];
                $newMeditation->save();
            }
            echo "Meditation Entries done";
        }
    }
}
