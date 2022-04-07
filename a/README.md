# instagram-feed
Embed Instagram profile feed from your instagram accounts on your website using PHP.\
(Live [Demo](https://instagram-feed.yizack.com/demo/))
[![Live Demo](https://yizack.com/images/instagram-feed/demo.gif)](https://instagram-feed.yizack.com/demo/)

## Requeriments
- PHP Hosting (with `cURL` and `fopen()` support) [[Guide]](#php-hosting)
- Facebook Developer App [[Guide]](#facebook-developer-app)
- Instagram Basic Display API [[Guide]](#instagram-basic-display-api)

## Configuration
- Edit `config.php`
  - Paste your Instagram Basic Display API long-lived access token in `{long-lived-access-token}` for the `$token` variable.
  - Paste your site URL in `{your-site-url}` for the `$site` variable.
- Use the code below, replace `http://your-site.com/feed` with your site URL and paste it on your site where you want your Instagram feed to appear.
  ```html
  <iframe style="border: none;height: 100vh" src="http://your-site.com/feed" width="100%"></iframe>
  ```

## About the code
- `config.php`: Global variables for setup your Instagram Feed.
- `functions.php`: Functions that will be used in `feed.php`.
  - `request($url)`: This function is used for cURL requests and returns the data.
  - `refreshToken()`:  The Instagram long-lived access token token expires in 60 days, but it can be refreshed every 24 hours to restart the expiration time so I wrote this function to refresh the token when 24 hours or more have passed since the last update date located at `update.json` file. If 24 hours have been passed, it overwrites the update date in the .json file.
  - `instagramFeed()`: This function calls the Instagram API with your long-lived access token and returns an array with the data of your last 25 posts. The information returned by this function is given by the `fields` GET parameter in `https://graph.instagram.com/me/media?fields=username,permalink,timestamp,caption&access_token=$token`, this request will return `username`, `permalink`, `timestamp` and `caption` for each Instagram post.
  - `fontawesome()` returns the Font Awesome kit URL given at `config.php`.
- `feed.php`: This is the main script, it calls `refreshToken()` first to verify the update date, then assigns the data returned from `instagramFeed()` to a `$post` variable, below is the HTML document with Bootstrap 4 CSS library.
  - This code section will loop the `post($username, $permalink, $caption, $timestamp)` function for each Instagram post found.
  ```html
  ...
      <div class="container-fluid">
        <div class="row flex-row flex-nowrap">
  <?
    for ($x = 0; $x < count($post); $x++) {
      $username = $post[$x]["username"];
      $permalink = $post[$x]["permalink"];
      if(isset($post[$x]["caption"])) {
        $caption = $post[$x]["caption"];
      }
      $timestamp = $post[$x]["timestamp"];
  ?>
          <div class="instagram_post col-12 col-lg-4" id="<?= $x; ?>">
            <?= post($username, $permalink, $caption, $timestamp); ?>
          </div>
  <?
    }
  ?>
        </div>
      </div>
  ...
  ```
  - Javascript slick-carousel code settings.
  ```javascript
  ...
  <script>
    $('.ig-feed').slick({
      infinite: false,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 3,
      adaptiveHeight: true,
      dots:false,
      arrows: true,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        }
      ]
    });
  </script>
  ...
  ```
  - Inside `post($username, $permalink, $caption, $timestamp)` function there is a HTML `<blockquote>` code that is provided by instargam when you want to embed a post. I add the parameter values of `$username`, `$permalink`, `$caption`, and `$timestamp` for each post.
- `update.json` this file will contains the date when your Instagram long-lived access token was refreshed.

## Requeriments Guide

- ### PHP Hosting
  I think you can use any PHP Hosting unless it doesn't support `cURL` or `fopen()`, I personally use [Namecheap Shared Hosting](https://www.namecheap.com/hosting/shared/).
1. Paste your site URL in `{your-site-url}` for the `$site` variable at `config.php`.

- ### Facebook Developer App
  In order to use the instagram API, we must first create a Facebook App. Follow the steps below to create a Facebook App.
1. Go to [Facebook for Developers site](https://developers.facebook.com/), login and click Create App.
[![Facebook App Step 1](https://yizack.com/images/instagram-feed/facebook-app-1.jpg)](https://developers.facebook.com/)
2. Create your App ID.
[![Facebook App Step 2](https://yizack.com/images/instagram-feed/facebook-app-2.jpg)](https://developers.facebook.com/)
3. In the products tab, add Instagram to use the Instagram API
[![Facebook App Step 3](https://yizack.com/images/instagram-feed/facebook-app-3.jpg)](https://developers.facebook.com/)
4. In the Instagram menu, click **Basic Display**, then click **Settings** to update your App settings.
[![Facebook App Step 4](https://yizack.com/images/instagram-feed/facebook-app-4.jpg)](https://developers.facebook.com/)
5. Fill these required fields.
    - Privacy Policy URL (Must be a valid url, even if not a privacy policy url)
    - App Icon
    - Business Use
    - Category
[![Facebook App Step 5](https://yizack.com/images/instagram-feed/facebook-app-5.jpg)](https://developers.facebook.com/)
6. Scroll down and click Add Platform button.
[![Facebook App Step 6](https://yizack.com/images/instagram-feed/facebook-app-6.jpg)](https://developers.facebook.com/)
7. Enter your site URL and save changes.
[![Facebook App Step 7](https://yizack.com/images/instagram-feed/facebook-app-7.jpg)](https://developers.facebook.com/)

- ### Instagram Basic Display API
  Now it's time to authorize your instagram account.
1. Back to Products > Instagram > Basic Display. Create new App.
[![Instagram App Step 1](https://yizack.com/images/instagram-feed/instagram-app-1.jpg)](https://developers.facebook.com/)
2. Fill OAuth Redirect, Deauthorize Callback and Data Deletion Request URL with your site URL and save changes.
[![Instagram App Step 2](https://yizack.com/images/instagram-feed/instagram-app-2.jpg)](https://developers.facebook.com/)
3. Add Instagram testers.
[![Instagram App Step 3](https://yizack.com/images/instagram-feed/instagram-app-3.jpg)](https://developers.facebook.com/)
4. Enter your Instagram username and select it.
[![Instagram App Step 4](https://yizack.com/images/instagram-feed/instagram-app-4.jpg)](https://developers.facebook.com/)
5. Go to your Instagram account settings page > App and Websites > Tester invites, accept the invite.
[![Instagram App Step 5](https://yizack.com/images/instagram-feed/instagram-app-5.jpg)](https://developers.facebook.com/)
6. Back to Products > Instagram > Basic Display > User Token Generator, you Instagram account should appear, then click Generate Token button for authorize and generate long-lived access token for instagram.
[![Instagram App Step 6](https://yizack.com/images/instagram-feed/instagram-app-6.jpg)](https://developers.facebook.com/)
7. Login and authorize the App.
[![Instagram App Step 7](https://yizack.com/images/instagram-feed/instagram-app-7.jpg)](https://developers.facebook.com/)
8. Copy the generated Token.
[![Instagram App Step 8](https://yizack.com/images/instagram-feed/instagram-app-8.jpg)](https://developers.facebook.com/)
9. Paste your token in `{long-lived-access-token}` for the `$token` variable at `config.php`.