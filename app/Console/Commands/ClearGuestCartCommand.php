<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CartItem;

class ClearGuestCartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:clearGuest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear guest cart after 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $cartItems = CartItem::whereRaw('user_id NOT REGEXP "^[0-9]+$"')->whereRaw('UNIX_TIMESTAMP(created_at) > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))')->delete();
      echo 'remove cart items';
    }
}
