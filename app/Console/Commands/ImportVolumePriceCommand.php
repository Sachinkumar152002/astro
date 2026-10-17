<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\ShippingVolumePrice;

class ImportVolumePriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:volumePrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to upload weight prices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = public_path('import/prices/volume.csv');
        $delimiter = ',';
        $i = 1;
        if(($handle = fopen($file, 'r')) !== false){
            while(($row = fgetcsv($handle, 2000, $delimiter)) !== false){
                if($row[4]=='National price') continue;
                if($row[0] && $row[1] && $row[2] && $row[3] && $row[4]){
                  $price = ShippingVolumePrice::where('volume_from', $row[0])->where('volume_to', $row[1])->first();
                  if(!$price){
                    $price = new ShippingVolumePrice;
                    $price->weight_from = $row[0];
                    $price->weight_to = $row[1];
                    $price->local_price = $row[2];
                    $price->domestic_price = $row[3];
                    $price->national_price = $row[4];
                    $price->save();
                  }
                }
            }
            fclose($handle);
        }
        $this->info('DONE');
    }
}
