<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\{PujaMahurat, PujaAstrologerAssignment};
use App\Jobs\PujaMahuratReminderJob;
use Carbon\Carbon;

class PujaMahuratReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'puja:mahurat-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for puja mahurats (4hrs, 2hrs, 1hr before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Get all mahurats that haven't passed yet and have assignments with one-to-one puja
        $mahurats = PujaMahurat::with(['assignment.product', 'assignment.order.user'])
            ->whereNotNull('date')
            ->whereNotNull('time')
            ->whereHas('assignment', function($query) {
                $query->whereHas('product', function($q) {
                    $q->where('is_one_to_one_puja', 1);
                });
            })
            ->get();

        foreach($mahurats as $mahurat) {
            if(!$mahurat->assignment || !$mahurat->assignment->order || !$mahurat->assignment->order->user) {
                continue;
            }

            // Format date properly (date is cast to date, so format it as Y-m-d)
            $dateString = $mahurat->date instanceof \Carbon\Carbon
                ? $mahurat->date->format('Y-m-d')
                : (is_string($mahurat->date) ? $mahurat->date : date('Y-m-d', strtotime($mahurat->date)));

            // Format time properly
            $timeString = $mahurat->time;
            if($timeString instanceof \Carbon\Carbon) {
                $timeString = $timeString->format('H:i:s');
            } elseif(is_string($timeString)) {
                // If time is already a string, use it as is (could be H:i or H:i:s)
                $timeString = $timeString;
            } else {
                continue; // Skip if time is invalid
            }

            // Parse the combined date and time (try with seconds first, then without)
            try {
                if(strlen($timeString) > 5) {
                    // Has seconds (H:i:s format)
                    $mahuratDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ' ' . $timeString);
                } else {
                    // No seconds (H:i format)
                    $mahuratDateTime = Carbon::createFromFormat('Y-m-d H:i', $dateString . ' ' . $timeString);
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing mahurat datetime: ' . $e->getMessage() . ' - Date: ' . $dateString . ' Time: ' . $timeString);
                continue;
            }

            // Skip if mahurat has already passed
            if($mahuratDateTime->isPast()) {
                continue;
            }

            $minutesUntilMahurat = $now->diffInMinutes($mahuratDateTime, false);

            // Check for 4 hours reminder (between 4hrs 1min and 4hrs 0min before)
            // 4 hours = 240 minutes, check between 241 and 240 minutes
            if($minutesUntilMahurat >= 240 && $minutesUntilMahurat < 241 && !$mahurat->reminder_4hr_sent) {
                PujaMahuratReminderJob::dispatch($mahurat, 4);
                $mahurat->reminder_4hr_sent = true;
                $mahurat->save();
            }

            // Check for 2 hours reminder (between 2hrs 1min and 2hrs 0min before)
            // 2 hours = 120 minutes, check between 121 and 120 minutes
            if($minutesUntilMahurat >= 120 && $minutesUntilMahurat < 121 && !$mahurat->reminder_2hr_sent) {
                PujaMahuratReminderJob::dispatch($mahurat, 2);
                $mahurat->reminder_2hr_sent = true;
                $mahurat->save();
            }

            // Check for 1 hour reminder (between 1hr 1min and 1hr 0min before)
            // 1 hour = 60 minutes, check between 61 and 60 minutes
            if($minutesUntilMahurat >= 60 && $minutesUntilMahurat < 61 && !$mahurat->reminder_1hr_sent) {
                PujaMahuratReminderJob::dispatch($mahurat, 1);
                $mahurat->reminder_1hr_sent = true;
                $mahurat->save();
            }
        }

        $this->info('Puja mahurat reminder check completed.');
    }
}
