<?php
function notificationTextTranslate($parameters = [],$lang)
{
    $result = '';
    if (count($parameters) > 0) {
        if ($parameters['type'] == 'newMessage') {
            $result = [
                'ar' => 'لقد قام '.$parameters['name'].' بإرسال رسالة جديدة.',
                'en' => '‘User with the name '.$parameters['name'].'Send a new message for you'
            ];
        }
        if ($parameters['type'] == 'newAppointment') {
            $result = [
                'ar' => 'لقد قام '.$parameters['name'].' بحجز موعد جديد.',
                'en' => 'user '.$parameters['name'].'  he booked a new appointment'
            ];
        }

        if ($parameters['type'] == 'cancelAppointment') {
            $result = [
                'ar' => 'لقد قام '.$parameters['name'].' إلغاء موعد جديد.',
                'en' => 'user '.$parameters['name'].'  he canceled a new appointment'
            ];
        }

        if ($parameters['type'] == 'newAffiliator') {
            $result = [
                'ar' => 'لقد قام '.$parameters['name'].' بطلب ليكون مسوق بالعمولة .',
                'en' =>  $parameters['name'].'  sent application to be an Affiliate Marketer'
            ];
        }

        if ($parameters['type'] == 'withdraw') {
            $result = [
                'ar' => 'لقد قام '.$parameters['name'].' بطلب ليكون لسحب الأرباح .',
                'en' =>  $parameters['name'].'  sent request to withdraw profits'
            ];
        }

        if ($parameters['type'] == 'clientDuration') {
            $result = [
                'ar' =>  'لقد تخطى'.$parameters['name'].'الدة المسموحة في هذا القسم' . $parameters['position'].'طريقة التواصل'.$parameters['cellphone'],
                'en' =>  $parameters['name'].'exceeded the period allowed in this' . $parameters['position'].'contact way'.$parameters['cellphone'],
            ];
        }
    }
    return $result[$lang];
}

function notifyManagers($notificationType,$notificationData)
{
    if (in_array($notificationType, ['newMessage','newAppointment','cancelAppointment','newAffiliator','withdraw'])) {
        $PrimeManagers =  App\Models\User::where('role','1')->where('status','Active')->get();
        foreach ($PrimeManagers as $key => $value) {
            $value->notify(new \App\Notifications\AdminNotifications($notificationData));
        }
    }
}

function notifyEmployee($notificationType,$notificationData,$role)
{
    if (in_array($notificationType, ['newMessage','newAppointment','cancelAppointment','newAffiliator','withdraw'])) {
        $PrimeEmployee =  App\Models\User::where('role',$role)->where('status','Active')->get();
        foreach ($PrimeEmployee as $key => $value) {
            $value->notify(new \App\Notifications\AdminNotifications($notificationData));
        }
    }
}

function notificationImageLink($type,$linked_id = '')
{
    $link = getSettingImageLink('logo');
    if ($type == 'newMessage') {
        $thePublisher =  App\Models\User::find($linked_id);
        if ($thePublisher != '') {
            $link = $thePublisher->photoLink();
        }
    }
    if ($type == 'newAppointment') {
        $thePublisher =  App\Models\User::find($linked_id);
        if ($thePublisher != '') {
            $link = $thePublisher->photoLink();
        }
    }
    if ($type == 'cancelAppointment') {
        $thePublisher =  App\Models\User::find($linked_id);
        if ($thePublisher != '') {
            $link = $thePublisher->photoLink();
        }
    }
    if ($type == 'newAffiliator') {
        $thePublisher =  App\Models\User::find($linked_id);
        if ($thePublisher != '') {
            $link = $thePublisher->photoLink();
        }
    }
    return $link;
}

function notifyPublisher($notificationData,$publisherId)
{
    $publisher =  App\Models\User::find($publisherId);
    if ($publisher != '') {
        $publisher->notify(new \App\Notifications\PublisherNotifications($notificationData));
    }
}

function pushNotify($name,$cellphone,$position,$userId,$type)
{
    $notificationText = notificationTextTranslate([
        'name' => $name,
        'cellphone' => $cellphone,
        'position' => $position,
        'type' => $type
    ], 'ar');
    $notificationData = [
        'type' => $type,
        'linked_id' => $userId,
        'text' => $notificationText,
        'date' => date('Y-m-d'),
        'time' => date('H:i')
    ];
    notifyManagers($type, $notificationData);
}



function clientDuration($name,$position,$cellphone,$lang)
{
    $result = [
        'ar' => "لقد تخطى $name المدة المسموحة في هذا القسم $position طريقه التواصل $cellphone",
        'en' =>  "$name exceeded the period allowed in this $position contact way $cellphone",
    ];
    return $result[$lang];
}
