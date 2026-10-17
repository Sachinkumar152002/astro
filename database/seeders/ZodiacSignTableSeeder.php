<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\ZodiacSign;
use DB;

class ZodiacSignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
      if(ZodiacSign::count()==0){
        DB::raw("truncate table zodiac_signs");
        $zodiaSigns=zodiacSigns();
        foreach($zodiaSigns as $zsign){
            $newZsign=new ZodiacSign();
            $newZsign->name=$zsign['name'];
            $newZsign->slug=str_slug($zsign['name']);
            $newZsign->image_id=$zsign['image_id'];
            $newZsign->sign_date_sd=$zsign['sd'];
            $newZsign->sign_date_sm=$zsign['sm'];
            $newZsign->sign_date_ed=$zsign['ed'];
            $newZsign->sign_date_em=$zsign['em'];
            $newZsign->save();
            $newZsign=null;
        }
        echo "Zodiac Sign Table Updated";
      }
    }
}
