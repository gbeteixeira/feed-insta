<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $site = "https://sistemavendas.epizy.com/feed/"; // recebe a uri de retorno 

    require_once('feedInstagram.php');
    require_once('oauthInstagram.php');

    $oauthInstagram = new oauthInstagram('787297432245507');

    if(isset($_GET['code'])){
        $request_token = $oauthInstagram->getAccessToken($_GET['code']);

       if(isset($request_token['access_token'])){
            $instagramFeed = new feedInstagram($request_token['access_token']);

            $feed_instagram = $instagramFeed->instagramFeed();

            foreach ($feed_instagram as $feed_item){
                $url_thumbnail = $instagramFeed->getThumbnail($feed_item['media_type'], $feed_item['media_url'],  isset($feed_item['thumbnail_url']) ? $feed_item['thumbnail_url'] : null);

                echo '<a href="'.$feed_item['permalink'].'">
                        <img src="'.$url_thumbnail.'" alt="Image"/>
                    </a>';
            }
       } else {
           echo "Deu algum erro ai meu jovem. Erro: " .$request_token['error_type']." - " .$request_token['error_message'];
        echo '<a href="'.$oauthInstagram->getCode().'">
                Login insta
             </a>';
       }

    } else {

        echo '<a href="'.$oauthInstagram->getCode().'">
                    Login insta
                </a>';
    }
?>