<?php


class connectToApiClass{


    private $myPostHeaders =[
        "User-Agent"=>"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0",
        "accept"=>"application/json",
        "Content-Type"=>"application/json"
    ];

    private $email;
    private $password;
    private $apiBaseUrl;

    // Placing Base Url, email and password in constructor, most likely these values wont change once object has been created.
    public function __construct($apiBaseUrl, $email, $password)
    {
        $this->apiBaseUrl = $apiBaseUrl;
        $this->email = $email;
        $this->password = $password;
    }


    // Function to obtain API token
    function getSymfonyToken(string $endPoint)
    {


        $postData = [
            "email"=> $this->email,
            "password"=> $this->password
        ];

        // Converting array to json
        $postDataJson = json_encode($postData);

        // Forming full url where post request gonna be sent
        $fullPath = $this->apiBaseUrl . $endPoint;

        $ch = curl_init();

 
        curl_setopt($ch, CURLOPT_URL, $fullPath);               // Target Url
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            // Return data instead of dumping it
        curl_setopt($ch, CURLOPT_POST, 1);                      // 1 means POST 0 means GET
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);            // Follow redirects
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);    // Json format
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        // Http supported aswell


        $headers = [];

        // Setting all headers
        if(!empty($this->myPostHeaders)){
            foreach($this->myPostHeaders as $key => $value){
                $headers[] = $key . ": " . $value;
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $res = json_decode($result);

        return $res;
    }

}
