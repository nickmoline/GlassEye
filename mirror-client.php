<?php
/**
 * Google Glass API Functions
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @package glasseye
 * @subpackage googleglass
 */

require_once 'sample/google-api-php-client/src/Google_Client.php';
require_once 'sample/google-api-php-client/src/contrib/Google_GlassService.php';
require_once 'sample/google-api-php-client/src/contrib/Google_PlusService.php';


/**
 * Returns Google API Client object
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @param string $access_token Access token to check
 * @param bool $new Create a new client
 * @return obj client object
 */
function get_gclient($access_token = null, $new = false) {
	global $client;

	if (!$client || $new) {
		$client = new Google_Client();
		$client->setApplicationName(APPLICATION_NAME);
		$client->setClientId(API_CLIENT_ID);
		$client->setClientSecret(API_CLIENT_SECRET);
		$client->setDeveloperKey(API_SIMPLE_KEY);

		$client->setRedirectUri(SERVICE_BASE_URL);
		$client->setScopes(
			array(
				'https://www.googleapis.com/auth/glass.timeline',
				'https://www.googleapis.com/auth/glass.location',
				'https://www.googleapis.com/auth/plus.me'
			)
		);
	}

	if ($access_token) {
		$client->setAccessToken($access_token);
	}

	return $client;
}

/**
 * Returns Google Glass object
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @return obj glass object
 */
function get_glass($access_token = null) {
	global $glass;

	if (!$glass) {
		$glass = new Google_GlassService(get_gclient($access_token));
	}

	return $glass;
}

/**
 * Returns Google Plus object
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @return obj plus object
 */
function get_plus($access_token = null) {
	global $plus;

	if (!$plus) {
		$plus = new Google_PlusService(get_gclient($access_token));
	}
}


/**
 * Raw Insert into Timeline Function
 *
 * @author nickmoline
 * @since 0.1
 * @version 0.1
 * @param string $text HTML/Text Message
 * @param array $menu_items Array of Menu Items
 * @param string $access_token Access Token for sending
 * @param string $bundle_id ID To Thread message with
 * @param bool $is_html [Default true] Content is html
 * @param bool $read_aloud [Default true] Allow Read Aloud option
 * @param bool $audio_notification [Default false] Send an audible ding
 * @param string $spoken_text [optional] Optional different version for spoken text
 * @param string $contentType [optional] MIME Type of optional file attachment
 * @param string $attachment [optional] File Attachment Contents for file attachment.
 * @return obj TimeLine Item
 */
function insertTimelineItem($text, $menu_items = array(), $access_token = null, $bundle_id = null, $is_html = true, $read_aloud = true, $audio_notification = false, $spoken_text = null, $contentType = null, $attachment = null) {
	$timelineItem = new Google_TimelineItem();

	if ($is_html) {
		$timelineItem->setHtml($text);
		if (!$spoken_text) $spoken_text = strip_tags($text);
	} else {
		$timelineItem->setText($text);
		if (!$spoken_text) $spoken_text = strip_tags($text);
	}
	$menuItem = new Google_MenuItem();
	$menuItem->setAction("REPLY");
	array_push($menu_items, $menuItem);
	$creator = new Google_Entity();
	$creator->setId('glass-eye');
	$timelineItem->setCreator($creator);

	if ($read_aloud) {
		$menuItem = new Google_MenuItem();
		$menuItem->setAction("READ_ALOUD");
		array_push($menu_items, $menuItem);
		$timelineItem->setSpeakableText($spoken_text);
	}

	if ($bundle_id) $timelineItem->setBundleId($bundle_id);

	$timelineItem->setMenuItems($menu_items);

	// if ($audio) {
	// 	$notification = new Google_NotificationConfig();
	// 	$notification->setLevel($notificationLevel);
	// 	$timelineItem->setNotification($notification);
	// }
	$optParams = array();
	if ($contentType != null && $attachment != null) {
		$optParams['data'] = $attachment;
		$optParams['mimeType'] = $contentType;
	}
	$glass = get_glass($access_token);
	return $glass->timeline->insert($timelineItem, $optParams);
}

/**
 * 
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @see insertTimelineItem()
 * @param string $clue Clue to Send
 * @param array $room_info Information for the Game Room (including info about the creator)
 * @return obj Timeline Item object
 */
