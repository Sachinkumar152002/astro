<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegenerateReferralCodesCommand extends Command
{
    protected $signature = 'referral:regenerate-codes {--force : Run without confirmation} {--chunk=500 : Chunk size}';
    protected $description = 'Regenerate referral codes for all users & astrologers and update references.';

    public function handle(): int
    {
        if(!$this->option('force')) {
            $this->error('Refusing to run without --force (this changes all referral codes).');
            return self::FAILURE;
        }

        $chunk = (int) $this->option('chunk');
        if($chunk < 50) $chunk = 50;

        $this->info('Starting referral code regeneration...');

        try {
            if(!Schema::hasTable('referral_code_mappings')) {
                $this->error('Missing table: referral_code_mappings. Run migrations first.');
                return self::FAILURE;
            }

            // Avoid TRUNCATE here (can implicitly commit transactions on MySQL).
            DB::table('referral_code_mappings')->delete();

            $this->regenerateForTable('users', 'user', 'C', $chunk);
            $this->regenerateForTable('astrologers', 'astrologer', 'P', $chunk);

            $this->info('Updating references (used_referral_code, visits, orders, suggestions)...');

            // used_referral_code in users
            if(Schema::hasColumn('users', 'used_referral_code')) {
                DB::statement("
                    UPDATE users u
                    JOIN referral_code_mappings m ON u.used_referral_code = m.old_code
                    SET u.used_referral_code = m.new_code
                    WHERE u.used_referral_code IS NOT NULL AND u.used_referral_code != ''
                ");
            }

            // used_referral_code in astrologers
            if(Schema::hasColumn('astrologers', 'used_referral_code')) {
                DB::statement("
                    UPDATE astrologers a
                    JOIN referral_code_mappings m ON a.used_referral_code = m.old_code
                    SET a.used_referral_code = m.new_code
                    WHERE a.used_referral_code IS NOT NULL AND a.used_referral_code != ''
                ");
            }

            // referral_visits.referral_code
            if(Schema::hasTable('referral_visits') && Schema::hasColumn('referral_visits', 'referral_code')) {
                DB::statement("
                    UPDATE referral_visits v
                    JOIN referral_code_mappings m ON v.referral_code = m.old_code
                    SET v.referral_code = m.new_code
                    WHERE v.referral_code IS NOT NULL AND v.referral_code != ''
                ");
            }

            // orders.referral_code (used in remedies flow)
            if(Schema::hasTable('orders') && Schema::hasColumn('orders', 'referral_code')) {
                DB::statement("
                    UPDATE orders o
                    JOIN referral_code_mappings m ON o.referral_code = m.old_code
                    SET o.referral_code = m.new_code
                    WHERE o.referral_code IS NOT NULL AND o.referral_code != ''
                ");
            }

            // astrologer_remedy_suggestions.referral_code
            if(Schema::hasTable('astrologer_remedy_suggestions') && Schema::hasColumn('astrologer_remedy_suggestions', 'referral_code')) {
                DB::statement("
                    UPDATE astrologer_remedy_suggestions s
                    JOIN referral_code_mappings m ON s.referral_code = m.old_code
                    SET s.referral_code = m.new_code
                    WHERE s.referral_code IS NOT NULL AND s.referral_code != ''
                ");
            }

            $this->info('Done. All referral codes regenerated and references updated.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function regenerateForTable(string $table, string $entityType, string $prefix, int $chunk): void
    {
        $this->info("Regenerating {$entityType} codes ({$table})...");

        DB::table($table)
            ->orderBy('id')
            ->chunkById($chunk, function ($rows) use ($table, $entityType, $prefix) {
                foreach ($rows as $row) {
                    $old = $row->referral_code ?? null;
                    $new = $this->generateUniqueCode($prefix);

                    DB::table('referral_code_mappings')->insert([
                        'entity_type' => $entityType,
                        'entity_id' => $row->id,
                        'old_code' => $old,
                        'new_code' => $new,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table($table)->where('id', $row->id)->update([
                        'referral_code' => $new,
                        'updated_at' => now(),
                    ]);
                }
            }, 'id');
    }

    private function generateUniqueCode(string $prefix): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $maxAttempts = 2000;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $suffix = '';
            for ($i = 0; $i < 5; $i++) {
                $suffix .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $code = $prefix . $suffix;

            $existsInUsers = DB::table('users')->where('referral_code', $code)->exists();
            $existsInAstrologers = DB::table('astrologers')->where('referral_code', $code)->exists();
            $existsInMappings = DB::table('referral_code_mappings')->where('new_code', $code)->exists();

            if(!$existsInUsers && !$existsInAstrologers && !$existsInMappings) {
                return $code;
            }
        }

        throw new \RuntimeException("Unable to generate unique code for prefix {$prefix} after {$maxAttempts} attempts.");
    }
}
