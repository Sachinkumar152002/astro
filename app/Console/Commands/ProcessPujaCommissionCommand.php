<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\PujaCommissionJob;
use App\Model\{PujaAstrologerAssignment, PujaMahurat};
use Carbon\Carbon;

class ProcessPujaCommissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'puja:process-commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process puja commissions after mahurat time has passed (2 minutes after mahurat)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get current datetime
        $now = Carbon::now();

        // Calculate the cutoff time (2 minutes ago from now)
        // This means we're looking for mahurats that passed at least 2 minutes ago
        $cutoffDateTime = $now->copy()->subMinutes(2);

        // Find assignments with mahurat that has passed (2 minutes after mahurat time)
        // and commission hasn't been paid yet
        $assignments = PujaAstrologerAssignment::with(['mahurat', 'product', 'orderItem'])
            ->where('commission_paid', 0)
            ->whereNotIn('status', ['cancelled', 'cancel'])
            ->whereHas('mahurat', function($query) use ($cutoffDateTime) {
                // Mahurat date + time should be <= cutoffDateTime (2 minutes ago)
                $query->whereNotNull('date')
                      ->whereNotNull('time')
                      ->whereRaw("CONCAT(date, ' ', time) <= ?", [$cutoffDateTime->format('Y-m-d H:i:s')]);
            })
            ->get();

        if($assignments->isEmpty()) {
            $this->info('No puja assignments found for commission processing.');
            return;
        }

        $this->info("Found {$assignments->count()} puja assignment(s) to process commission.");

        foreach($assignments as $assignment) {
            $mahurat = $assignment->mahurat;
            if($mahurat && $mahurat->date && $mahurat->time) {
                $mahuratDateTime = Carbon::parse($mahurat->date->format('Y-m-d') . ' ' . $mahurat->time);

                // Double check: mahurat should have passed at least 2 minutes ago
                $mahuratWithBuffer = $mahuratDateTime->copy()->addMinutes(2);
                if($mahuratWithBuffer->lte($now)) {
                    $this->info("Processing commission for assignment ID: {$assignment->id}");
                    PujaCommissionJob::dispatch($assignment);
                }
            }
        }

        $this->info('Commission processing jobs dispatched successfully.');
    }
}
