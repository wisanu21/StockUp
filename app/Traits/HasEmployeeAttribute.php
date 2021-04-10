<?php

namespace App\Traits;
use App\Models\MenuEmployee;
use App\Models\Level;
use App\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasEmployeeAttribute {
    use SoftDeletes;
    public function getMenuEmployeesAttribute()
    {
        return MenuEmployee::where("employee_id", $this->id)
                            ->leftJoin('menus', 'menus.id', '=', 'menu_employees.menu_id')
                            ->orderBy('menus.list_no', 'ASC')
                            ->select("menu_employees.*")
                            ->get();
    }

    public function getFullNameAttribute()
    {
        return $this->first_name ." ".$this->last_name ;
    }

    public function getLevelAttribute()
    {
        return Level::where("id",$this->level_id)->first();

    }

    public function getCompanyAttribute()
    {
        return Company::where("id",$this->company_id)->first();
    }
}