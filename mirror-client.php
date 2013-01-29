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
require_once 'sample/plus-client.php';


/**
 * Returns Google API Client object
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @return obj client object
 */
function get_gclient($access_token = null) {
	static $client;

	if (!$client) {
		$client = new Google_Client();
		$client->setApplicatonName(APPLICATION_NAME);
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
	static $glass;

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
	static $plus;

	if (!$plus) {
		$plus = Google_PlusService(get_gclient($access_token));
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
	if ($read_aloud) {
		$menuItem = new Google_MenuItem();
		$menuItem->setAction("READ_ALOUD");
		array_push($menu_items, $menuItem);
		$timelineItem->setSpeakableText($spoken_text);
	}

	if ($bundle_id) $timelineItem->setBundleId($bundle_id);

	$timelineItem->setMenuItems($menu_items);

	if ($notificationLevel != null) {
		$notification = new Google_NotificationConfig();
		$notification->setLevel($notificationLevel);
		$timelineItem->setNotification($notification);
	}
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
	$html = <<<EndOfCreatorClue
<article>
	<figure>
		<img src="{$spy_image_left}" />
	</figure>
	<section>
		<table class="text-small" align-justify">
			<tbody>
				<tr>
					<td>{$room_info['name']} spied with {$room_info['gender']} glass eye, something...</td>
				</tr>
				<tr>
					<td>{$clue}</td>
				</tr>
			</tbody>
		</table>
	</section>
</article>
EndOfCreatorClue;

	$spoken_text = "{$room_info['name']} spied with {$room_info['gender']} glass eye, something {$clue}";

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
		$item = insertTimelineItem($html, $menu_items, $recipient_info['token']);
		$user_id = $recipient_info['user_id'];
		$thread_id = $item['id'];
		$stmt->execute();
	}
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
	return $stmt->fetchAll();
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
		$menu_items[] = add_menu_item('ask-question', 'REPLY');
	}

	$room_id = $room_info['room_id'];
	$recipients = get_room_recipients($room_info['room_id'], false);
	foreach ($recipients as $recipient_info) {
		$item = insertTimelineItem($html, $menu_items, $recipient_info['token'], $recipient_info['threadid'], true, true, $spoken_text);
	}
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

	return insertTimelineItem($html, $menu_items, $room_info['token'], $room_info['timelineid'], true, true, $spoken_text);
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
 * @param string $id ID for response
 * @param string $action Menu Action [REPLY,REPLY_ALL,DELETE,SHARE,READ_ALOUD,VOICE_CALL,NAVIGATE]
 * @return obj Google_MenuItem()
 */
function add_menu_item($id, $action = 'REPLY') {
	$menuItem = new Google_MenuItem();
	$menuItem->setAction($action);
	$menuItem->setId($id);
	return $menuItem;
}

