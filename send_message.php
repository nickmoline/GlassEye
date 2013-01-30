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


$room_id = 41;

$room_info = load_room_info_by_id($room_id);

send_clue_out("Red", $room_info);