<?php include_once dirname( __FILE__ ) . "/../../../wp-load.php"; ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Yakadanda Google+ Hangout Events instructions</title>
    <meta name="description" content="Yakadanda Google+ Hangout Events instructions">
    <meta name="viewport" content="width=device-width">
    
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    
  </head>
  <body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <!-- Add your site or application content here -->
    <h1>How to get your Google Api Key, Client ID, and Client Secret</h1>
    <ol>
      <li>Go to <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a></li>
      <li>In selectbox click create to create project<br/><img src="images/manual-1.png"/></li>
      <li>Enter the name for your project, e.g. <em>Yakadanda Google+ Hangout Events</em></li>
      <li>On Services menu of your project, turn on calendar api<br/><img src="images/manual-2.png"/><br/><br/><img src="images/manual-3.png"/></li>
      <li>On API Access menu of your project, create an OAuth 2.0 client ID<br/><img src="images/manual-4.png"/></li>
      <li>Fill Branding Information form and then click next.</li>
      <li>On Client ID Settings form.
        <dl>
          <dt><strong>Application type</strong></dt>
          <dd><em>Web application</em></dd>
          <dt><strong>Your site or hostname</strong></dt>
          <dd>Change the selectbox to "<em>http://</em>"<br/>
            Copy and paste this url <em><?php echo GPLUS_HANGOUT_EVENT_URL . '/oauth2callback.php'; ?></em> to the textbox.</dd>
          <dt><strong>Redirect URI</strong></dt>
          <dd>It will automatic filled with "<em><?php echo GPLUS_HANGOUT_EVENT_URL . '/oauth2callback.php'; ?></em>" after paste and click outside the textbox.</dd>
        </dl>
        Finally click Create client ID button.
      </li>
      <li>Now you have Api Key, Client ID, and Client Secret.</li>
    </ol>
    
  </body>
</html>
