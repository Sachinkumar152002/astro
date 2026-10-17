<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\User;
use App\Model\Astrologer;

class UpdateUUIDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNull('uuid')->withTrashed()->get();
        foreach($users as $user){
          $user->uuid = 'IJS-'.date('Ym').'-C'.sprintf('%04d',$user->id);
          $user->save();
        }

        $astrologers = Astrologer::whereNull('uuid')->withTrashed()->get();
        foreach($astrologers as $user){
          $user->uuid = 'IJS-'.date('Ym').'-'.$user->type[0].sprintf('%04d',$user->id);
          $user->save();
        }
    }
}
