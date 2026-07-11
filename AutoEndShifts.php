<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ShiftService;

class AutoEndShifts extends Command
{
    protected $signature = 'shift:auto-end';
    protected $description = 'إنهاء الورديات المفتوحة تلقائياً';

    protected $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        parent::__construct();
        $this->shiftService = $shiftService;
    }

    public function handle()
    {
        $this->shiftService->autoEndShifts();
        $this->info('تم إنهاء الورديات التلقائية بنجاح.');
    }
}