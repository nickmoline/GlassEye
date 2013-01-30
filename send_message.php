<?php
/**
 * Send Yes/No Question Test
 *
 * @author nickmoline
 * @version 0.1
 * @since 0.1
 * @package glasseye
 * @subpackage tests
 */


require_once 'config.php';
require_once 'mirror-client.php';

ini_set('display_errors','On');


$room_id = 41;

$room_info = get_room_info_by_id($room_id);

print_r($room_info);

send_clue_out("Red", $room_info);