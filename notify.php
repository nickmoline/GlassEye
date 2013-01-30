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

	$user_info = get_user_by_plusid($notification['userToken']);

	$glass = get_glass($user_info['user_token']);
	$client = get_gclient($user_info['user_token']);

	if (array_key_exists('menuActions',$notification)) {
		foreach ($notification['menuActions'] as $action) {
			$previous_answer = get_message_by_timeline_id($notification['itemId']);
			$asker_info = get_user_by_id($previous_answer['message_creator_user_id']);
			$room_info = get_room_info_by_id($previous_answer['room_id']);

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
		$dump = fopen ('/tmp/glassnotifydump.txt','a');
		fwrite($dump, print_r($glass,true));
		fwrite($dump, print_r($notification,true));
		$timelineItem = $glass->timeline->get($notification['itemId']);
		fwrite($dump, print_r($timelineItem,true));

		if (array_key_exists('inReplyTo', $timelineItem)) {
			$parent_item = $timelineItem['inReplyTo'];
			$parent_item_info = get_message_by_timeline_id($parent_item);
			$room_info = get_room_info_by_id($parent_item_info['room_id']);
			fwrite($dump, print_r($room_info,true));
			if (!$room_info['room_timeline_id'] && $room_info['room_creator_user_id'] == $user_info['user_id']) {
				$clue = send_clue_out($timelineItem['text'], $room_info);
				fwrite($dump, "CLUE!!!");
				fwrite($dump, print_R($clue,true));
				fwrite($dump, "CLUE!!!");
			}
		} else {
			$share_targets = array();
			if (array_key_exists('shareTargets',$timelineItem)) {
				$share_targets = $timelineItem['shareTargets'];
			}
			//$share_targets = $timelineItem->getShareTargets();
			fwrite($dump, print_r($share_targets, true));

			$timeline_id = $timelineItem['id'];

			$image_url = '';

			foreach ($share_targets as $share_target) {
				if ($share_target['id'] == 'glass-eye') {
					$attachments = $timelineItem['attachments'];
					if ($attachments) {
						foreach ($attachments as $attachment) {
							$request = new Google_HttpRequest($attachment['contentUrl'], 'GET', null, null);
	    					
	    					$httpRequest = Google_Client::$io->authenticatedRequest($request);
							if ($httpRequest->getResponseHttpCode() == 200) {
								$image_file = $httpRequest->getResponseBody();
								$out = fopen(dirname(__FILE__).'/spied/'.$timeline_id.'.jpg','w');
								fwrite($out, $image_file);
								$image_url = SERVICE_BASE_URL.'spied/'.$timeline_id.'.jpg';



							}

						}
						$room_id = create_room($user_info, $image_url);
						$room_info = get_room_info_by_id($room_id);
						fwrite($dump,print_r($room_info,true));
						$timeline_prompt = prompt_for_clue($room_info);
						fwrite($dump,print_r($timeline_prompt,true));
					}
				}
			}
		}
	}
}
fclose( $file );
