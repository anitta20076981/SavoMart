<?php

namespace App\Repositories\Currency;

use App\Models\Currency;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function getCurrency($id)
    {
        return Currency::find($id);
    }
}
