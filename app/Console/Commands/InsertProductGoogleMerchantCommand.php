<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{Product};

class InsertProductGoogleMerchantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:insert-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $fileName = "product.xml";
      $filePath = public_path("google/{$fileName}");
      if(!file_exists($filePath)){
        $filePath = public_path('google');
        if(!file_exists($filePath)){
          mkdir(public_path('google'), 0755, true);
        }
      }
      $filePath = public_path("google/{$fileName}");
      $content = '<?xml version="1.0"?>
      <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
      <channel>
      <title>Google Store</title>
      <link>https://store.google.com</link>
      <description>This is an example of a basic RSS 2.0 document containing a single item</description>
      <item>';
      $page = 0;
      $take = 100;
      $content .= $this->productXMLData($page, $take, $content);
      $content .= '</item>
      </channel>
      </rss>';
      file_put_contents($filePath, $content);
      return $filePath;
    }

    public function productXMLData($page, $take, $content){
      $productTypes = ["", "Product", "Pooja", "Service"];
      $todayDate = date("Y-m-d");
      $products = Product::where("status", 1)->where("type", 1)->take($take)->offset($page*$take)->get();
      if ($products->count() > 0) {
        $imageLink = no_image();
        foreach ($products as $index => $item) {
          $title = $description = $item->title;
          $specifications = $item->specifications;
          if($specifications){
            $description = $specifications->description;
          }
          $image = $item->image;
          if($image){
            $imageLink = $image->image();
          }
          $category = $item->category;
          $link = route('product.detail', [$productTypes[$item->type], $category->slug, $item->slug]);
          $itemSpecialPriceFromDate = $item->sale_price_start_date;
          $itemSpecialPriceToDate = $item->sale_price_end_date;
          $price = $item->base_price;
          if ($itemSpecialPriceFromDate && ($itemSpecialPriceFromDate <= $todayDate) && ($itemSpecialPriceToDate >= $todayDate) ) {
            $price = $item->sale_price;
          }
          $content .= "<g:id>{$item->id}</g:id>";
          $content .= "<g:title>{$title}</g:title>";
          $content .= "<g:description>{$description}</g:description>";
          $content .= "<g:link>{$link}</g:link>";
          $content .= "<g:image_link>{$imageLink}</g:image_link>";
          $content .= "<g:availability>{$item->availability}</g:availability>";
          $content .= "<g:price>{$price} RS</g:price>";
          $content .= "<g:shipping>";
          $content .= "<g:country>IN</g:country>";
          $content .= "<g:service>Standard</g:service>";
          $content .= "<g:price>{$price} RS</g:price>";
          $content .= "</g:shipping>";
        }
        $content = self::productXMLData($page+1, $take, $content);
      }
      return $content;
    }
}
