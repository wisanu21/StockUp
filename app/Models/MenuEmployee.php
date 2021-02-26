<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class MenuEmployee extends Model
{
    use HasFactory;

    public function getMenuAttribute()
    {   
        $menu = Menu::where("id", $this->menu_id)->first();
        return $menu ;
    }
}
