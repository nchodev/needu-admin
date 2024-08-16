<?php

namespace App\Services;
use Google\Auth\OAuth2;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
    // protected $projectId;
    // protected $client;
    // protected $accessToken;
    protected $accessToken;
    protected $messaging;


    public function __construct()
    {
        // $this->projectId = env('FIREBASE_PROJECT_ID');
        $keyFilePath = file_get_contents(storage_path("firebase-auth.json"));
        $factory =(new Factory)->withServiceAccount($keyFilePath);

        $this->messaging = $factory->createMessaging();
    }

    // protected function authenticate()
    // {

    //     $creds = json_decode(file_get_contents($this->keyFilePath), true);
    //     return $creds;
    //     $auth = new OAuth2([
    //         'audience' => 'https://oauth2.googleapis.com/token',
    //         'issuer' => $creds['client_email'],
    //         'sub' => $creds['client_email'],
    //         'signingAlgorithm' => 'RS256',
    //         'signingKey' => $creds['private_key'],
    //         'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
    //     ]);

    //     $auth->setGrantType(OAuth2::JWT_URN);
    //     $auth->fetchAuthToken();
    //     $this->accessToken = $auth->getAccessToken();
    // }

    public function sendNotification($deviceToken, $title, $body, $data = [])
    {

        $message = CloudMessage::withTarget('token', $deviceToken)
        ->withNotification(['title'=>$title, 'body'=>$body])
        ->withData($data);
        $this->messaging->send($message);
        // $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        // $headers = [
        //     'Authorization' => 'Bearer ' . $this->accessToken,
        //     'Content-Type' => 'application/json; UTF-8',
        // ];
        // $payload = [
        //     'message' => [
        //         'token' => $deviceToken,
        //         'notification' => [
        //             'title' => $title,
        //             'body' => $body,
        //         ],
        //         'data' => $data,
        //     ],
        // ];

        // try {
        //     $response = $this->client->post($url, [
        //         'headers' => $headers,
        //         'json' => $payload,
        //     ]);
        //     return json_decode($response->getBody(), true);
        // } catch (RequestException $e) {
        //     return ['error' => $e->getMessage()];
        // }
    }
}
