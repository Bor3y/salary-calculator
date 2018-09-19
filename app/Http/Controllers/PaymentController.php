<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Helpers;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    public function paymentDates(Request $request)
    {
//        return Employee::with('currentSalay')->find(5);
//        $month = intval(date('m'));
//        $request->validate([
//            'month' => 'integer|between:1,12|gte:'.$month,
//        ]);
//
//        if($request->has('month')){
//
//        }

        $months = [];
        $totalSalaries = Employee::totalSalaries();
        $totalBonuses = Employee::totalBonuses();
        $total = $totalSalaries + $totalBonuses;
        $currentMonth = intval(date('m'));

        for($m = $currentMonth;$m<=12;$m++){
            $monthName = date('F', mktime(0, 0, 0, $m, 10));
            $lastDay = date('D', strtotime('last day of ' . $monthName));
            $SalariesPaymentDay = date('d', strtotime('last day of ' . $monthName));
            if($lastDay == 'Fri'){
                $SalariesPaymentDay = date('d', strtotime('last day of ' . $monthName)) - 1;
            }else if ($lastDay == 'Sat'){
                $SalariesPaymentDay = date('d', strtotime('last day of ' . $monthName)) - 2;
            }
            $BonusPaymentDay = date('D', strtotime('15 ' . $monthName));
            if($BonusPaymentDay == 'Fri' || $BonusPaymentDay == 'Sat'){
                $BonusPaymentDay = date('d',strtotime('next Thursday',strtotime('15 ' . $monthName)));
            }else{
                $BonusPaymentDay = 15;
            }

            $months[] = [
                'Month' => $monthName,
                'Salaries_payment_day' => intval($SalariesPaymentDay),
                'Bonus_payment_day' => intval($BonusPaymentDay),
                'Salaries_total' => $totalSalaries,
                'Bonus_total' => $totalBonuses,
                'Payments_total' => $total
            ];
        }
        return $months;
    }
}

