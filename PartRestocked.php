<?php

namespace App\Events;

use App\Models\Inventory;
use Illuminate\Foundation\Events\Dispatchable;

class PartRestocked
{
    use Dispatchable;

    public function __construct(public Inventory $inventory, public int $quantityAdded)
    {
    }
}