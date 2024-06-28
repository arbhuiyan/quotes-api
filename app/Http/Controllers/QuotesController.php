<?php

namespace App\Http\Controllers;

use App\Quotes\Quotes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QuotesController extends Controller
{
    public function index(Request $request)
    {
        $cache = Cache::has('quotes');

        if (!$cache
            || filter_var($request->get('refresh', false), FILTER_VALIDATE_BOOLEAN)
        ) {
            Log::debug('Quotes: fetching quotes');

            $quotes = Quotes::all();
            Cache::put('quotes', $quotes, 3600);
        } else {
            Log::debug('Quotes: using cache');
            $quotes = Cache::get('quotes');
        }

        return JsonResource::make(Arr::random($quotes, 5));
    }
}
