<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\LocalPincode;

class UploadPincodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pincodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to upload pincodes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = public_path('import/pincodes/local.csv');
        $delimiter = ',';
        $i = 1;
        if(($handle = fopen($file, 'r')) !== false){
            while(($row = fgetcsv($handle, 2000, $delimiter)) !== false){
                if($row[2]=='PINCODE') continue;
                if($row[2]!=''){
                  $pincode = LocalPincode::where('pincode', $row[2])->first();
                  if(!$pincode){
                    $pincode = new LocalPincode;
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
