<?php

namespace App\Quotes;

use Illuminate\Support\Facades\Facade;

class Quotes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'quotes';
    }
}