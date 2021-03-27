<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicDate extends Model
{
  use HasFactory;

  protected $casts = [
    'start_date' => 'date:Y-m-d',
    'end_date' => 'date:Y-m-d'
  ];

  protected $fillable = [
    'semester',
    'start_date',
    'end_date'
  ];
}
