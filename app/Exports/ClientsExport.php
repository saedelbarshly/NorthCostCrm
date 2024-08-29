<?php
namespace App\Exports;


use App\Models\Clients;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClientsExport implements FromView
{
    private $position;
    private $cellphone;
    private $status;
    private $Name;
    private $identity;
    private $source_id;
    public function __construct($position, $cellphone, $status, $Name, $identity, $source_id) {
        $this->position = $position;
        $this->cellphone = $cellphone;
        $this->status = $status;
        $this->Name = $Name;
        $this->identity = $identity;
        $this->source_id = $source_id;
    }

    public function view(): View
    {
        $clients = Clients::orderBy('updated_at','desc');
        if ($this->position != '') {
            if (is_array($this->position)) {
                $clients = $clients->whereIn('position',$this->position);
            }
            else {
                $clients = $clients->where('position',$this->position);
            }
        }
        if ($this->cellphone != '') {
            $clients = $clients->where('cellphone',$this->cellphone);
        }
        if ($this->status != '') {
            $clients = $clients->where('status',$this->status);
        }
        if ($this->Name != '') {
            $clients = $clients->where('Name',$this->Name);
        }
        $clients = $clients->get();
        return view('AdminPanel.clients.exportExcel', [
            'clients' => $clients
            // 'clients' => [
            //     [
            //         'Name' => $this->cellphone
            //     ]
            // ]
        ]);
    }
}