function send_clue_out($clue, $room_info) {
	$logo_url = SERVICE_BASE_URL."images/logoVertical.png";
	$html = <<<EndOfCreatorClue
<article>
	<figure>
		<img src="{$logo_url}" />
	</figure>
	<section>
		<table class="text-small" align-justify">
			<tbody>
				<tr>
					<td>{$room_info['room_name']} spied with his glass eye, something...</td>
				</tr>
				<tr>
					<td>{$clue}</td>
				</tr>
			</tbody>
		</table>
	</section>
</article>
EndOfCreatorClue;

	$spoken_text = "{$room_info['room_name']} spied with his glass eye, something {$clue}";

	$room_id = $room_info['room_id'];
	$user_id = null;
	$thread_id = '';

	$recipients = get_room_recipients($room_id, true);
	global $db;
	$stmt = $db->prepare("REPLACE INTO access (room_id, user_id, thread_id) values(:roomid, :userid, :threadid)");
	$stmt->bindParam(":roomid", $room_id, PDO::PARAM_INT);
	$stmt->bindParam(":userid", $user_id, PDO::PARAM_INT);
	$stmt->bindParam(":threadid", $thread_id, PDO::PARAM_STR);

	foreach ($recipients as $recipient_info) {
		$item = insertTimelineItem($html, $menu_items, $recipient_info['user_token']);
		$user_id = $recipient_info['user_id'];
		$thread_id = $item['id'];
		$stmt->execute();
	}
	return $item;
}

function prompt_for_clue($room_info) {
	$logo_url = SERVICE_BASE_URL."images/logoVertical.png";
	$html = <<<EndOfCreatorCluePrompt
<article>
	<figure>
		<img src="{$logo_url}" />
	</figure>
	<section>
		<table class="text-small" align-justify">
			<tbody>
				<tr>
					<td>I spy with my glass eye, something...<em>What (Reply to prompt)?</em></td>
				</tr>
			</tbody>
		</table>
	</section>
</article>
EndOfCreatorCluePrompt;
	$spoken_text = "I spy with my glass eye, something";
	$room_id = $room_info['room_id'];
	$user_id = null;
	$thread_id = '';
	$menu_items = array(
		add_menu_item('REPLY')
	);
	$item = insertTimelineItem($html, $menu_items, $room_info['user_token'], null, true, true, true, $spoken_text);

	$message_id = insert_message_into_db($room_id, $room['room_creator_user_id'], 'eye-spy demo', '', $html, date("Y-m-d H:i:s"),$item['id']);

	insert_message_timeline_into_db($message_id, $room['room_creator_user_id'], $item['id'], $item['created']);

	global $db;
	$stmt = $db->prepare('UPDATE rooms SET room_timeline_id=:timelineid WHERE room_id=:roomid');
	$stmt->bindValue(':timelineid', $item['id'],	PDO::PARAM_STR);
	$stmt->bindValue(':roomid',		$room_id,		PDO::PARAM_INT);
	$stmt->execute();
	return $item;
}

