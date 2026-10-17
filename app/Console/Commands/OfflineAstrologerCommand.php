<?php

namespace App\Console\Commands;

use App\Model\Astrologer;

use Illuminate\Console\Command;

class OfflineAstrologerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'astrologer:offline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make astrologer offline who in active before more than five minutes from now.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $astrologers=Astrologer::where("type", "Astrologer")
      ->where(function($query){
        $query->orWhere('last_logined', null)
        ->orWhere('last_logined', "<", date("Y-m-d H:i:s", strtotime("now-300seconds")));
      })
      // ->where(function($query){
      //   $query->where('chat', 1)
      //   ->orWhere('call', 1)
      //   ->orWhere('video', 1);
      // })
      ->get();
      $astrologers->map(function($item){
        if($item->chat>0){
          $item->chat=2;
        }
        if($item->call>0){
          $item->call=2;
        }
        if($item->video>0){
          $item->video=2;
        }
        $item->save();
      });
    }
}
