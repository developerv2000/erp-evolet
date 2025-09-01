<?php

namespace Database\Seeders;

use App\Models\InvoiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            InvoiceType::PRODUCTION_TYPE_NAME,
            InvoiceType::DELIVERY_TO_WAREHOUSE_TYPE_NAME,
            InvoiceType::EXPORT_TYPE_NAME,
        ];

        for ($i = 0; $i < count($name); $i++) {
            $item = new InvoiceType();
            $item->name = $name[$i];
            $item->save();
        }
    }
}
