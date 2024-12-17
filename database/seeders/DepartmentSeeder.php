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
            Department::MANAGMENT_NAME,
            Department::MAD_NAME,
            Department::BDM_NAME,
            Department::PPL_NAME,
            Department::PR_NAME,
        ];

        $abbreviation = [
            Department::MANAGMENT_ABBREVIATION,
            Department::MAD_ABBREVIATION,
            Department::BDM_ABBREVIATION,
            Department::PPL_ABBREVIATION,
            Department::PR_ABBREVIATION,
        ];

        for ($i = 0; $i < count($name); $i++) {
            Department::create([
                'name' => $name[$i],
                'abbreviation' => $abbreviation[$i],
            ]);
        }
    }
}
