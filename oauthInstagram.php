<?php

class oauthInstagram {

    protected $url_instagram_oauth = 'https://api.instagram.com/oauth';
    private $client_id;
    private $client_secret;

    public function __construct($client_id)
    {
        $this->url_instagram_oauth = "https://api.instagram.com/oauth"; // base url oauth api instagram
        $this->client_id = $client_id; // client id
        $this->client_secret = "5564d0c8c675ab957d70acbc01f5868c";
    }

    /**
     * Função que realiza uma requisição para a api do Insta
     */
    static function request($url,  $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


        if($data !== null){
            $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($curl, CURLOPT_HEADER, false);
        }

        $request = curl_exec($curl);
        curl_close($curl);
        $request = json_decode($request, true);
        return $request;
    }

    function getCode(){
        global $site;
        $url = $this->url_instagram_oauth."/authorize?client_id=".$this->client_id."&scope=user_profile,user_media&response_type=code&redirect_uri=".$site;
        return $url;
    }

    /**
     * Função que faz a troca do code por um access token
     */
    function getAccessToken($code){
        global $site;
        $url = $this->url_instagram_oauth."/access_token";
        $data = "client_id=".$this->client_id."&client_secret=".$this->client_secret."&grant_type=authorization_code&redirect_uri=".$site."&code=".$code;

        return oauthInstagram::request($url, $data);
    }
}

?>
