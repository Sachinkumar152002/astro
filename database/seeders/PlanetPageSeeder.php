<?php

use Illuminate\Database\Seeder;
use App\Model\Planet;

class PlanetPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Planet::count()==0){
            $pages = planetsPagesAr();
            foreach($pages as $p){
                $newPage = new Planet();
                $newPage->name = $p['name'];
                $newPage->slug = str_slug($p['name']);
                $newPage->page_type = $p['type'];
                $newPage->is_transit = $p['is_transit'];
                $newPage->is_retrograde = $p['is_retrograde'];
                $newPage->is_grahan = $p['is_grahan'];
                $newPage->grahan_name = $p['grahan_name'];
                $newPage->save();
                if($newPage->is_transit) {
                    $newPageTransit = new Planet();
                    $newPageTransit->name = $p['name'];
                    $newPageTransit->slug = str_slug($p['name']);
                    $newPageTransit->page_type = 'transit';
                    $newPageTransit->parent_id = $newPage->id;
                    $newPageTransit->save();
                }
                if($newPage->is_retrograde) {
                    $newPageRetrograde = new Planet();
                    $newPageRetrograde->name = $p['name'];
                    $newPageRetrograde->slug = str_slug($p['name']);
                    $newPageRetrograde->page_type = 'retrograde';
                    $newPageRetrograde->parent_id = $newPage->id;
                    $newPageRetrograde->save();
                }
                if($newPage->is_grahan) {
                    $newPageGrahan = new Planet();
                    $newPageGrahan->name = $p['name'];
                    $newPageGrahan->slug = str_slug($p['name']);
                    $newPageGrahan->page_type = 'grahan';
                    $newPageGrahan->parent_id = $newPage->id;
                    $newPageGrahan->save();
                }
            }
            echo "Planet Table Updated";
        }
    }
}
