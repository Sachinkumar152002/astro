<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Cms;

class HoroScopeStaticPage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newPage = Cms::firstOrNew(['slug'=>'horoscopes']);
        if(!$newPage->id){
            $newPage->name = 'Horoscope';
            $newPage->meta_title = 'Horoscope';
            $newPage->slug = 'horoscopes';
            $newPage->template = 'fixed';
            $newPage->status = 1;
            $newPage->hide_from_list = 1;
            $newPage->save();
        }

        $newPage = Cms::firstOrNew(['slug'=>'contact']);
        if(!$newPage->id){
            $newPage->name = 'Contact Us';
            $newPage->meta_title = 'Contact Us';
            $newPage->slug = 'contact';
            $newPage->template = 'contact';
            $newPage->status = 1;
            $newPage->hide_from_list = 1;
            $newPage->save();
        }
    }
}
