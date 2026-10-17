<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\CommissionTransaction;
use App\Services\CommissionService;
use Carbon\Carbon;

class ProcessDelayedCommissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:process-delayed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process delayed commissions that are ready to be credited (day-based delay for refund_and_cancellation products)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processing delayed commissions...');

        // Get all pending delayed commissions with day-based delays
        // Check for all delayed_Xday patterns
        $pendingCommissions = CommissionTransaction::where('status', 'pending')
            ->where('credit_time', 'like', 'delayed_%day')
            ->get()
            ->filter(function($commission) {
                // Extract days from credit_time (e.g., 'delayed_1day' -> 1 day)
                preg_match('/delayed_(\d+)day/', $commission->credit_time, $matches);
                if(!isset($matches[1])) {
                    return false;
                }

                $days = (int)$matches[1];
                $createdAt = Carbon::parse($commission->created_at);
                $shouldCreditAt = $createdAt->copy()->addDays($days);

                // Check if the delay period has passed
                return Carbon::now()->gte($shouldCreditAt);
            });

        $pendingCommissions = $pendingCommissions->values(); // Re-index array

        if($pendingCommissions->isEmpty()) {
            $this->info('No delayed commissions ready to process.');
            return 0;
        }

        $this->info("Found {$pendingCommissions->count()} delayed commission(s) ready to process.");

        $processed = 0;
        $failed = 0;

        foreach($pendingCommissions as $commission) {
            try {
                $this->line("Processing commission ID: {$commission->id} for Order: {$commission->order_id}");

                // Credit the commission
                $result = CommissionService::creditCommission($commission);

                if($result) {
                    $processed++;
                    $this->info("✓ Commission ID {$commission->id} credited successfully.");
                } else {
                    $failed++;
                    $this->error("✗ Failed to credit commission ID {$commission->id}.");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Error processing commission ID {$commission->id}: " . $e->getMessage());
            }
        }

        $this->info("\nSummary:");
        $this->info("Processed: {$processed}");
        $this->info("Failed: {$failed}");

        return 0;
    }
}
