<?php

namespace App\Imports;


use App\Models\Units;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class UnitsImport implements ToModel ,WithHeadingRow
{
    private $project_id;
    private $block_id;
    private $building_id;

    public function __construct($project_id,$block_id,$building_id) {
        $this->project_id = $project_id;
        $this->block_id = $block_id;
        $this->building_id = $building_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row['code'] != '') {
            return new Units([
                //
                'name' => $row['code'],
                'space' => $row['bulding_space'],
                'landSpace' => $row['land_space'] ?? '-',
                'Price' => $row['unit_price'],
                'ProjectID' => $this->project_id,
                'block_id' => $this->block_id,
                'building_id' => $this->building_id,
                'floor' => $row['floor'] ?? '-',
                'rooms' => $row['rooms'] ?? '-',
                'bathrooms' => $row['bathrooms'] ?? '-',
                'garden' => $row['garden'] ?? '-',
                'roof' => $row['roof'] ?? '-',
                'UID' => auth()->user()->id
            ]);
        }
    }
}
