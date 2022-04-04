<?php

namespace App\Models;

use App\Models\Traits\HasCountry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    use HasCountry;
}
