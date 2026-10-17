<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\DomesticPincode;

class UploadDoemsticPincodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:domestic-pincodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import domestic pincodes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = public_path('import/pincodes/domestic.csv');
        $delimiter = ',';
        $i = 1;
        if(($handle = fopen($file, 'r')) !== false){
            while(($row = fgetcsv($handle, 2000, $delimiter)) !== false){
                if($row[2]=='PINCODE') continue;
                if($row[2]!=''){
                  $pincode = DomesticPincode::where('pincode', $row[2])->first();
                  if(!$pincode){
                    $pincode = new DomesticPincode;
                    $pincode->city = $row[0];
                    $pincode->post_office = $row[1];
                    $pincode->pincode = $row[2];
                    $pincode->save();
                  }
                }
            }
            fclose($handle);
        }
        $this->info('DONE');
    }
}
