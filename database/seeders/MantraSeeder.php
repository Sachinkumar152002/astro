<?php

use Illuminate\Database\Seeder;
use App\Model\Mantra;

class MantraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Mantra::count()==0){
            $pages =  mantrasAr();
            foreach($pages as $page){
                $newMantra = new Mantra();
                $newMantra->name = $page['name'];
                $newMantra->slug = str_slug($page['slug']);
                $newMantra->type = $page['type'];
                $newMantra->save();
            }
            echo "Mantra Entries done";
        }
    }
}
