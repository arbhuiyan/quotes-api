<?php

namespace App\Quotes\Drivers;

interface QuotesDriver
{
    public function all(): array;
}
