<?php

class OauthInstagram {

    protected const URL_INSTAGRAM_OAUTH = 'https://api.instagram.com/oauth/'; // base url oauth api instagram
    protected const URL_TOKEN_OAUTH_LONG_LIFE =  'https://graph.instagram.com/';
    private $client_id;
    private $client_secret;
	private $redirect_uri;

    public function __construct($client_id, $client_secret, $redirect_uri) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
		$this->redirect_uri = $redirect_uri;
    }

    /**
     * Função que realiza uma requisição para a api do Instagram
     */
    static function request($url, $data = null){
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

    function getCode() {
        $url = $this->construirGetCodeUrl();

        return $url;
    }

    /**
     * Função que faz a troca do code por um access token
     */
    function getAccessToken($code) {
        $url = self::URL_INSTAGRAM_OAUTH . "access_token";
        $data = "client_id={$this->client_id}&client_secret={$this->client_secret}&grant_type=authorization_code&redirect_uri={$this->redirect_uri}&code={$code}";

        return oauthInstagram::request($url, $data);
    }

    function getLongLiveAccessToken($access_token) {
        $url = self::URL_TOKEN_OAUTH_LONG_LIFE . "access_token";
        $params = "?grant_type=ig_exchange_token&client_secret={$this->client_secret}&access_token={$access_token}";

        return oauthInstagram::request($url.$params);
    }

    /**
     * Função para atualzar o token de acesso de longa duração de um usuário.
	 * 
	 * @return string Token atualizado
     */    
    function refreshToken(){
        $url = self::URL_TOKEN_OAUTH_LONG_LIFE . "refresh_access_token?grant_type=ig_refresh_token&access_token={$access_token}";
		
		$resposta = oauthInstagram::request($url);
		if(!isset($resposta) || !isset($resposta['access_token'])) {
			die('Não foi possível realizar a atualização do token. Error: ' . var_dump($resposta));
		}
		return $resposta['access_token'];
    }

	public function construirGetCodeUrl() {
		return self::URL_INSTAGRAM_OAUTH . 
			"authorize?client_id=" . $this->client_id . 
			"&scope=user_profile,user_media&response_type=code&redirect_uri=" . $this->redirect_uri;
	}
}

?>
