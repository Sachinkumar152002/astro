<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Service;
use App\Model\Language;

class AstrologerServiceLanugageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            'Vedic Astrology',
            'Tarot Card Reading',
            'Numerology',
            'Vaastu',
            'Fengshui',
            'KP Astrology',
            'Prashna Kundali',
        ];
        $languages = [
            'Bangla',
            'English',
            'Gujarati',
            'Hindi',
            'Kannada',
            'Marathi',
            'Tamil',
            'Telugu',
            'Punjabi',
            'Malayalam',
            'Marwari',
        ];
        if(Service::count()==0){
            foreach($services as $service){
                $newService = new Service();
                $newService->name = $service;
                $newService->slug = str_slug($service);
                $newService->save();
            }
            echo "Services Done";
        }
        if(Language::count()==0){
            foreach($languages as $language){
                $newLanguage = new Language();
                $newLanguage->name = $language;
                $newLanguage->slug = str_slug($language);
                $newLanguage->save();
            }
            echo "Language Done";
        }

    }}
