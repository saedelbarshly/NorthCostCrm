<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Clients;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ClientsImport implements ToModel ,WithHeadingRow
{

    public function __construct() {
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row['name'] != '' && $row['mobile'] != '') {
            $user_id = '';
            if (isset($row['employee'])) {
               	$user = User::where('name', $row['employee'])->first();
                if ($user != '') {
                    $user_id = $user->id;
                }
            }

            $old_client = Clients::where('cellphone',$row['mobile'])->first();
            // where('identity',$row['identity'])->
            if ($old_client == '') {
                return new Clients([
                    'Name' => $row['name'],
                    'cellphone' => $row['mobile'],
                    'whatsapp' => $row['whatsapp'],
                    'email' => $row['email'],
                    'supportHousing' => $row['supporthousing'],
                    'identity' => $row['identity'],
                    'salary' => $row['salary'],
                    'bank' => $row['bank'],
                    'Job' => $row['job'],
                    'militaryTitle' => $row['militarytitle'],
                    'Notes' => $row['notes'],
                    'referral' => $row['referral'],
                    'status' => $row['status'],
                    'position' => $user_id == '' ? 'call_center' : 'sales',
                    'UID' => $user_id
                ]);
            }
        }
    }
}
