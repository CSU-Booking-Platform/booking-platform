<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicDate extends Model
{
    use HasFactory;

  protected $fillable = [
    'semester',
    'start_date',
    'end_date'
  ];
}
