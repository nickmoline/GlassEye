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

// Utility functions for interacting with the Mirror API
// You will probably have to modify and expand this set for your project.

require_once 'config.php';

function insertTimelineItem($service, $text, $contentType, $attachment, $notificationLevel)
{
  try {
    $timelineItem = new Google_TimelineItem();
    // alternatively use setHtml to specify an HTML payload
    $timelineItem->setText($text);

    $menuItems = array();

    // A couple of built in menu items
    $menuItem = new Google_MenuItem();
    $menuItem->setAction("READ_ALOUD");
    array_push($menuItems, $menuItem);
    $timelineItem->setSpeakableText("Hello world! The weather is nice, isn't it?");

    $menuItem = new Google_MenuItem();
    $menuItem->setAction("SHARE");
    array_push($menuItems, $menuItem);

    // A custom menu item
    $customMenuItem = new Google_MenuItem();
    $customMenuValue = new Google_MenuValue();
    $customMenuValue->setDisplayName("Save For Later"); // Displayed to user on glass
    $customMenuValue->setIconUrl($service_base_url . "/static/icons/game.png");

    $customMenuItem->setValues(array($customMenuValue));
    $customMenuItem->setAction("CUSTOM");
    $customMenuItem->setId("safe-for-later"); // This is how you identify it on the notification ping
    array_push($menuItems, $customMenuItem);

    $timelineItem->setMenuItems($menuItems);

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
    return $service->timeline->insert($timelineItem, $optParams);
  } catch (Exception $e) {
    print 'An error ocurred: ' . $e->getMessage();
    return null;
  }
}

function subscribeToNotifications($service, $userToken, $callbackUrl)
{
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

function insertShareTarget($service, $shareTargetId, $displayName, $iconUrl)
{
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