<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use App\Model\{Astrologer, Category, ZodiacSign, Cms, Punchang, Vastu, Planet, PalmReading, Puja, Mantra, Chakra, ColorTherapy, Wedding, Remedie, Meditation, Yoga, Festival};

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // modify this to your own needs
        $sitemap = SitemapGenerator::create(config('app.url'))->getSitemap();
        // Home page
        $sitemap->add(Url::create(route('home'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS)
            ->setPriority(0.1));
        // Astrologes
        $sitemap->add(Url::create(route('astrologers.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS)
            ->setPriority(0.1));

        $astrologers = Astrologer::where('status', 1)->where('is_verified', 1)->select('slug')->get();
        foreach($astrologers as $astrologer){
            $sitemap->add(Url::create(route('astrologers.publicview',$astrologer->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.2));
        }

        // Blog Urls
        $sitemap->add(Url::create(route('blog.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5));
        $allCategories = Category::where('status', 1)->select('slug', 'id')->get();
        foreach($allCategories as $category){
            $sitemap->add(Url::create(route('blog.category',$category->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.5));
             $articles = $category->where('status', 1);
             foreach($articles as $article){
                $sitemap->add(Url::create(route('blog.post',[$category->slug, $article->slug]))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.5));
             }
        }
        // Zodiac Signs
        $sitemap->add(Url::create(route('zodiac-signs.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5));
        $signs = ZodiacSign::where('status',1)->select('slug')->get();
        foreach($signs as $sign){
            $sitemap->add(Url::create(route('zodiac-signs.sign',[$sign->slug]))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.5));
            foreach(zodiacSignsMorePagesTypes() as $sp){
                $sitemap->add(Url::create(route('zodiac-signs.sign.page',[$sign->slug, str_slug($sp)]))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.5));
            }
        }

        // Pages
        $allPages = Cms::where('status', 1)->where('template','!=','fixed')->select('slug')->get();
        foreach($allPages as $page){
            $sitemap->add(Url::create(route('pages',[$page->slug]))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.5));
        }

        // Horoscope
        $sitemap->add(Url::create(route('horoscopes.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));
        $types = ['weekly','monthly', 'daily', date('Y')];
        foreach($types as $type){
            foreach($signs as $sign){
                $sitemap->add(Url::create(route('horoscopes.index', $type))
                        ->setLastModificationDate(Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.1));
                if(in_array($type, ['weekly','monthly','daily'])){
                    foreach(typeDWM() as $sl=>$sp){
                        $sitemap->add(Url::create(route('horoscopes.detail', [$type, $sign->slug, $sl]))
                            ->setLastModificationDate(Carbon::now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                            ->setPriority(0.1));
                    }
                }
                if(is_numeric($type)){
                    foreach(typeYear() as $sl=>$sp){
                        $sitemap->add(Url::create(route('horoscopes.yearly', [$type, $sign->slug, $sl, $type]))
                            ->setLastModificationDate(Carbon::now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                            ->setPriority(0.1));
                    }
                }
            }
        }
        // panchang
        $sitemap->add(Url::create(route('panchang.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));
        $subPages = Punchang::where('type','sub-page')->where('status',1)->select('slug')->get();
        foreach($subPages as $page){
            $sitemap->add(Url::create(route('panchang.index',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
        }
        //vastu
        $sitemap->add(Url::create(route('vastu.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));
        $subPages = Vastu::where('type','sub-page')->where('status',1)->select('slug')->get();
        foreach($subPages as $page){
            $sitemap->add(Url::create(route('vastu.index',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
        }
        // baby-names
        $sitemap->add(Url::create(route('baby-names.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1));

        // planets
        $sitemap->add(Url::create(route('planet.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));
        $planets = Planet::where('page_type','planet')->where('status',1)->select('slug', 'page_type')->get();
        foreach($planets as $page){
            $sitemap->add(Url::create(route('planet.page',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
            $pageTypes = false;
            if($page->slug=='transit' || $page->page_type=='transit') {
                $pageTypes = ['transit', 'grahan'];
            }
            elseif($page->slug=='retrograde'|| $page->page_type=='retrograde') {
                $pageTypes = ['retrograde'];
            }
            if($pageTypes){
                $subplanets = Planet::whereIn('page_type',$pageTypes)->where('status',1)->get();
                foreach($subplanets as $spage){
                    $sitemap->add(Url::create(in_array($page->slug, ['transit', 'retrograde']) ? route('planet.subpage', [$page->slug, $spage->slug])  : route('planet.subpage', [$page->page_type, $spage->slug]))
                        ->setLastModificationDate(Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8));
                }
            }
        }

         //palmreading
         $sitemap->add(Url::create(route('palmreading.index'))
         ->setLastModificationDate(Carbon::now())
         ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
         ->setPriority(0.1));
         $subPages = PalmReading::where('type','sub-page')->where('status',1)->select('slug')->get();
            foreach($subPages as $page){
                $sitemap->add(Url::create(route('palmreading.index',$page->slug))
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.1));
            }

        //Puja Vidhi
        $sitemap->add(Url::create(route('puja.index'))
         ->setLastModificationDate(Carbon::now())
         ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
         ->setPriority(0.1));
         $subPages = Puja::where('type','sub-page')->where('status',1)->select('slug')->get();
         foreach($subPages as $page){
             $sitemap->add(Url::create(route('puja.index',$page->slug))
                 ->setLastModificationDate(Carbon::now())
                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                 ->setPriority(0.1));
         }
        //  Mantra
        $sitemap->add(Url::create(route('mantra.index'))
         ->setLastModificationDate(Carbon::now())
         ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
         ->setPriority(0.1));
         $subPages = Mantra::where('type','sub-page')->where('status',1)->select('slug')->get();
         foreach($subPages as $page){
             $sitemap->add(Url::create(route('mantra.index',$page->slug))
                 ->setLastModificationDate(Carbon::now())
                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                 ->setPriority(0.1));
         }
         //  Chakra
        $sitemap->add(Url::create(route('chakra.index'))
         ->setLastModificationDate(Carbon::now())
         ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
         ->setPriority(0.1));
         $subPages = Chakra::where('type','sub-page')->where('status',1)->select('slug')->get();
         foreach($subPages as $page){
             $sitemap->add(Url::create(route('chakra.index',$page->slug))
                 ->setLastModificationDate(Carbon::now())
                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                 ->setPriority(0.1));
         }
         //  ColorTherapy
        $sitemap->add(Url::create(route('color.index'))
        ->setLastModificationDate(Carbon::now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(0.1));
        $subPages = ColorTherapy::where('type','sub-page')->where('status',1)->select('slug')->get();
        foreach($subPages as $page){
            $sitemap->add(Url::create(route('color.index',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
        }
         //  Wedding
         $sitemap->add(Url::create(route('wedding.index'))
         ->setLastModificationDate(Carbon::now())
         ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
         ->setPriority(0.1));
         $subPages = Wedding::where('type','sub-page')->where('sub_type','rituals')->where('status',1)->select('slug')->get();
         foreach($subPages as $page){
             $sitemap->add(Url::create(route('wedding.index',$page->slug))
                 ->setLastModificationDate(Carbon::now())
                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                 ->setPriority(0.1));
         }

        //  Remedie
        $sitemap->add(Url::create(route('remedies.index'))
        ->setLastModificationDate(Carbon::now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(0.1));
        $subPages = Remedie::where('type','sub-page')->where('status',1)->select('slug', 'id')->get();
        foreach($subPages as $page){
            $sitemap->add(Url::create(route('remedies.page',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
            $ChildsubPages = $page->childPages->where('status', 1);
            foreach($ChildsubPages as $cpage){
                $sitemap->add(Url::create(route('remedies.page',[$cpage->type,$cpage->slug]))
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.1));
            }
        }

        // chineseastrology
        $sitemap->add(Url::create(route('chineseastrology.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1));

        // Videos
        $sitemap->add(Url::create(route('video.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));
        foreach(['daily','weekly','yearly'] as $category){
        $sitemap->add(Url::create(route('video.category', $category))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));
        }

        //  Meditation
        $sitemap->add(Url::create(route('meditation.index'))
        ->setLastModificationDate(Carbon::now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(0.1));
        $subPages = Meditation::where('type','sub-page')->where('status',1)->select('slug')->get();
        foreach($subPages as $page){
            $sitemap->add(Url::create(route('meditation.index',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
        }

        //  Yoga
        $sitemap->add(Url::create(route('yoga.index'))
        ->setLastModificationDate(Carbon::now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(0.1));
        $subPages = Yoga::where('type','sub-page')->where('status',1)->select('slug')->get();
        foreach($subPages as $page){
            $sitemap->add(Url::create(route('yoga.index',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
        }
        //  Festival
        $sitemap->add(Url::create(route('festival.index'))
        ->setLastModificationDate(Carbon::now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(0.1));
        $subPages = Festival::where('type','sub-page')->where('status',1)->select('slug')->get();
        foreach($subPages as $page){
            $sitemap->add(Url::create(route('festival.index',$page->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.1));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
