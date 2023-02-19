<?php

namespace App\repository;

use App\Models\subuser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

//define("GOOGLE_API_KEY", "testAIzaSyCy109sfCtns_pdPdpFb6fn4LcA4UV9K10");
class userSubRep
{
    //'user_id', 'name', 'token','expire'
    /**
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $user  = $request->user();
        $name  = $request->input('name');
        $token = $user->createToken($name, ['data:send'])->plainTextToken;
        $i=0;
        $fcm="";
        while ($i==0){
            $fcm = Str::random(6);
            if(! subuser::where('fcm',$fcm)->exists())
                $i=1;
        }
        $newUser =subuser::create([
            'user_id'=>$user->id,
            'name' => $name,
            'token' => $token,
            'expire' =>"-1",
            'fcm'   => $fcm,
        ]);
        //$newUser['token2'] = $token;
        return $newUser;
    }

    public function login(Request $request)
    {
        $user = User::where('name', \request('name'))->first();
        if (! $user || ! Hash::check(\request('password'), $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }
        $name  = $request->input('subuser');
        return subuser::where('user_id',$user->id)->whereName($name)->value('token');
    }


    public function login2(REquest $request)
    {
        return subuser::where('fcm',$request->input('fcm'))->value('token');
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function readToken(Request $request)
    {
        $user  = $request->user();
        $name  = $request->input('name');
        return subuser::where('user_id',$user->id)->whereName($name)->value('token');
    }

    public function getSubUsers(Request $request)
    {
        $user  = $request->user();
        return subuser::whereUser_id($user->id)->get();
    }

    public function sendNotification(Request $request){
        $fcmToken = $this->getFsmToken($request);
        /***$notification["title"]          = $request->input('title');;
        $notification["body"]           = $request->input('body');;
        $notification["mutable_content"]= true;
        $notification["sound"]          = "Tri-tone" ;*/

        /****$notification["command"]          = $request->input('command');;
        $notification["type"]           = $request->input('type');;
        $fields = [
            'to' => $fcmToken,// '/topics/global',//
            //'notification' => $notification ,
            'data' => $notification,
        ];
        $data = [
            "registration_ids" => [$fcmToken],
            "notification" => [
                "title" => '$request->title',
                "body" => '$request->body',
            ]
        ];
        return $this->sendPushNotification($fields );****/


        // Payload data you want to send to devices
        $data = array('message' => 'new commaand!');
        $data["command"]          = $request->input('command');;
        $data["type"]           = $request->input('type');;

        // The recipient device tokens
        $to = array($fcmToken);//'a8b6aeebc9e09617317444'

        // Optionally, send to a publish/subscribe topic instead
        // $to = '/topics/news';

        // Optional push notification options (such as iOS notification fields)
        $options = array(
            'notification' => array(
                'badge' => 1,
                'sound' => 'ping.aiff',
                'title' => 'Test admin',
                'body'  => "its a new commanaad \xE2\x9C\x8C"
            )
        );

        // Send it with Pushy
        return $this->sendPushNotification($data, $to, $options);
    }

    public function getFsmToken(Request $request)
    {
        return subuser::where('user_id',$request->user()->id)->where('name',$request->input('name'))->value('fcm');
    }

    public function setFcmToken(Request $request)
    {
        subuser::where('user_id',$request->user()->currentAccessToken()['tokenable_id'])
            ->whereName($request->user()->currentAccessToken()['name'])
            ->update(['fcm'=>$request->input('fcm')]);
        return $request->user()->currentAccessToken();
    }

    private function sendPushNotification2(array $data) {

        // include config
        //$google_api_key = 	   "AAAAcK2ju3s:APA91bHdhNXQBKx4-Te_Uz6WPe9gBDOf220atGNd1lyJlGTWYI7RFQNXip3PbmB4X6Eh7f6p1xVKe7mzH2s7FNXha9jPMdJk93KxFesSh0dL5ci6NA5gEk6n5qJNTfHK4rK0fejDlBFV";
        $google_api_key = 'AAAAcK2ju3s:APA91bFBkjiL8t7gBQGsqd-e-6cOO84fy3jI2SFlNGzf-4zSvHYiF0pKifHUh60Z3ItU1b08W8iM6TodDFudHpx5H1vnJQy2d958ZDERUhuWc9FsJzb6vB9HR83TC5_wlhb0PrynmYMl';
        //$google_api_key = "AIzaSyCy109sfCtns_pdPdpFb6fn4LcA4UV9K10";
        //"AIzaSyCy109sfCtns_pdPdpFb6fn4LcA4UV9K10";//

        // Set POST variables
        $url ='https://fcm.googleapis.com/fcm/send';//'https://fcm.googleapis.com/v1/projects/myspy-7799c/messages:send';//


        $SERVER_API_KEY = $google_api_key;


        $dataString = json_encode($data);

        $headers = [
            'Authorization:key ='. $SERVER_API_KEY,
            'Content-Type: application/json',
		//'project_id: 483949525883',
		//'sender_id: 483949525883',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);



        //$proxyauth = 'user:password';
        $proxy = 'fodev.org:8118';
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        return [curl_exec($ch),$google_api_key,$dataString];
        /**$google_api_key = "Authorization:key =AAAAcK2ju3s:APA91bFBkjiL8t7gBQGsqd-e-6cOO84fy3jI2SFlNGzf-4zSvHYiF0pKifHUh60Z3ItU1b08W8iM6TodDFudHpx5H1vnJQy2d958ZDERUhuWc9FsJzb6vB9HR83TC5_wlhb0PrynmYMl";
            return Http::acceptJson()->withToken($google_api_key)->post(
                'https://fcm.googleapis.com/fcm/send',
                ($dataString),
            );*/
    }

     public function sendPushNotification( $data, $to, $options) {
        // Insert your Secret API Key here
        $apiKey = 'c08e6cfac7bbba59d4deef887e3a881e5cd0ae170119e1c418f98d2963a68b1b';

        // Default post data to provided options or empty array
        $post = $options ?: array();

        // Set notification payload and recipients
        $post['to'] = $to;
        $post['data'] = $data;

        // Set Content-Type header since we're sending JSON
        $headers = array(
            'Content-Type: application/json'
        );

        // Initialize curl handle
        $ch = curl_init();

        // Set URL to Pushy endpoint
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushy.me/push?api_key=' . $apiKey);

        // Set request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set our custom headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Get the response back as string instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set post data as JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));

        // Actually send the push
        $result = curl_exec($ch);

        // Display errors
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }

        // Close curl handle
        curl_close($ch);

        // Attempt to parse JSON response
        $response = @json_decode($result);

        // Throw if JSON error returned
        if (isset($response) && isset($response->error)) {
            throw new Exception('Pushy API returned an error: ' . $response->error);
        }
    }



}
