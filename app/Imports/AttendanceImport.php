<?php

namespace App\Imports;


use Auth;
use App\Models\User;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel ,WithHeadingRow
{
    private $branch;

    public function __construct(int $branch_id) {
        $this->branch = 1;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row != '') {
            $timestamp = strtotime($row['time']);
            if ($timestamp === FALSE) {
              $timestamp = str_replace('/', '-', $row['time']);
            }
            if (is_numeric($timestamp)) {
                $UNIX_DATE = ($timestamp - 25569) * 86400;
                // $day = gmdate("l d F Y", $UNIX_DATE);
                $day = gmdate("Y-m-d", $UNIX_DATE);
                $time = gmdate("H:i:s", $UNIX_DATE);
                $month = gmdate("m", $UNIX_DATE);
                $year = gmdate("Y", $UNIX_DATE);
            } else {
                // $day = date("l d F Y", strtotime($timestamp));
                $day = date("Y-m-d", strtotime($timestamp));
                $time = date("H:i:s", strtotime($timestamp));
                $month = date("m", strtotime($timestamp));
                $year = date("Y", strtotime($timestamp));
            }
    
            
            //duplicated attendance
            if ($row['no'] != '') {
                $thisUser = User::where('branch_id',$this->branch)->where('zk_id',$row['no'])->first();
                $DA = Attendance::where('EmployeeID',$thisUser['id'])->where('Date',$day)->first();
                if ($thisUser != '') {
                    if ($DA == '') {
                        return new Attendance([
                            //
                            'UID' => Auth::user()->id,
                            'EmployeeID' => $thisUser['id'],
                            'EmployeeType' => 'Managment',
                            'branch_id' => $this->branch,
                            'zk_id' => $row['no'],
                            'Date' => $day,
                            'month' => $month,
                            'year' => $year,
                            'CheckIn' => $time,
                            'late' => $thisUser->lateCalculator($time),
                            'CheckOut' => NULL,
                            'OverTime' => NULL
                        ]);
                    } else {
                        $DA->update([
                            'CheckOut' => $time,
                            'OverTime' => $thisUser->overtimeCalculator($time)
                        ]);
                        return $DA;
                    }
                }
            }
        }
    }
    public function headingRow(): int
    {
        return 1;
    }
}
