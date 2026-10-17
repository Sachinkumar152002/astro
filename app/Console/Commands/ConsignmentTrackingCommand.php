<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\OrderItem;
use App\Model\Invoice;
use App\Model\OrderStatus;
use App\Model\Consignment;
use App\Model\ApiLog;
use App\Jobs\ConsignmentTrackingJob;

class ConsignmentTrackingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delhivery:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tracking delhivery status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $consignments = Consignment::where('status', 'success')
        ->whereNotNull('waybill')
        ->whereHas('orders', function($q){
          $q->whereNotIn('order_status', ['delivered', 'rto', 'pending', 'cancel']);
        })
        ->orderBy('id', 'desc')->get();
        if(count($consignments)){
          foreach($consignments as $consignment){
            ConsignmentTrackingJob::dispatch($consignment);
          }
        }
    }
}
