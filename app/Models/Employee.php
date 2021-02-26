<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\MenuEmployee;
use App\Traits\HasEmployeeAttribute ;

class Employee extends Authenticatable
{
    use HasFactory,HasEmployeeAttribute;

}
