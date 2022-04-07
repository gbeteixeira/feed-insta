<?php

class feedInstagram {

    protected $url_instagram;
    private $token;

    public function __construct($token)
    {
        $this->url_instagram = "https://graph.instagram.com/"; // base url api instagram
        $this->token = $token; // // Long live access token user
    }

    /**
     * Função que realiza uma requisição para a api do Insta
     */
    static function request($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $request = curl_exec($curl);
        curl_close($curl);
        $request = json_decode($request, true);
        return $request;
    }

    /**
     * Função que pega o refresh token
    */

    
    function refreshToken(){
        global $site;

        $url = $this->url_instagram."/refresh_access_token?grant_type=ig_refresh_token&access_token=".$this->token;

        // a $access_token recebe o novo valor do token de acesso, que costuma durar 60 dias
        $date_now = date("Y-m-d H:i:s");
        $last_request = '';
        
        if(strtotime($date_now) - strtotime($last_request) > 86400){
            $access_token = feedInstagram::request($url)["access_token"];
        }
    }

    function instagramFeed(){
        $url = $this->url_instagram."/me/media?fields=username,permalink,timestamp,caption,media_type,media_url,thumbnail_url&access_token=".$this->token;
        return feedInstagram::request($url)["data"];
    }

    function getThumbnail($media_type, $media_url, $thumbnail_url = ''){
        $thumbnail = ($media_type != 'VIDEO') ? $media_url : $thumbnail_url;
        //codifica a img para base64
        $imageData = base64_encode(file_get_contents($thumbnail));
        //retorna a img em base64
        $src = 'data: image/png;base64,'.$imageData;

        return $src;
    }

}

/**
 * feed_instagram -> recebe array com todas as publicações
 * @param username recebe o nome do usuário no instagram
 * @param permalink recebe o link da publicação
 * @param timestamp recebe a data de postagem da publicação
 * @param caption recebe a descrição do post
 * @param id recebe o id do post
 */

?>
