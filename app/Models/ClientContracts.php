<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientContracts extends Model
{
    //
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class,'UID');
    }

    public function agent()
    {
        return $this->belongsTo(User::class,'AgentID');
    }

    public function client()
    {
        return $this->belongsTo(Clients::class,'ClientID');
    }

    public function payments()
    {
        return $this->hasMany(Revenues::class,'contract_id');
    }
    public function expenses()
    {
        return $this->hasMany(Expenses::class,'contract_id');
    }

    public function servicesList()
    {
        return $this->hasMany(ContractServices::class,'contract_id');
    }

    public function totals()
    {
        $list = [
            'paid' => $this->payments()->sum('amount'),
            'expenses' => $this->expenses()->sum('Expense'),
            'net' => $this->Total - $this->expenses()->sum('Expense')
        ];
        $list['rest'] = $this->Total - $list['paid'];

        return $list;
    }


    public function FilesArray()
    {
        $arr = [];
        if ($this->Files != '') {
            $arr = unserialize(base64_decode($this->Files));
        }
        return $arr;
    }

    public function getAttachmentLink($image)
    {
        $link = '';
        if (in_array($image,$this->FilesArray())) {
            $link = asset('uploads/contracts/'.$this->id.'/'.$image);
        }
        return $link;
    }

    public function FilesHtml()
    {
        $arr = $this->FilesArray();
        $html = '';
        if (is_array($arr)) {
            if (count($arr) > 0) {
                foreach ($arr as $key => $value) {
                    $html .= '<div class="col-3" id="row_photo_'.$key.'">';
                    $ext = pathinfo($value, PATHINFO_EXTENSION);

                    if ($ext == 'pdf') {
                        $html .= '<a class="btn btn-sm btn-promary" target="_blank" href="'.$this->getAttachmentLink($value).'">تحميل المرفق</a>';
                    } else {
                        $html .= '<img class="round" src="'.$this->getAttachmentLink($value).'" alt="avatar" width="100%">';
                    }
                    $delete = route('admin.contracts.deleteAttachment',['id'=>$this->id,'photo'=>$value,'X'=>$key]);
                    $html .= '<br><button type="button" class="btn btn-block btn-danger" onclick="';
                    $html .= "confirmDelete('".$delete."','photo_".$key."')";
                    $html .= '">';
                    $html .= '<i data-feather="trash-2"></i>';
                    $html .= '</button>';
                    $html .= '</div>';
                }
            }
        }
        return $html;
    }
}
