<?php
/*
 * Copyright (C) 2013 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once 'config.php';
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_GlassService.php';
require_once 'google-api-php-client/src/contrib/Google_PlusService.php';
require_once 'mirror-client.php';
require_once 'plus-client.php';


// Set your cached access token. Remember to replace $_SESSION with a
// real database or memcached.
session_start();

$client = new Google_Client();
$client->setApplicationName('PHP Starter Application');

// These are set in config.php
$client->setClientId($api_client_id);
$client->setClientSecret($api_client_secret);
$client->setDeveloperKey($api_simple_key);

$client->setRedirectUri($service_base_url);

$client->setScopes(array('https://www.googleapis.com/auth/glass.timeline',
  'https://www.googleapis.com/auth/glass.location',
  'https://www.googleapis.com/auth/plus.me'));

// A glass service for interacting with Glass
$glass = new Google_GlassService($client);
// A Google+ service to know who we're interacting with
$plus = new Google_PlusService($client);

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if (!$client->getAccessToken()) {
  // Not logged in? Redirect to OAuth login
  header('Location: ' . $client->createAuthUrl());
} else {
  // User is logged in. Show cool stuff.

  //handle POST data from the form
  if (isset($_POST['operation']) && $_POST['operation'] == "insertTimelineItem") {
    $result = insertTimelineItem($glass, $_POST['text'], null, null, "AUDIO_ONLY");
    echo "<div class=''>Timeline Item inserted!</div>";
    echo '<pre>';
    print_r($result);
    echo '</pre>';
  } else if (isset($_POST['operation']) && $_POST['operation'] == "insertSubscription") {
    #TODO: get real user ID
    subscribeToNotifications($glass, $plus->people->get("me"), $_POST['callback']);
    echo "<div class=''>Subscription inserted</div>";
  } else if (isset($_POST['operation']) && $_POST['operation'] == "insertShareTarget") {
    #FYI: Share target icons will not work unless you deploy to a publicly accessible server
    insertShareTarget($glass, "starter-project", "Starter Project",
        $service_base_url . "/static/icons/run.png");
    echo "<div class=''>Share target inserted. Enable it on the control panel to use it.</div>";
  }
  ?>
<a style="float: right;" href="<?=$service_base_url?>/logout">Sign out</a>
<h1>Do Stuff</h1>
<h2>Timeline Items</h2>
<form method="post">
  <input type="hidden" name="operation" value="insertTimelineItem"/>
  <input type="text" name="text" value="hello world"/><br/>
  <button type="submit">Create a timeline item</button>
</form>
<h2>Subscriptions</h2>
<?php if (strstr($service_base_url, "localhost")) { ?>
  <div class="warning">Warning: URLs must be publicly accessible to enable
    subscriptions. You appear to be running on localhost.
  </div>
  <?php } ?>
<form method="post">
  <input type="hidden" name="operation" value="insertSubscription"/>
  <input style="width: 300px;" type="text" name="callback"
         value="<?= $service_base_url . "/notify.php" ?>"/><br/>
  <button type="submit">Subscribe to timeline updates</button>
</form>
<h2>Share Targets</h2>
<form method="post">
  <input type="hidden" name="operation" value="insertShareTarget"/>
  <button type="submit">Insert a share target</button>
</form>

<h1>See Stuff</h1>
<?php
  $timeline = $glass->timeline->listTimeline();
  echo '<h2>Your Timeline</h2> <pre>' . print_r($timeline, true) . '</pre>';

  $subscriptions = $glass->subscriptions->listSubscriptions();
  echo '<h2>Your Subscriptions</h2> <pre>' . print_r($subscriptions, true) . '</pre>';

  $shareTargets = $glass->shareTargets->listShareTargets();
  echo '<h2>Your Share Targets</h2> <pre>' . print_r($shareTargets, true) . '</pre>';

  // We're not done yet. Remember to update the cached access token.
  // Remember to replace $_SESSION with a real database or memcached.
  $_SESSION['token'] = $client->getAccessToken();
}




