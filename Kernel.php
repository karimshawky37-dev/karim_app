<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // بدء الورديات التلقائي كل يوم في 9:00 صباحاً
        $schedule->command('shift:auto-start')->dailyAt('09:00');

        // إنهاء الورديات التلقائي كل يوم في 5:00 مساءً
        $schedule->command('shift:auto-end')->dailyAt('17:00');

        // نسخ احتياطي لقاعدة البيانات يومياً
        $schedule->command('backup:run')->daily();

        // تنظيف الملفات المؤقتة كل أسبوع
        $schedule->command('storage:clean')->weekly();

        // تحديث معادلة الاستثمار (تأكيد) كل ساعة
        $schedule->command('investment:recalculate')->hourly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}