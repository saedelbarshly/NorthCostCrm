<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafesBanks extends Model
{
    //
    protected $guarded = [];
    public function revenues()
    {
        return $this->hasMany(Revenues::class,'safe_id');
    }
    public function transfeersIn()
    {
        return $this->hasMany(MoneyTransfeers::class,'to_safe_id');
    }
    public function transfeersOut()
    {
        return $this->hasMany(MoneyTransfeers::class,'from_safe_id');
    }
    public function revenuesWithOutTransfeers()
    {
        $amount = 0;
        if ($this->revenues()->where('Type','transfeerFromAnother')->count() > 0) {
            $amount = $this->revenues()->where('Type','transfeerFromAnother')->sum('amount');
        }
        return $amount;
    }
    public function expenses()
    {
        return $this->hasMany(Expenses::class,'safe_id');
    }
    public function expensesWithOutTransfeers()
    {
        $amount = 0;
        if ($this->expenses()->where('Type','transfeerToAnother')->count() > 0) {
            $amount = $this->expenses()->where('Type','transfeerToAnother')->sum('Expense');
        }
        return $amount;
    }
    public function salaryRequests()
    {
        return $this->hasMany(SalaryRequest::class,'safe_id');
    }
    public function totals()
    {
        $totals = [
            'revenues' => $this->revenues()->sum('amount'),
            'transfeersIn' => $this->transfeersIn()->sum('amount'),
            'transfeersOut' => $this->transfeersOut()->sum('amount'),
            'expenses' => $this->expenses()->sum('Expense'),
            'salaries' => $this->salaryRequests()->sum('DeliveredSalary')
        ];
        $totals['income'] = $totals['revenues'];
        $totals['singleTotal'] = $totals['revenues'] + $totals['transfeersIn'] - $totals['transfeersOut'];
        $totals['outcome'] = $totals['expenses'] + $totals['salaries'];
        $totals['balance'] = $totals['singleTotal'] - $totals['outcome'];
        return $totals;
    }
    public function TypeText()
    {
        return safeTypes(session()->get('Lang'))[$this->Type];
    }
    public function branch()
    {
        return $this->belongsTo(Branches::class,'branch_id');
    }
}
