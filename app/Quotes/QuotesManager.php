<?php

namespace App\Quotes;

use App\Quotes\Drivers\KayneQuotes;
use Illuminate\Support\Manager;

class QuotesManager extends Manager
{
    public function getDefaultDriver()
    {
        return $this->config['quotes.driver'] ?? 'kayne';
    }

    public function createKayneDriver()
    {
        return new KayneQuotes();
    }
}