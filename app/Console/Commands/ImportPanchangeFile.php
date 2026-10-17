<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\File as FileModel;
use App\Model\{Panchang};
use File;

class ImportPanchangeFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:panchang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import panchang file.';

    /**
     * Execute the console command.
     */
    public function handle(){
      $fileModal = FileModel::where('is_processed', 0)->where('processed_at', null)->where('type', 'panchang')->first();
      $total = 0;
      $processed = 0;
      $file = public_path($fileModal->url);
      $delimiter = ',';
      $i = 1;
      if(($handle = fopen($file, 'r')) !== false){
        while(($row = fgetcsv($handle, 2000, $delimiter)) !== false){
          if($row[0]=='Date') continue;
          $total++;
          if($row[0] && $row[1] && $row[2]){
            $panchang = Panchang::where('date', $row[0])->first();
            if(!$panchang){
              $panchang = new Panchang();
              $panchang->date = $row[0];
              $panchang->english = $row[1];
              $panchang->hindi = $row[2];
              $panchang->status = 1;
              $panchang->save();
              $processed++;
            }
          }
          if($fileModal->is_processed == 0){
            $fileModal->is_processed = 1;
            $fileModal->save();
          }
        }
        fclose($handle);
        $fileModal->processed_at = date('Y-m-d H:i:s');
        $fileModal->total = $total;
        $fileModal->processed = $processed;
        $fileModal->save();
      }
      $this->info('DONE');
    }
}
