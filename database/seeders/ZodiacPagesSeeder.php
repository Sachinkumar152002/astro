<?php

use Illuminate\Database\Seeder;
use App\Model\Cms;
use App\Model\SubPage;

class ZodiacPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'name' => 'Zodiac Signs',
                'slug' => 'zodiac-signs',
                'children' => [
                            ['name' => 'Know Your Sun Sign', 'slug' => 'sunsigns'] ,
                            ['name' => 'Know Your Moon Sign', 'slug' => 'moonsigns'] ,
                            ['name' => 'Zodiac Compatibility', 'slug' => 'compatibility'] ,
                ]
            ]
        ];
        foreach($pages as $item) {
            $newPage = Cms::firstOrNew(['slug'=>$item['slug']]);
            $newPage->name = $item['name'];
            $newPage->meta_title = $item['name'];
            $newPage->slug = $item['slug'];
            $newPage->template = 'fixed';
            $newPage->status = 1;
            $newPage->hide_from_list = 1;
            $newPage->save();
            if(isset($item['children']) && count($item['children'])>0) {
                foreach($item['children'] as $chilitem) {
                    $newChildPage = Cms::firstOrNew(['slug'=>$chilitem['slug']]);
                    $newChildPage->name = $chilitem['name'];
                    $newChildPage->meta_title = $chilitem['name'];
                    $newChildPage->slug = $chilitem['slug'];
                    $newChildPage->template = 'fixed';
                    $newChildPage->status = 1;
                    $newChildPage->parent_page_id = $newPage->id;
                    $newChildPage->hide_from_list = 1;
                    $newChildPage->save();
                }
            }
        }
        // echo "Zodiac Page Table Updated";
        $zodiaSigns = zodiacSigns();
        $moduleKey = 'zodiac-signs';
        $subPages = zodiacSignsMorePagesTypes();
        foreach($zodiaSigns as $zsign) {
            $signKey = str_slug($zsign['name']);
            foreach($subPages as $sb) {
                    $subPage = SubPage::where([
                        'module_key' => $moduleKey,
                        'parent_slug'=> $signKey,
                        'slug' => str_slug($sb)
                    ])->first() ? : new SubPage();
                    $subPage->module_key = $moduleKey;
                    $subPage->parent_slug = $signKey;
                    $subPage->slug = str_slug($sb);
                    $subPage->name = $sb;
                    $subPage->save();
            }
        }
        // echo "Zodiac Page Child Table Updated";
    }
}
