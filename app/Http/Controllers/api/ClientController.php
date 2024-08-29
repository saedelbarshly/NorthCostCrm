<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clients;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $client = Clients::create($data);
        if ($client) {
            $resArr = [
                'status' => true,
                'message' => 'تم الأضاضة بنجاح',
            ];
        } else {
            $resArr = [
                'status' => false,
                'message' => 'حدث شي خطأ حاول مره أخرى !',
            ];
        }
        return response()->json($resArr);

    }
}