function create_room($creator_info, $photo_url) {
	global $db;

	$stmt = $db->prepare(
		'INSERT INTO rooms 
			(
				room_name, 
				room_creator_user_id, 
				room_create_timestamp, 
				image_url
			)
		VALUES
			(
				:name,
				:uid,
				:ts,
				:image
			)
	');
	$stmt->bindValue(':name',	$creator_info['user_name'], PDO::PARAM_STR);
	$stmt->bindValue(':uid', 	$creator_info['user_id'],	PDO::PARAM_INT);
	$stmt->bindValue(':ts',		date("Y-m-d H:i:s"),		PDO::PARAM_STR);
	$stmt->bindValue(':image',	$photo_url,					PDO::PARAM_STR);
	$stmt->execute();
	return $db->lastInsertId();
}

/**
 * Gets room information by room id (including owner user information)
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @author nickmoline
 * @param int $room_id Room ID To get information about
 * @return array Array of information from the database
 */
function get_room_info_by_id($room_id) {
	global $db;
	$stmt = $db->prepare("SELECT r.*, o.* FROM rooms r INNER JOIN users o ON (r.user_id=o.user_id) WHERE r.room_id = :roomid");
	$stmt->bindValue(":roomid", $room_id, PDO::PARAM_INT);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get a Message information, including user information and room information by timeline id
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @author nickmoline
 * @param string $timeline_id Timeline ID of message
 * @return array Array of information from the database
 */
function get_message_by_timeline_id($timeline_id) {
	global $db;
	$stmt = $db->prepare(
		"SELECT 
				t.*, m.*, u.*, r.* 
			FROM messages_timeline t 
			INNER JOIN messages m ON (t.message_id = m.message_id) 
			INNER JOIN users u ON (t.user_id = u.user_id) 
			INNER JOIN rooms r ON (m.room_id = r.room_id)
			WHERE t.timeline_id = :timelineid"
	);
	$stmt->bindValue(":timelineid", $timeline_id, PDO::PARAM_STR);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get the recipients for a room
 *
 * @version 0.1
 * @since 0.1
 * @author nickmoline
 * @param int $room_id Room ID to get recipients for
 * @param bool $game_start [Default False] - This is the start of a game
 * @return array Array of Recipients
 */
function get_room_recipients($room_id, $game_start = false) {
	global $db;
	if ($game_start) {
		$stmt = $db->prepare("SELECT * FROM users u");
	} else {
		$stmt = $db->prepare("SELECT a.*,u.* FROM access a INNER JOIN users u ON (a.user_id = u.user_id) WHERE r.room_id = :roomid");
		$stmt->bindValue(":roomid", $room_id, PDO::PARAM_INT);
	}
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Sends Answer back to a member of the room
 * 
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @see insertTimelineItem()
 * @param string $question Question that was asked
 * @param string $response ID of the Response
 * @param array $room_info Information for the Game Room (including info about the creator)
 * @param array $asker_info Information for the asker of the yes/no question
 * @return obj Timeline Item object
 */
function send_answer_back_to_team($question, $response, $room_info, $asker_info) {

	if ($response == 'answer-win') {
		$spy_image_left = $room_info['image_url'];
		$answer_text = 'You Win!';
	} else {
		
	}
	$spy_image_left = SERVICE_BASE_URL.'/images/glasseye-icon-left.png';

	$more_questions = true;

	switch ($response) {
		case 'answer-win':
			$spy_image_left = $room_info['image_url'];
			$answer_text = 'You Win!';
			$more_questions = false;
			break;
		case 'answer-yes':
			$answer_text = 'Yes!';
			break;
		case 'answer-no':
			$answer_text = 'No';
			break;
	}

	$html = <<<EndOfCreatorAnswer
<article>
	<figure>
		<img src="{$spy_image_left}" />
	</figure>
	<section>
		<table class="text-small" align-justify">
			<tbody>
				<tr>
					<td>{$asker_info['name']} asked:</td>
				</tr>
				<tr>
					<td>{$question}</td>
				</tr>
				<tr>
					<td align="right">&mdash;<em>{$room_info['name']}</em> answered: <strong>{$answer_text}</strong></td>
				</tr>
			</tbody>
		</table>
	</section>
</article>
EndOfCreatorAnswer;

	$spoken_text = "{$asker_info['name']} asks: {$question} - {$room_info['name']} Answers: {$answer_text}";

	$menu_items = array();

	if ($more_questions) {
		$menu_items[] = add_menu_item('REPLY');
	}

	$room_id = $room_info['room_id'];
	$message_id = insert_message_into_db($room_id, $room['room_creator_user_id'], $question, $answer_text, $html, date("Y-m-d H:i:s"));
	
	$recipients = get_room_recipients($room_info['room_id'], false);

	foreach ($recipients as $recipient_info) {
		$item = insertTimelineItem($html, $menu_items, $recipient_info['user_token'], $recipient_info['thread_id'], true, true, $spoken_text);	
		insert_message_timeline_into_db($message_id, $recipient_info['user_id'], $item['id'], $item['created']);
	}
}

/**
 * Insert message into the database and return its id
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @param int $room_id Room ID
 * @param int $creator_uid Creator User ID
 * @param string $message_text Message Plain Text
 * @param string $message_response Response Plain Text
 * @param string $message_html Full HTML version of message
 * @param string $message_timestamp in format strtotime() can understand
 * @param string $created_timeline_id [Optional] Optional Timeline ID of the created item
 * @return int $message_id
 */
function insert_message_into_db($room_id, $creator_uid, $message_text, $message_response = '', $message_html, $message_timestamp, $created_timeline_id = '') {
	global $db;
	
	$message_stmt = $db->prepare(
		"INSERT INTO messages
			(
				message_room_id, 
				message_creator_user_id, 
				message_creator_timeline_id, 
				message_text,
				message_response,
				message_html, 
				message_timestamp
			)
		VALUES
			(
				:roomid,
				:creatoruid,
				:creatortimelineid,
				:messagetext,
				:messageresponse,
				:messagehtml,
				:messagets
			)"
	);
	$message_stmt->bindValue(":roomid", $room_id, PDO::PARAM_INT);
	$message_stmt->bindValue(":creatoruid", $creator_uid, PDO::PARAM_INT);
	$message_stmt->bindValue(":creatortimelineid", $created_timeline_id, PDO::PARAM_STR);
	$message_stmt->bindValue(":messagetext", $message_text, PDO::PARAM_STR);
	$message_stmt->bindValue(":messageresponse", $message_response, PDO::PARAM_STR);
	$message_stmt->bindValue(":messagehtml", $message_html, PDO::PARAM_STR);
	$message_stmt->bindValue(":messagets", date("Y-m-d H:i:s",strtotime($message_timestamp)), PDO::PARAM_STR);

	$message_stmt->execute();
	return $db->lastInsertId();
}

/**
 * Insert individual recipient information for a message
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @param int $message_id Message ID
 * @param int $recip_uid Recipient User ID
 * @param string $recip_timeline_id Timeline Item ID
 * @param string $recip_sentts in format strtotime() can understand
 * @return int $message_timeline_id
 */
function insert_message_timeline_into_db($message_id, $recip_uid, $recip_timeline_id, $recip_sentts) {
	global $db;
	static $recip_stmt;
	static $a_message_id;
	static $a_recip_uid;
	static $a_recip_timeline_id;
	static $a_recip_sentts;

	if (!$recip_stmt) {
		$recip_stmt = $db->prepare(
			"INSERT INTO messages_timeline 
				(
					message_id, 
					user_id, 
					timeline_id, 
					sent_timestamp
				) 
			VALUES 
				(
					:messageid, 
					:userid, 
					:timelineid, 
					:senttimestamp
				)"
		);

		$recip_uid = null;
		$recip_timeline_id = null;
		$recip_sentts = null;

		$recip_stmt->bindParam(":messageid", $a_message_id, PDO::PARAM_INT);
		$recip_stmt->bindParam(":userid", $a_recip_uid, PDO::PARAM_INT);
		$recip_stmt->bindParam(":timelineid", $a_recip_timeline_id, PDO::PARAM_STR);
		$recip_stmt->bindParam(":senttimestamp", $a_recip_sentts, PDO::PARAM_STR);
	}

	$a_message_id			= $message_id;
	$a_recip_uid			= $recip_uid;
	$a_recip_timeline_id	= $recip_timeline_id;
	$a_recip_sentts			= date("Y-m-d H:i:s",strtotime($recip_sentts));

	$recip_stmt->execute();
	return $db->lastInsertId();
}

/**
 * Sends a Yes/No/Win question to the creator
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @see insertTimelineItem()
 * @param string $question Question to ask the game creator
 * @param array $room_info Information for the Game Room (including info about the creator)
 * @param array $asker_info Information for the asker of the yes/no question
 * @return obj Timeline Item object
 */
function ask_creator_question($question, $room_info, $asker_info) {

	$spy_image_left = SERVICE_BASE_URL.'/images/glasseye-icon-left.png';

	$html = <<<EndOfCreatorQuestion
<article>
	<figure>
		<img src="{$spy_image_left}" />
	</figure>
	<section>
		<table class="text-small" align-justify">
			<tbody>
				<tr>
					<td>{$question}</td>
				</tr>
				<tr>
					<td align="right">&mdash;<em>{$asker_info['name']}</em></td>
				</tr>
			</tbody>
		</table>
	</section>
</article>
EndOfCreatorQuestion;

	$spoken_text = "{$asker_info['name']} asks: {$question}";

	// Add Menu Items
	$menu_items = array(
		custom_menu_item('answer-win',	'You Win!',		'award_star_gold_3.png'),
		custom_menu_item('answer-yes',	'Yes', 			'tick.png'),
		custom_menu_item('answer-no',	'No',			'cross.png'),
	);

	$message_id = insert_message_into_db($room_id, $room['room_creator_user_id'], $question, $answer_text, $html, $item['created']);
	$item = insertTimelineItem($html, $menu_items, $room_info['token'], $room_info['timelineid'], true, true, $spoken_text);
	insert_message_timeline_into_db($message_id, $recipient_info['user_id'], $item['id'], $item['created']);
	return $item;
} 



/**
 * Custom Menu Item for Timeline Items
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @param string $id ID for response
 * @param string $label Label
 * @param string $icon_filename Filename for the icon for the menu item
 * @return obj Google_MenuItem()
 */
function custom_menu_item($id, $label, $icon_filename) {
	$menuItem = new Google_MenuItem();
	$menuValue = new Google_MenuValue();

	$menuValue->setDisplayName($label);
	$menuValue->setIconUrl(SERVICE_BASE_URL."/silk/icons/".$icon_filename);
	$menuItem->setValues(array($menuValue));
	$menuItem->setAction("CUSTOM");
	$menuItem->setId($id);
	return $menuItem;
}

/**
 * Built In Menu Items
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @param string $action Menu Action [REPLY,REPLY_ALL,DELETE,SHARE,READ_ALOUD,VOICE_CALL,NAVIGATE]
 * @return obj Google_MenuItem()
 */
function add_menu_item($action = 'REPLY') {
	$menuItem = new Google_MenuItem();
	$menuItem->setAction($action);
	return $menuItem;
}

function subscribeToNotifications($service, $userToken, $callbackUrl) {
  try {
    $subscription = new Google_Subscription();
    $subscription->setCollection('timeline');
    $subscription->setUserToken($userToken);
    $subscription->setCallbackUrl($callbackUrl);
    $service->subscriptions->insert($subscription);
  } catch (Exception $e) {
    print 'An error ocurred: ' . $e->getMessage();
  }
}

function login_user() {
	session_start();
	$token = null;

	global $plus;
	global $glass;
	global $client;

	$client = get_gclient(null,true);
	$plus = new Google_PlusService($client);
	$glass = new Google_GlassService($client);
	if (array_key_exists('token',$_SESSION)) {
		$token = $_SESSION['token'];
		$client->setAccessToken($token);
	} elseif (array_key_exists('code',$_GET)) {
		$client->authenticate();
		$_SESSION['token'] = $token = $client->getAccessToken();
	}

	if (!$token) {
		header('Location: ' . $client->createAuthUrl());
		die();
	}

	$profile = $plus->people->get("me");

	$plus_id = $profile['id'];
	$plus_name = $profile['displayName'];

	save_userinfo($token, $plus_id, $plus_name);

	insertShareTarget($glass, "glass-eye", "Glass Eye", SERVICE_BASE_URL."images/logoHorizontal.png");
	subscribeToNotifications($glass, $plus_id, SERVICE_BASE_URL."notify.php");
	return $token;
}

function save_userinfo($token, $plus_id, $plus_name) {
	global $db;
	
	$existing_user = get_user_by_plusid($plus_id);

	if ($existing_user) {
		$stmt = $db->prepare(
			"UPDATE users u
				SET 
					u.user_name = :username,
					u.user_token = :usertoken,
					u.user_plus_id = :userplusid
				WHERE
					u.user_id = :existinguid"
		);
		$stmt->bindValue(":existinguid",	$existing_user['user_id'],	PDO::PARAM_INT);
		$stmt->bindValue(":username",		$plus_name,					PDO::PARAM_STR);
		$stmt->bindValue(":usertoken",		$token,						PDO::PARAM_STR);
		$stmt->bindValue(":userplusid",		$plus_id,					PDO::PARAM_STR);
		$stmt->execute();
		return $existing_user['user_id'];
	} else {
		$stmt = $db->prepare(
			"INSERT INTO users
				(
					user_name,
					user_token,
					user_plus_id
				)
			VALUES
				(
					:username,
					:usertoken,
					:userplusid
				)"
		);
		$stmt->bindValue(":username",		$plus_name,					PDO::PARAM_STR);
		$stmt->bindValue(":usertoken",		$token,						PDO::PARAM_STR);
		$stmt->bindValue(":userplusid",		$plus_id,					PDO::PARAM_STR);
		$stmt->execute();
		return $db->lastInsertId();
	}	
}

function get_user_by_id($user_id) {
	global $db;

	$stmt = $db->prepare("SELECT * FROM users WHERE user_id = :userid");
	$stmt->bindValue(":userid", $user_id, PDO::PARAM_INT);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);	
}

function get_user_by_plusid($plus_id) {
	global $db;

	$stmt = $db->prepare("SELECT * FROM users WHERE user_plus_id = :plusid");
	$stmt->bindValue(":plusid", $plus_id, PDO::PARAM_STR);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_user_by_token($token) {
	global $db;

	$stmt = $db->prepare("SELECT * FROM users WHERE user_token = :token");
	$stmt->bindValue(":token", $token, PDO::PARAM_STR);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Insert Share Target
 */
function insertShareTarget($service, $shareTargetId, $displayName, $iconUrl) {
  try {
    $shareTarget = new Google_Entity();
    $shareTarget->setId($shareTargetId);
    $shareTarget->setDisplayName($displayName);
    $shareTarget->setImageUrls(array($iconUrl));
    return $service->shareTargets->insert($shareTarget);
  } catch (Exception $e) {
    print 'An error ocurred: ' . $e->getMessage();
    return null;
  }
}

// Just a couple of functions that help us figure out who is logged in
function getProfile($service, $user_id) {
  return $service->people->get($user_id);
}

function getCurrentProfileId($service) {
  $current_user = getProfile($service, "me");
  return $current_user['id'];
}

function getCurrentProfileName($service) {
	$current_user = getProfile($service, "me");
	return $current_user['name']['formatted'];
}
