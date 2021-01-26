<?php

namespace Database\Seeders;

use App\Models\AcademicDate;
use Illuminate\Database\Seeder;

class AcademicDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDate::create([
          'semester' => 'Fall',
          'start_date' => '2021-09-01',
          'end_date' => '2021-12-23'
        ]);

      AcademicDate::create([
        'semester' => 'Winter',
        'start_date' => '2021-01-13',
        'end_date' => '2021-05-07'
      ]);

      AcademicDate::create([
        'semester' => 'Summer',
        'start_date' => '2021-05-10',
        'end_date' => '2021-08-28'
      ]);
    }
}
