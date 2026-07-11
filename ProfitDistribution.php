<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitDistribution extends Model
{
    protected $fillable = ['partner_id', 'financial_summary_id', 'ownership_percentage', 'share_amount'];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function summary()
    {
        return $this->belongsTo(FinancialSummary::class);
    }
}