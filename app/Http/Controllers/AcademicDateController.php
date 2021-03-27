<?php

namespace App\Http\Controllers;

use App\Models\AcademicDate;
use Illuminate\Http\Request;

class AcademicDateController extends Controller
{

    public function updateAcademicDate(Request $request, AcademicDate $academicDate)
    {
        $request->validateWithBag('updateDateTime', [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date']
        ]);

        $academicDate->start_date = $request->get('start_date');
        $academicDate->end_date = $request->get('end_date');
        $academicDate->save();

        return back();
    }

}
