<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Model\NgSoftware;

class NgSoftwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        if(NgSoftware::count()==0){
          $pages =  cms2Arr();
          foreach($pages as $page){
              $item = new NgSoftware();
              $item->title = $page['title'];
              $item->slug = str_slug($page['slug']);
              $item->page = $page['page'];
              $item->description = $page['description'];
              $item->save();
          }
        }
        echo "Cms2 Page done";
    }
}
