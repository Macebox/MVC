<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="sv"> <!--<![endif]-->
<head>

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/i/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?php echo $title?></title>

  <!-- Mobile viewport optimized: h5bp.com/viewport -->
  <meta name="viewport" content="width=device-width">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <link rel="stylesheet" href="<?=theme_url('/boilerplate.css')?>">
  <link rel="stylesheet" href="<?=$stylesheet?>">

  <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->
  <style>
    <?php echo  @$style?>
  </style>
</head>
<body>
  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
  <header id="above">
	<?=login_menu()?>
  </header>

  <header id="header">
    <div id="banner">
      <a href="<?=CNocturnal::Instance()->request->CreateUrl('index')?>">
        <img class="site-logo" src="<?=theme_url($logo)?>" alt="logo" width="<?=$logo_width?>" height="<?=$logo_height?>" />
      </a>
      <p class="site-title"><?=$header?></p>
      <p class="site-slogan"><?=$slogan?></p>
    </div>
    <?=getHTMLForNavigation("navbar")?>
  </header>
  
  <div id="main" role="main">
	<?=get_messages_from_session()?>
    <?=@$main?>
	<?=render_views('primary')?>
	<?=render_views('sidebar')?>
  </div>

  <footer id="footer">
  <?=$footer?>
<pre>
 .
..:      /mace
</pre>
	<?=get_debug()?>
  </footer>
</body>
</html> 