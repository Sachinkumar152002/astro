<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GenerateSitemap;
use App\Console\Commands\ReminderConsultationCommand;
use App\Console\Commands\CallInitiateCommand;
use App\Console\Commands\ClearGuestCartCommand;
use App\Console\Commands\AppointmentAmountCommand;
use App\Console\Commands\AstrologerAppointmentAmountCommand;
use App\Console\Commands\RefundCancelAppointmentAmountCommand;
use App\Console\Commands\AstrologerDailyConsultationSummaryCommand;
use App\Console\Commands\ConsignmentTrackingCommand;
use App\Console\Commands\ChangeStatusLiveAstrologer;
use App\Console\Commands\OfflineAstrologerCommand;
use App\Console\Commands\AiAppointmentAmountCommand;
use App\Console\Commands\AstrolgerAutoPayoutCommand;
use App\Console\Commands\InsertProductGoogleMerchantCommand;
use App\Console\Commands\AstrologerLiveNotificationCommand;
use App\Console\Commands\ProcessPujaCommissionCommand;
use App\Console\Commands\RegenerateReferralCodesCommand;
use App\Console\Commands\ProcessDelayedCommissionsCommand;
use App\Console\Commands\PujaMahuratReminderCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateSitemap::class,
        ReminderConsultationCommand::class,
        CallInitiateCommand::class,
        ClearGuestCartCommand::class,
        AppointmentAmountCommand::class,
        AstrologerAppointmentAmountCommand::class,
        RefundCancelAppointmentAmountCommand::class,
        AstrologerDailyConsultationSummaryCommand::class,
        ConsignmentTrackingCommand::class,
        ChangeStatusLiveAstrologer::class,
        OfflineAstrologerCommand::class,
        AiAppointmentAmountCommand::class,
        AstrolgerAutoPayoutCommand::class,
        InsertProductGoogleMerchantCommand::class,
        AstrologerLiveNotificationCommand::class,
        ProcessPujaCommissionCommand::class,
        RegenerateReferralCodesCommand::class,
        ProcessDelayedCommissionsCommand::class,
        PujaMahuratReminderCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('sitemap:generate')->dailyAt('00:10');
        // $schedule->command('reminder:consultation')->everyMinute();
        // $schedule->command('consultation:call')->everyMinute();
        // $schedule->command('cart:clearGuest')->everyTenMinutes();
        $schedule->command('appointment:amount')->everyMinute();
        $schedule->command('appointment:astrologerAmount')->everyTenMinutes();
        $schedule->command('appointment:refundcancelamount')->everyMinute();
        // $schedule->command('appointment:astrologerSummary')->dailyAt('1:00');
        // $schedule->command('delhivery:tracking')->hourly();
        $schedule->command('astrologer:change-live-status-leaved')->everyMinute();
        $schedule->command('astrologer:offline')->everyThreeMinutes();
        $schedule->command('aiappointment:amount')->everyMinute();
        $schedule->command('astrologer:live-notification')->everyTwoMinutes();
        $schedule->command('puja:process-commission')->everyMinute();
        $schedule->command('commission:process-delayed')->everyMinute();
        $schedule->command('puja:mahurat-reminder')->everyMinute();
        $todayDate = date('d');
        $availableDate = [15, 30];
        $currentMonth = date('m');
        if($currentMonth==02){
          $availableDate = [15, 28];
        }
        if(in_array($todayDate, $availableDate)){
          $schedule->command('astrologer:autoPay')->dailyAt('08:00');
        }
        $schedule->command('google:insert-product')->dailyAt('10:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
