<?php

namespace Tests\Feature;

use App\Models\AcademicDate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicDateControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_edit_academic_dates()
    {
        $academicDate = AcademicDate::create([
            'semester' => 'Fall',
            'start_date' => '2020-11-02',
            'end_date' => '2020-11-03'
        ]);

        $response = $this->actingAs($this->createUserWithPermissions(['settings.edit']))->post('admin/settings/academic_date/' . $academicDate->id, [
            'start_date' => '2020-11-08',
            'end_date' => '2020-11-12'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('academic_dates', ['start_date' => '2020-11-08', 'end_date' => '2020-11-12']);
    }
}
