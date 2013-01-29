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

// Dumps received notifications to a text file
// This is where you would do cool stuff based on notifications

require_once("config.php");
require_once("mirror-client.php");

$filename = "/tmp/glass-notify.txt";
$file = fopen( $filename, "a+" );
if( $file == false ) {
	echo ( "Error in opening new file" );
	exit();
}
$post_body = file_get_contents('php://input');
if($post_body != null) {
	fwrite ($file, "$post_body\n");
	$notification = json_decode($post_body, true);

	$glass = get_glass();

	if (array_key_exists('menuActions',$notification)) {
		foreach ($notification['menuActions'] as $action) {
			$previous_answer = get_message_by_timeline_id($notification['itemId']);
			$asker_info = get_user_by_id($previous_answer['message_creator_user_id']);
			$room_info = get_room_by_id($previous_answer['room_id']);

			$game_over = false;

			switch ($action['id']) {
				case 'answer-win':
					$game_over = true;

				case 'answer-yes':
				case 'answer-no':
				case 'answer-invalid':
					send_answer_back_to_team($previous_answer['message_text'], $action['id'], $room_info, $asker_info);
					break;
				default:
					
					break;
			}
		}
	} else {
		$timelineItem = $glass->timeline->get($notification['itemId']);

		$share_targets = $timelineItem->getShareTargets();

		$timeline_id = $timelineItem->getId();

		$image_url = '';

		foreach ($share_targets as $share_target) {
			if ($share_target->getId() == 'glass-eye') {
				$attachments = $timelineItem->getAttachments();
				if ($attachments) {
					foreach ($attachments as $attachment) {
						$request = new Google_HttpRequest($attachment['contentUrl'], 'GET', null, null);
    					$httpRequest = Google_Client::$io->authenticatedRequest($request);
						if ($httpRequest->getResponseHttpCode() == 200) {
							$image_file = $httpRequest->getResponseBody();
							$out = fopen(dirname(__FILE__).'/spied/'.$timeline_id.'.jpg','w');
							fwrite($out, $image_file);
							$image_url = SERVICE_BASE_URL.'spied/'.$timeline_id.'.jpg';

							$room_id = create_room(get_user_by_plusid($notification['userToken']), $image_url);

							$room_info = get_room_info_by_id($room_id);


						}
					}
				}
			}
		}
	}
}
fclose( $file );
