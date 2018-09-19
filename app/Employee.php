<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    protected $appends = ['current_bonus'];

    public static function totalSalaries(){
        $salariesTotal = DB::table('employees')
            ->join('employee_salary','employees.id', '=', 'employee_salary.employee_id')
            ->where('employee_salary.end',null)
            ->select(DB::raw('SUM(employee_salary.salary) as total'))
            ->first();

        return $salariesTotal->total ?? 0;
    }

    public static function totalBonuses(){
        $defaultBonus = Helpers::dbConfig('bonus');
        $bonusesTotal = DB::table('employees')
            ->join('employee_salary','employees.id', '=', 'employee_salary.employee_id')
            ->where('employee_salary.end',null)
            ->select(DB::raw('SUM(salary * IFNULL(employees.bonus,' . $defaultBonus . ') ) as total'))
            ->first();

        return $bonusesTotal->total ?? 0;
    }

    public function currentSalay(){
        return $this->hasOne(EmployeeSalary::class)->where('end',null);
    }

    public function getCurrentBonusAttribute(){
        return $this->bonus ?? Helpers::dbConfig('bonus');
    }
}
