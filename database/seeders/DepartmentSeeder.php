<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            Department::MGMT_NAME,
            Department::MAD_NAME,
            Department::CMD_NAME,
            Department::PLPD_NAME,
            Department::PRD_NAME,
            Department::PPDD_NAME,
            Department::ELD_NAME,
            Department::MSD_NAME,
            Department::DD_NAME,
        ];

        $abbreviation = [
            Department::MGMT_ABBREVIATION,
            Department::MAD_ABBREVIATION,
            Department::CMD_ABBREVIATION,
            Department::PLPD_ABBREVIATION,
            Department::PRD_ABBREVIATION,
            Department::PPDD_ABBREVIATION,
            Department::ELD_ABBREVIATION,
            Department::MSD_ABBREVIATION,
            Department::DD_ABBREVIATION,
        ];

        for ($i = 0; $i < count($name); $i++) {
            Department::create([
                'name' => $name[$i],
                'abbreviation' => $abbreviation[$i],
            ]);
        }
    }
}
