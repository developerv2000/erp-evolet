<?php

namespace App\Models;

use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Currency extends Model
{
    use ScopesOrderingByName;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const EXCHANGE_RATE_API_URL = 'https://v6.exchangerate-api.com/v6/2b3965359716e1bb35e7a237/latest/';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Update all currencies 'usd_ratio' using an external API.
     *
     * This method is used for updating currencies 'usd_ratio' via a cron job every day.
     *
     * @return void
     */
    public static function updateAllUSDRatios()
    {
        self::where('name', '!=', 'USD')->each(function ($record) {
            $response = Http::get(self::EXCHANGE_RATE_API_URL . $record->name);
            $record->usd_ratio = ($response->json())['conversion_rates']['USD'];
            $record->save();
        });
    }

    /**
     * Convert a given price from the specified currency to USD.
     *
     * @param float $price The price to be converted.
     * @param Currency|null $currency The currency to convert from. If null, the original price is returned.
     * @return float The converted price in USD.
     */
    public static function convertPriceToUSD(float $price, ?Currency $currency): float
    {
        // If a valid currency is provided, perform the conversion using its USD ratio.
        if ($currency && $currency->usd_ratio > 0) {
            return $price * $currency->usd_ratio;
        }

        // If no currency is provided or USD ratio is invalid, return the original price.
        return $price;
    }
}
