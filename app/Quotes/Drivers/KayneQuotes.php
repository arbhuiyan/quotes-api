<?php

namespace App\Quotes\Drivers;

use Illuminate\Support\Facades\Http;

class KayneQuotes implements QuotesDriver
{
    public function all(): array
    {
        // todo: need improvements! Not ideal for large dataset

        return Http::get('https://api.kanye.rest/quotes')->json();
    }
}