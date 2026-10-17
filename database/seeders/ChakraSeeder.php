<?php

use Illuminate\Database\Seeder;
use App\Model\Chakra;

class ChakraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Chakra::count()==0){
            $pages =  chakraAr();
            foreach($pages as $page){
                $newChakra = new Chakra();
                $newChakra->name = $page['name'];
                $newChakra->slug = str_slug($page['slug']);
                $newChakra->type = $page['type'];
                $newChakra->save();
            }
            echo "Chakra Entries done";
        }
    }
}
