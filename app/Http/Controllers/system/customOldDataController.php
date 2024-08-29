<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EntriesOperations;
use App\ContractPayments;
use App\Models\ClientContracts;
use App\Expenses;
use App\Revenues;
use App\Models\User;
use App\Models\SalariesDeductions;
use App\MoneyTransfeers;

class customOldDataController extends Controller
{
    //
    // public function ConvertPaymentsToRevenues()
    // {
    //     $OldPayments = ContractPayments::orderBy('id','asc')->get();
    //     foreach ($OldPayments as $key => $value) {
    //         $stringArr = explode('/',$value['PaymentDate']);
    //         // return $value['PaymentDate'];
    //         $data = [
    //             'UID' => $value['UID'],
    //             'LinkedID' => $value['ContractID'],
    //             'Type' => $value['Type'] == 'InAdvance' ? 'contract_inAdvance' : 'contract_payment',
    //             'amount' => $value['Amount'],
    //             'Notes' => $value['notes'],
    //             'Date' => $stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1],
    //             'DateStr' => strtotime( $stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1]),
    //             'month' =>  $stringArr[0],
    //             'year' =>  $stringArr[2],
    //             'safe_id' => $value['SafeID'],
    //             'branch_id' => 1
    //         ];
    //         $revenue = Revenues::create($data);
    //     }
    // }
    public function ConvertTransfers()
    {
        $safesAccountsArr = [1632,1633,1635,1691,1739,1749,1778,1783];
        $safesIds = [
            1632 => 1,
            1633 => 2,
            1635 => 3,
            1691 => 4,
            1739 => 5,
            1749 => 6,
            1778 => 7,
            1783 => 8
        ];
        $OldFunds = EntriesOperations::where('Type','transfeer')->orderBy('id','asc')->get();
        // return $OldFunds;
        $arr = [];
        $newArr = [];
        foreach ($OldFunds as $key => $value) {
            $entries = $value->Entries()->whereIn('accounts_tree_id',$safesAccountsArr)->get();
            $arr[] = [
                'operation' => $value,
                'entries' => $entries,
            ];
            $from = '';
            $to = '';
            foreach ($entries as $entry) {
                $stringArr = explode('/',$entry['PaymentDate']);
                if ($entry->Debit != null) {
                    if (isset($safesIds[$entry['accounts_tree_id']])) {
                        $from = $entry;
                    } else {
                        $arr[] = [
                            'D-Entry' => $entry
                        ];

                    }
                }
                if ($entry->Credit != null) {
                    if (isset($safesIds[$entry['accounts_tree_id']])) {
                        $to = $entry;
                    } else {
                        $arr[] = [
                            'C-Entry' => $entry
                        ];

                    }
                }
            }
            if ($from != '' && $to != '') {
                $newArr[] = [
                    'to' => $from,
                    'from' => $to
                ];
            }
            //مسحوبات شراء السيارة
            // else {
            //     if ($from == '') {
            //         $stringArr = explode('/',$to['PaymentDate']);
            //         $data = [
            //             'UID' => 1,
            //             'Type' => 'withdrawal',
            //             'Expense' => $to['Credit'],
            //             'Des' => $to['Statement'],
            //             'ExpenseDate' => $stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1],
            //             'month' =>  $stringArr[0],
            //             'year' =>  $stringArr[2],
            //             'safe_id' => $safesIds[$to['accounts_tree_id']],
            //             'branch_id' => 1
            //         ];
            //         $expense = Expenses::create($data);
            //     }
            // }

        }
        foreach ($newArr as $key => $value) {
            $from = $value['from'];
            $to = $value['to'];
            $stringArr = explode('/',$to['PaymentDate']);

            $data = [
                'from_safe_id' => $safesIds[$from['accounts_tree_id']],
                'to_safe_id' => $safesIds[$to['accounts_tree_id']],
                'amount' => $to['Debit'],
                'date' => $stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1],
                'month' =>  $stringArr[0],
                'year' =>  $stringArr[2],
                'user_id' => 1,
                'notes' => $from['Statement']
            ];
            $MoneyTransfeers = MoneyTransfeers::create($data);
        }
        // return $newArr;
    }

    public function refineExpensesDays()
    {
        $expenses = Expenses::orderBy('id','asc')->get();
        foreach ($expenses as $key => $value) {
            $stringArr = explode('/',$value['ExpenseDate']);
            // $data = [
            //     'UID' => $value['UID'],
            //     'LinkedID' => $value['ContractID'],
            //     'Type' => $value['Type'] == 'InAdvance' ? 'contract_inAdvance' : 'contract_payment',
            //     'amount' => $value['Amount'],
            //     'Notes' => $value['notes'],
            //     'Date' => date('Y-m-d',strtotime(str_replace("/","-",$value['PaymentDate']))),
            //     'DateStr' => strtotime( date('Y-m-d',strtotime(str_replace("/","-",$value['PaymentDate'])))),
            //     'month' =>  date('m',strtotime(str_replace("/","-",$value['PaymentDate']))),
            //     'year' =>  date('Y',strtotime(str_replace("/","-",$value['PaymentDate']))),
            //     'safe_id' => $value['SafeID'],
            //     'branch_id' => 1
            // ];
            if (isset($stringArr[2])) {
                $revenue = Expenses::find($value['id'])->update([
                    'ExpenseDate'=>$stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1],
                    'safe_id' => $value->SafeID
                ]);
            }
        }
    }

    public function refineUserEmploymentDate()
    {
        $users = User::orderBy('id','asc')->get();
        foreach ($users as $key => $value) {
            $stringArr = explode('/',$value['employment_date']);
            if (isset($stringArr[2])) {
                $User = User::find($value['id'])->update([
                    'employment_date'=>$stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1]
                ]);
            }
        }
    }

    public function refineUserDeductions()
    {
        $deductions = SalariesDeductions::orderBy('id','asc')->get();
        foreach ($deductions as $key => $value) {
            // $stringArr = explode('/',$value['DeductionDate']);
            // if (isset($stringArr[2])) {
            //     $deduction = SalariesDeductions::find($value['id'])->update([
            //         'DeductionDate'=>$stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1]
            //     ]);
            // }
            $type = $value->Type;
            if ($value->Type == 'managerDiscount') {
                $type = 'management';
            }
            if ($value->Type == 'monthlyReward') {
                $type = 'reward';
            }
            $deduction = SalariesDeductions::find($value['id'])->update([
                'Type'=>$type,
                'month' => date('m',strtotime($value['DeductionDate'])),
                'year' => date('Y',strtotime($value['DeductionDate']))
            ]);
        }
    }

    public function refineContractPeyments()
    {
        $payments = ContractPayments::orderBy('id','asc')->get();
        return $payments;
        foreach ($payments as $key => $value) {
            $stringArr = explode('/',$value['PaymentDate']);
            $data = [
                'UID' => $value['UID'],
                'Type' => 'contract',
                'amount' => $value['Amount'],
                'Notes' => $value['notes'],
                'Date' => $stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1],
                'DateStr' => strtotime( $stringArr[2].'-'.$stringArr[0].'-'.$stringArr[1]),
                'month' =>  $value['month'],
                'year' =>  $value['year'],
                'safe_id' => $value['SafeID'],
                'branch_id' => 1,
                'contract_id' => $value['ContractID'],
                'client_id' => $value['ClientID']
            ];
            $revenue = Revenues::create($data);
        }
    }

    public function refineContracts()
    {
        // $arr = [
        //     'canceledWithOutMoney' => 'cancel',
        //     'finalClientRevision' => 'inProgress',
        //     'uploadingIOS' => 'inProgress'
        // ];
        $contracts = ClientContracts::orderBy('id','asc')->get();
        // return $contracts;
        foreach ($contracts as $key => $value) {
            // if (in_array($value['Status'],['canceledWithOutMoney','finalClientRevision','uploadingIOS'])) {
            //     $update = ClientContracts::find($value['id'])->update(['Status'=>$arr[$value['Status']]]);
            // }
            $update = ClientContracts::find($value['id'])->update(['paid'=>$value->payments()->sum('amount')]);
        }
    }

}
