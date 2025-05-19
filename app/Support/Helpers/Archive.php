<?php

namespace App\Support\Helpers;

use App\Models\Atx;
use App\Models\Inn;
use App\Models\Product;
use App\Models\ProductForm;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Helper class which stores all used custom functions,
 * which may come in handy in the future.
 *
 * @author Bobur Nuridinov
 */
class Archive
{
    public static function addAtxesFromExcel()
    {
        $file = public_path('atxes.xlsx');
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $records = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            $records[] = [
                'inn' => $sheet->getCell('A' . $row)->getValue(),
                'form' => $sheet->getCell('B' . $row)->getValue(),
                'atx' => $sheet->getCell('C' . $row)->getValue(),
                'short_atx' => $sheet->getCell('D' . $row)->getValue(),
            ];
        }

        foreach ($records as $record) {
            $inn = Inn::where('name', $record['inn'])->first();
            $form = ProductForm::where('name', $record['form'])->first();

            if (!$inn || !$form) continue;

            Atx::create([
                'name' => $record['atx'],
                'short_name' => $record['short_atx'],
                'inn_id' => $inn->id,
                'form_id' => $form->id,
            ]);
        }
    }

    public static function validateProductAtxes()
    {
        Product::chunk(500, function ($products) {
            foreach ($products as $product) {
                $atx = Atx::where('inn_id', $product->inn_id)
                    ->where('form_id', $product->form_id)
                    ->first();

                if ($atx) {
                    $product->timestamps = false;
                    $product->atx_id = $atx->id;
                    $product->saveQuietly();
                } else {
                    $product->timestamps = false;
                    $product->atx_id = null;
                    $product->saveQuietly();
                }
            }
        });
    }
}
