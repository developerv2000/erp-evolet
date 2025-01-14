<?php

namespace Database\Seeders;

use App\Models\ProcessGeneralStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessGeneralStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = ['1ВП', '2ПО', '3АЦ', '4СЦ', '5Кк', '6КД', '7НПР', '8Р', '9Зя', '10Отмена'];
        $nameForAnalysts = ['1ВП', '2ПО', '3АЦ', '4СЦ', '5Кк', '5Кк', '5Кк', '5Кк', '5Кк', '5Кк'];
        $stage = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $requiresPermission = [0, 0, 0, 0, 0, 1, 1, 1, 1, 1];

        for ($i = 0; $i < count($name); $i++) {
            $record = new ProcessGeneralStatus();
            $record->name = $name[$i];
            $record->name_for_analysts = $nameForAnalysts[$i];
            $record->stage = $stage[$i];
            $record->requires_permission = $requiresPermission[$i];
            $record->save();
        }
    }
}
