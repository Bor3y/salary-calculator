<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->addAmin();
        $this->addEmployees(15);
        $this->addDefaultConfig();
    }

    private function addAmin(){
        $admin = new \App\User();
        $admin->name = "admin";
        $admin->email = "admin@xyz.com";
        $admin->password = Hash::make("secret");
        $admin->save();
    }

    private function addDefaultConfig(){
        DB::table('config')->insert([
            ['key' => 'bonus', 'value' => 0.10],
        ]);
    }

    private function addEmployees($num){
        $faker = Faker\Factory::create();
        for($i=0;$i<$num;$i++){
            $employee = new \App\Employee();
            $employee->email = $faker->safeEmail;
            $employee->name = $faker->name;
            $employee->save();
            if($i%3 == 0){
                $employeeSalary = new \App\EmployeeSalary();
                $employeeSalary->employee_id = $employee->id;
                $employeeSalary->salary = $faker->randomFloat(2,5000,100000);
                $employeeSalary->start = $faker->dateTimeBetween('-20 years','-2 years');
                $employeeSalary->end = $faker->dateTimeBetween('-2 years','now');
                $employeeSalary->save();
            }

            $employeeSalary = new \App\EmployeeSalary();
            $employeeSalary->employee_id = $employee->id;
            $employeeSalary->salary = $faker->randomFloat(2,5000,100000);
            $employeeSalary->start = $faker->dateTimeBetween('-5 years','-1 years');
            $employeeSalary->save();
        }
    }
}
