<?php

use Illuminate\Database\Seeder;
use App\Model\PalmReading;
class PalmreadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(PalmReading::count()==0){
            $pages =  palmReadingAr();
            foreach($pages as $page){
                $newPalmReading = new PalmReading();
                $newPalmReading->name = $page['name'];
                $newPalmReading->slug = str_slug($page['slug']);
                $newPalmReading->type = $page['type'];
                $newPalmReading->save();
            }
            echo "Palm Reading Entries done";
        }

    }
}
