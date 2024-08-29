<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactMessages;
use App\Models\Clients;
use Response;

class ContactMessagesController extends Controller
{
    public function sendMessage(Request $request)
    {

        $data = $request->except(['_token']);

     	foreach($data as $dd){
          $client = new Clients();
          $client->Name =  $dd['Name'];
          $client->phone =  $dd['phone'];
          $client->age =  $dd['age'];
          $client->email =  $dd['email'];
          $client->Notes =  $dd['content'];
          $client->question =  $dd['question'];
          $client->referral =  $dd['referral'];
          $client->save();
        }

        if ($client) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }
    public function registerClient(Request $request)
    {


        $rules = [
                    'Name' => 'required',
                    'identity' => 'nullable|unique:clients,identity|digits:10',
                    'email' => 'nullable|email|unique:clients,email',
                    'phone' => 'nullable|numeric|unique:clients,phone|digits:10',
                    'cellphone' => 'required|required_without:email|numeric|unique:clients,cellphone|digits_between:10,13|digits:10',
                    'whatsapp' => 'nullable|numeric|unique:clients,whatsapp'
                ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            foreach ((array)$validator->errors() as $error) {
                return response()->json([
                    'status' => 'faild',
                    'message' => trans('api.pleaseRecheckYourDetails'),
                    'data' => $error
                ]);
            }
        }

        $data = $request->except(['_token']);
        $client = Clients::create([
            'Name' => $request['Name'],
            'cellphone' => $request['cellphone'],
            'age' => $request['age'],
            'email' => $request['email'],
            'Notes' => $request['content'],
            'referral' => $request['referral'],
            'position' => 'call_center',
            'question' => 1
        ]);

        if ($client) {
            $resArr = [
                'status' => 'success',
                'message' => trans('api.yourDataHasBeenSavedSuccessfully')
            ];
        } else {
            $resArr = [
                'status' => 'faild',
                'message' => trans('api.someThingWentWrong')
            ];
        }
        return response()->json($resArr);



    }
}
