<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Currency extends Model
{
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
     * Convert the given price from the specified currency to USD.
     *
     * @param float $price The price to convert.
     * @param string $currencyName The name of the currency to convert from.
     * @return float The converted price in USD.
     */
    public static function convertPriceToUSD(float $price, string $currencyName): float
    {
        // Retrieve the currency information from the database
        $currency = self::where('name', $currencyName)->first();

        // If the currency is found, calculate the converted price
        if ($currency) {
            $converted = $price * $currency->usd_ratio;
            return $converted;
        } else {
            // If the currency is not found, return the original price
            return $price;
        }
    }
}
