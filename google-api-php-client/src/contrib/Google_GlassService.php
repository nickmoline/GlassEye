<?php
/*
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */


  /**
   * The "attachments" collection of methods.
   * Typical usage is:
   *  <code>
   *   $glassService = new Google_GlassService(...);
   *   $attachments = $glassService->attachments;
   *  </code>
   */
  class Google_AttachmentsServiceResource extends Google_ServiceResource {


    /**
     * Gets an attachment by id. (attachments.get)
     *
     * @param string $itemId The ID of the timeline item the attachment is associated with.
     * @param string $attachmentId The ID of the attachment.
     * @param array $optParams Optional parameters.
     * @return Google_Empty
     */
    public function get($itemId, $attachmentId, $optParams = array()) {
      $params = array('itemId' => $itemId, 'attachmentId' => $attachmentId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Empty($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "locations" collection of methods.
   * Typical usage is:
   *  <code>
   *   $glassService = new Google_GlassService(...);
   *   $locations = $glassService->locations;
   *  </code>
   */
  class Google_LocationsServiceResource extends Google_ServiceResource {


    /**
     * Gets a single location by id. (locations.get)
     *
     * @param string $id The ID of the location or latest for the last known location.
     * @param array $optParams Optional parameters.
     * @return Google_Location
     */
    public function get($id, $optParams = array()) {
      $params = array('id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Location($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "shareTargets" collection of methods.
   * Typical usage is:
   *  <code>
   *   $glassService = new Google_GlassService(...);
   *   $shareTargets = $glassService->shareTargets;
   *  </code>
   */
  class Google_ShareTargetsServiceResource extends Google_ServiceResource {


    /**
     * Deletes a share target. (shareTargets.delete)
     *
     * @param string $id The ID of the share target.
     * @param array $optParams Optional parameters.
     */
    public function delete($id, $optParams = array()) {
      $params = array('id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('delete', array($params));
      return $data;
    }
    /**
     * Gets a single share target by id. (shareTargets.get)
     *
     * @param string $id The ID of the share target.
     * @param array $optParams Optional parameters.
     * @return Google_Entity
     */
    public function get($id, $optParams = array()) {
      $params = array('id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Entity($data);
      } else {
        return $data;
      }
    }
    /**
     * Inserts a new share target. (shareTargets.insert)
     *
     * @param Google_Entity $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Entity
     */
    public function insert(Google_Entity $postBody, $optParams = array()) {
      $params = array('postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Entity($data);
      } else {
        return $data;
      }
    }
    /**
     * Retrieves a list of share targets for the authenticated user. (shareTargets.list)
     *
     * @param array $optParams Optional parameters.
     * @return Google_ShareTargetsListResponse
     */
    public function listShareTargets($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_ShareTargetsListResponse($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates a share target in place. This method supports patch semantics. (shareTargets.patch)
     *
     * @param string $id The ID of the share target.
     * @param Google_Entity $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Entity
     */
    public function patch($id, Google_Entity $postBody, $optParams = array()) {
      $params = array('id' => $id, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('patch', array($params));
      if ($this->useObjects()) {
        return new Google_Entity($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates a share target in place. (shareTargets.update)
     *
     * @param string $id The ID of the share target.
     * @param Google_Entity $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Entity
     */
    public function update($id, Google_Entity $postBody, $optParams = array()) {
      $params = array('id' => $id, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('update', array($params));
      if ($this->useObjects()) {
        return new Google_Entity($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "subscriptions" collection of methods.
   * Typical usage is:
   *  <code>
   *   $glassService = new Google_GlassService(...);
   *   $subscriptions = $glassService->subscriptions;
   *  </code>
   */
  class Google_SubscriptionsServiceResource extends Google_ServiceResource {


    /**
     * Deletes a subscription. (subscriptions.delete)
     *
     * @param string $id The ID of the subscription.
     * @param array $optParams Optional parameters.
     */
    public function delete($id, $optParams = array()) {
      $params = array('id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('delete', array($params));
      return $data;
    }
    /**
     * Creates a new subscription. (subscriptions.insert)
     *
     * @param Google_Subscription $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Subscription
     */
    public function insert(Google_Subscription $postBody, $optParams = array()) {
      $params = array('postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Subscription($data);
      } else {
        return $data;
      }
    }
    /**
     * Retrieves a list of subscriptions for the authenticated user and service. (subscriptions.list)
     *
     * @param array $optParams Optional parameters.
     * @return Google_SubscriptionsListResponse
     */
    public function listSubscriptions($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_SubscriptionsListResponse($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates an existing subscription in place. (subscriptions.update)
     *
     * @param string $id The ID of the subscription.
     * @param Google_Subscription $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Subscription
     */
    public function update($id, Google_Subscription $postBody, $optParams = array()) {
      $params = array('id' => $id, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('update', array($params));
      if ($this->useObjects()) {
        return new Google_Subscription($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "timeline" collection of methods.
   * Typical usage is:
   *  <code>
   *   $glassService = new Google_GlassService(...);
   *   $timeline = $glassService->timeline;
   *  </code>
   */
  class Google_TimelineServiceResource extends Google_ServiceResource {


    /**
     * Deletes a timeline item. (timeline.delete)
     *
     * @param string $timelineId The ID of the timeline item.
     * @param array $optParams Optional parameters.
     */
    public function delete($timelineId, $optParams = array()) {
      $params = array('timelineId' => $timelineId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('delete', array($params));
      return $data;
    }
    /**
     * Gets a single timeline item by id. (timeline.get)
     *
     * @param string $id The ID of the timeline item.
     * @param array $optParams Optional parameters.
     * @return Google_TimelineItem
     */
    public function get($id, $optParams = array()) {
      $params = array('id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_TimelineItem($data);
      } else {
        return $data;
      }
    }
    /**
     * Inserts a new item into the timeline. (timeline.insert)
     *
     * @param Google_TimelineItem $postBody
     * @param array $optParams Optional parameters.
     * @return Google_TimelineItem
     */
    public function insert(Google_TimelineItem $postBody, $optParams = array()) {
      $params = array('postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_TimelineItem($data);
      } else {
        return $data;
      }
    }
    /**
     * Retrieves a list of timeline items for the authenticated user. (timeline.list)
     *
     * @param array $optParams Optional parameters.
     *
     * @opt_param bool includeDeleted If true, tombstone records for deleted items will be returned.
     * @opt_param string maxResults The maximum number of items to include in the response, used for paging.
     * @opt_param string orderBy Controls the order in which timeline items are returned.
     * @opt_param string pageToken Token for the page of results to return.
     * @opt_param bool pinnedOnly If true, only pinned items will be returned.
     * @return Google_TimelineListResponse
     */
    public function listTimeline($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_TimelineListResponse($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates a timeline item in place. This method supports patch semantics. (timeline.patch)
     *
     * @param string $id The ID of the timeline item.
     * @param Google_TimelineItem $postBody
     * @param array $optParams Optional parameters.
     * @return Google_TimelineItem
     */
    public function patch($id, Google_TimelineItem $postBody, $optParams = array()) {
      $params = array('id' => $id, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('patch', array($params));
      if ($this->useObjects()) {
        return new Google_TimelineItem($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates a timeline item in place. (timeline.update)
     *
     * @param string $id The ID of the timeline item.
     * @param Google_TimelineItem $postBody
     * @param array $optParams Optional parameters.
     * @return Google_TimelineItem
     */
    public function update($id, Google_TimelineItem $postBody, $optParams = array()) {
      $params = array('id' => $id, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('update', array($params));
      if ($this->useObjects()) {
        return new Google_TimelineItem($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Glass (v1).
 *
 * <p>
 * API for interacting with Glass users via the timeline.
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="https://devsite.googleplex.com/glass" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_GlassService extends Google_Service {
  public $attachments;
  public $locations;
  public $shareTargets;
  public $subscriptions;
  public $timeline;
  /**
   * Constructs the internal representation of the Glass service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = 'glass/v1/';
    $this->version = 'v1';
    $this->serviceName = 'glass';

    $client->addService($this->serviceName, $this->version);
    $this->attachments = new Google_AttachmentsServiceResource($this, $this->serviceName, 'attachments', json_decode('{"methods": {"get": {"id": "glass.attachments.get", "path": "attachments/{itemId}/{attachmentId}", "httpMethod": "GET", "parameters": {"attachmentId": {"type": "string", "required": true, "location": "path"}, "itemId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Empty"}, "supportsMediaDownload": true}}}', true));
    $this->locations = new Google_LocationsServiceResource($this, $this->serviceName, 'locations', json_decode('{"methods": {"get": {"id": "glass.locations.get", "path": "locations/{id}", "httpMethod": "GET", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Location"}}}}', true));
    $this->shareTargets = new Google_ShareTargetsServiceResource($this, $this->serviceName, 'shareTargets', json_decode('{"methods": {"delete": {"id": "glass.shareTargets.delete", "path": "shareTargets/{id}", "httpMethod": "DELETE", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}}, "get": {"id": "glass.shareTargets.get", "path": "shareTargets/{id}", "httpMethod": "GET", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Entity"}}, "insert": {"id": "glass.shareTargets.insert", "path": "shareTargets", "httpMethod": "POST", "request": {"$ref": "Entity"}, "response": {"$ref": "Entity"}}, "list": {"id": "glass.shareTargets.list", "path": "shareTargets", "httpMethod": "GET", "response": {"$ref": "ShareTargetsListResponse"}}, "patch": {"id": "glass.shareTargets.patch", "path": "shareTargets/{id}", "httpMethod": "PATCH", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Entity"}, "response": {"$ref": "Entity"}}, "update": {"id": "glass.shareTargets.update", "path": "shareTargets/{id}", "httpMethod": "PUT", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Entity"}, "response": {"$ref": "Entity"}}}}', true));
    $this->subscriptions = new Google_SubscriptionsServiceResource($this, $this->serviceName, 'subscriptions', json_decode('{"methods": {"delete": {"id": "glass.subscriptions.delete", "path": "subscriptions/{id}", "httpMethod": "DELETE", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}}, "insert": {"id": "glass.subscriptions.insert", "path": "subscriptions", "httpMethod": "POST", "request": {"$ref": "Subscription"}, "response": {"$ref": "Subscription"}}, "list": {"id": "glass.subscriptions.list", "path": "subscriptions", "httpMethod": "GET", "response": {"$ref": "SubscriptionsListResponse"}}, "update": {"id": "glass.subscriptions.update", "path": "subscriptions/{id}", "httpMethod": "PUT", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Subscription"}, "response": {"$ref": "Subscription"}}}}', true));
    $this->timeline = new Google_TimelineServiceResource($this, $this->serviceName, 'timeline', json_decode('{"methods": {"delete": {"id": "glass.timeline.delete", "path": "timeline/{timelineId}", "httpMethod": "DELETE", "parameters": {"timelineId": {"type": "string", "required": true, "location": "path"}}}, "get": {"id": "glass.timeline.get", "path": "timeline/{id}", "httpMethod": "GET", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "TimelineItem"}}, "insert": {"id": "glass.timeline.insert", "path": "timeline", "httpMethod": "POST", "request": {"$ref": "TimelineItem"}, "response": {"$ref": "TimelineItem"}, "supportsMediaUpload": true, "mediaUpload": {"accept": ["audio/*", "image/*", "video/*"], "maxSize": "10MB", "protocols": {"simple": {"multipart": true, "path": "/upload/glass/v1/timeline"}, "resumable": {"multipart": true, "path": "/resumable/upload/glass/v1/timeline"}}}}, "list": {"id": "glass.timeline.list", "path": "timeline", "httpMethod": "GET", "parameters": {"includeDeleted": {"type": "boolean", "location": "query"}, "maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "orderBy": {"type": "string", "enum": ["displayTime", "writeTime"], "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "pinnedOnly": {"type": "boolean", "location": "query"}}, "response": {"$ref": "TimelineListResponse"}}, "patch": {"id": "glass.timeline.patch", "path": "timeline/{id}", "httpMethod": "PATCH", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "TimelineItem"}, "response": {"$ref": "TimelineItem"}}, "update": {"id": "glass.timeline.update", "path": "timeline/{id}", "httpMethod": "PUT", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "TimelineItem"}, "response": {"$ref": "TimelineItem"}, "supportsMediaUpload": true, "mediaUpload": {"accept": ["audio/*", "image/*", "video/*"], "maxSize": "10MB", "protocols": {"simple": {"multipart": true, "path": "/upload/glass/v1/timeline/{id}"}, "resumable": {"multipart": true, "path": "/resumable/upload/glass/v1/timeline/{id}"}}}}}}', true));

  }
}

class Google_Attachment extends Google_Model {
  public $contentType;
  public $contentUrl;
  public $id;
  public $thumbnailUrl;
  public function setContentType($contentType) {
    $this->contentType = $contentType;
  }
  public function getContentType() {
    return $this->contentType;
  }
  public function setContentUrl($contentUrl) {
    $this->contentUrl = $contentUrl;
  }
  public function getContentUrl() {
    return $this->contentUrl;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getId() {
    return $this->id;
  }
  public function setThumbnailUrl($thumbnailUrl) {
    $this->thumbnailUrl = $thumbnailUrl;
  }
  public function getThumbnailUrl() {
    return $this->thumbnailUrl;
  }
}

class Google_Empty extends Google_Model {
  public $kind;
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
}

class Google_Entity extends Google_Model {
  public $acceptTypes;
  public $displayName;
  public $email;
  public $id;
  public $imageUrls;
  public $kind;
  public $phoneNumber;
  public $priority;
  public $secondaryEmails;
  public $secondaryPhoneNumbers;
  public $source;
  public $type;
  public function setAcceptTypes(/* array(Google_string) */ $acceptTypes) {
    $this->assertIsArray($acceptTypes, 'Google_string', __METHOD__);
    $this->acceptTypes = $acceptTypes;
  }
  public function getAcceptTypes() {
    return $this->acceptTypes;
  }
  public function setDisplayName($displayName) {
    $this->displayName = $displayName;
  }
  public function getDisplayName() {
    return $this->displayName;
  }
  public function setEmail($email) {
    $this->email = $email;
  }
  public function getEmail() {
    return $this->email;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getId() {
    return $this->id;
  }
  public function setImageUrls(/* array(Google_string) */ $imageUrls) {
    $this->assertIsArray($imageUrls, 'Google_string', __METHOD__);
    $this->imageUrls = $imageUrls;
  }
  public function getImageUrls() {
    return $this->imageUrls;
  }
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
  public function setPhoneNumber($phoneNumber) {
    $this->phoneNumber = $phoneNumber;
  }
  public function getPhoneNumber() {
    return $this->phoneNumber;
  }
  public function setPriority($priority) {
    $this->priority = $priority;
  }
  public function getPriority() {
    return $this->priority;
  }
  public function setSecondaryEmails(/* array(Google_string) */ $secondaryEmails) {
    $this->assertIsArray($secondaryEmails, 'Google_string', __METHOD__);
    $this->secondaryEmails = $secondaryEmails;
  }
  public function getSecondaryEmails() {
    return $this->secondaryEmails;
  }
  public function setSecondaryPhoneNumbers(/* array(Google_string) */ $secondaryPhoneNumbers) {
    $this->assertIsArray($secondaryPhoneNumbers, 'Google_string', __METHOD__);
    $this->secondaryPhoneNumbers = $secondaryPhoneNumbers;
  }
  public function getSecondaryPhoneNumbers() {
    return $this->secondaryPhoneNumbers;
  }
  public function setSource($source) {
    $this->source = $source;
  }
  public function getSource() {
    return $this->source;
  }
  public function setType($type) {
    $this->type = $type;
  }
  public function getType() {
    return $this->type;
  }
}

class Google_Location extends Google_Model {
  public $accuracy;
  public $address;
  public $displayName;
  public $kind;
  public $latitude;
  public $longitude;
  public $timestamp;
  public function setAccuracy($accuracy) {
    $this->accuracy = $accuracy;
  }
  public function getAccuracy() {
    return $this->accuracy;
  }
  public function setAddress($address) {
    $this->address = $address;
  }
  public function getAddress() {
    return $this->address;
  }
  public function setDisplayName($displayName) {
    $this->displayName = $displayName;
  }
  public function getDisplayName() {
    return $this->displayName;
  }
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
  public function setLatitude($latitude) {
    $this->latitude = $latitude;
  }
  public function getLatitude() {
    return $this->latitude;
  }
  public function setLongitude($longitude) {
    $this->longitude = $longitude;
  }
  public function getLongitude() {
    return $this->longitude;
  }
  public function setTimestamp($timestamp) {
    $this->timestamp = $timestamp;
  }
  public function getTimestamp() {
    return $this->timestamp;
  }
}

class Google_MenuItem extends Google_Model {
  public $action;
  public $id;
  public $state;
  protected $__valuesType = 'Google_MenuValue';
  protected $__valuesDataType = 'array';
  public $values;
  public function setAction($action) {
    $this->action = $action;
  }
  public function getAction() {
    return $this->action;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getId() {
    return $this->id;
  }
  public function setState($state) {
    $this->state = $state;
  }
  public function getState() {
    return $this->state;
  }
  public function setValues(/* array(Google_MenuValue) */ $values) {
    $this->assertIsArray($values, 'Google_MenuValue', __METHOD__);
    $this->values = $values;
  }
  public function getValues() {
    return $this->values;
  }
}

class Google_MenuValue extends Google_Model {
  public $displayName;
  public $iconUrl;
  public $state;
  public function setDisplayName($displayName) {
    $this->displayName = $displayName;
  }
  public function getDisplayName() {
    return $this->displayName;
  }
  public function setIconUrl($iconUrl) {
    $this->iconUrl = $iconUrl;
  }
  public function getIconUrl() {
    return $this->iconUrl;
  }
  public function setState($state) {
    $this->state = $state;
  }
  public function getState() {
    return $this->state;
  }
}

class Google_Notification extends Google_Model {
  public $collection;
  public $itemId;
  protected $__menuActionsType = 'Google_MenuItem';
  protected $__menuActionsDataType = 'array';
  public $menuActions;
  public $operation;
  public $userToken;
  public $verifyToken;
  public function setCollection($collection) {
    $this->collection = $collection;
  }
  public function getCollection() {
    return $this->collection;
  }
  public function setItemId($itemId) {
    $this->itemId = $itemId;
  }
  public function getItemId() {
    return $this->itemId;
  }
  public function setMenuActions(/* array(Google_MenuItem) */ $menuActions) {
    $this->assertIsArray($menuActions, 'Google_MenuItem', __METHOD__);
    $this->menuActions = $menuActions;
  }
  public function getMenuActions() {
    return $this->menuActions;
  }
  public function setOperation($operation) {
    $this->operation = $operation;
  }
  public function getOperation() {
    return $this->operation;
  }
  public function setUserToken($userToken) {
    $this->userToken = $userToken;
  }
  public function getUserToken() {
    return $this->userToken;
  }
  public function setVerifyToken($verifyToken) {
    $this->verifyToken = $verifyToken;
  }
  public function getVerifyToken() {
    return $this->verifyToken;
  }
}

class Google_NotificationConfig extends Google_Model {
  public $deliveryTime;
  public $level;
  public function setDeliveryTime($deliveryTime) {
    $this->deliveryTime = $deliveryTime;
  }
  public function getDeliveryTime() {
    return $this->deliveryTime;
  }
  public function setLevel($level) {
    $this->level = $level;
  }
  public function getLevel() {
    return $this->level;
  }
}

class Google_ShareTargetsListResponse extends Google_Model {
  protected $__itemsType = 'Google_Entity';
  protected $__itemsDataType = 'array';
  public $items;
  public $kind;
  public function setItems(/* array(Google_Entity) */ $items) {
    $this->assertIsArray($items, 'Google_Entity', __METHOD__);
    $this->items = $items;
  }
  public function getItems() {
    return $this->items;
  }
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
}

class Google_Subscription extends Google_Model {
  public $callbackUrl;
  public $collection;
  public $id;
  public $kind;
  public $operation;
  public $updated;
  public $userToken;
  public $verifyToken;
  public function setCallbackUrl($callbackUrl) {
    $this->callbackUrl = $callbackUrl;
  }
  public function getCallbackUrl() {
    return $this->callbackUrl;
  }
  public function setCollection($collection) {
    $this->collection = $collection;
  }
  public function getCollection() {
    return $this->collection;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getId() {
    return $this->id;
  }
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
  public function setOperation(/* array(Google_string) */ $operation) {
    $this->assertIsArray($operation, 'Google_string', __METHOD__);
    $this->operation = $operation;
  }
  public function getOperation() {
    return $this->operation;
  }
  public function setUpdated($updated) {
    $this->updated = $updated;
  }
  public function getUpdated() {
    return $this->updated;
  }
  public function setUserToken($userToken) {
    $this->userToken = $userToken;
  }
  public function getUserToken() {
    return $this->userToken;
  }
  public function setVerifyToken($verifyToken) {
    $this->verifyToken = $verifyToken;
  }
  public function getVerifyToken() {
    return $this->verifyToken;
  }
}

class Google_SubscriptionsListResponse extends Google_Model {
  protected $__itemsType = 'Google_Subscription';
  protected $__itemsDataType = 'array';
  public $items;
  public $kind;
  public function setItems(/* array(Google_Subscription) */ $items) {
    $this->assertIsArray($items, 'Google_Subscription', __METHOD__);
    $this->items = $items;
  }
  public function getItems() {
    return $this->items;
  }
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
}

class Google_TimelineItem extends Google_Model {
  protected $__attachmentsType = 'Google_Attachment';
  protected $__attachmentsDataType = 'array';
  public $attachments;
  public $bundleId;
  public $created;
  protected $__creatorType = 'Google_Entity';
  protected $__creatorDataType = '';
  public $creator;
  public $displayTime;
  public $etag;
  public $html;
  public $htmlPages;
  public $id;
  public $inReplyTo;
  public $isDeleted;
  public $isPinned;
  public $kind;
  protected $__locationType = 'Google_Location';
  protected $__locationDataType = '';
  public $location;
  protected $__menuItemsType = 'Google_MenuItem';
  protected $__menuItemsDataType = 'array';
  public $menuItems;
  protected $__notificationType = 'Google_NotificationConfig';
  protected $__notificationDataType = '';
  public $notification;
  public $selfLink;
  protected $__shareTargetsType = 'Google_Entity';
  protected $__shareTargetsDataType = 'array';
  public $shareTargets;
  public $speakableText;
  public $text;
  public $title;
  public $updated;
  public function setAttachments(/* array(Google_Attachment) */ $attachments) {
    $this->assertIsArray($attachments, 'Google_Attachment', __METHOD__);
    $this->attachments = $attachments;
  }
  public function getAttachments() {
    return $this->attachments;
  }
  public function setBundleId($bundleId) {
    $this->bundleId = $bundleId;
  }
  public function getBundleId() {
    return $this->bundleId;
  }
  public function setCreated($created) {
    $this->created = $created;
  }
  public function getCreated() {
    return $this->created;
  }
  public function setCreator(Google_Entity $creator) {
    $this->creator = $creator;
  }
  public function getCreator() {
    return $this->creator;
  }
  public function setDisplayTime($displayTime) {
    $this->displayTime = $displayTime;
  }
  public function getDisplayTime() {
    return $this->displayTime;
  }
  public function setEtag($etag) {
    $this->etag = $etag;
  }
  public function getEtag() {
    return $this->etag;
  }
  public function setHtml($html) {
    $this->html = $html;
  }
  public function getHtml() {
    return $this->html;
  }
  public function setHtmlPages(/* array(Google_string) */ $htmlPages) {
    $this->assertIsArray($htmlPages, 'Google_string', __METHOD__);
    $this->htmlPages = $htmlPages;
  }
  public function getHtmlPages() {
    return $this->htmlPages;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getId() {
    return $this->id;
  }
  public function setInReplyTo($inReplyTo) {
    $this->inReplyTo = $inReplyTo;
  }
  public function getInReplyTo() {
    return $this->inReplyTo;
  }
  public function setIsDeleted($isDeleted) {
    $this->isDeleted = $isDeleted;
  }
  public function getIsDeleted() {
    return $this->isDeleted;
  }
  public function setIsPinned($isPinned) {
    $this->isPinned = $isPinned;
  }
  public function getIsPinned() {
    return $this->isPinned;
  }
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
  public function setLocation(Google_Location $location) {
    $this->location = $location;
  }
  public function getLocation() {
    return $this->location;
  }
  public function setMenuItems(/* array(Google_MenuItem) */ $menuItems) {
    $this->assertIsArray($menuItems, 'Google_MenuItem', __METHOD__);
    $this->menuItems = $menuItems;
  }
  public function getMenuItems() {
    return $this->menuItems;
  }
  public function setNotification(Google_NotificationConfig $notification) {
    $this->notification = $notification;
  }
  public function getNotification() {
    return $this->notification;
  }
  public function setSelfLink($selfLink) {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink() {
    return $this->selfLink;
  }
  public function setShareTargets(/* array(Google_Entity) */ $shareTargets) {
    $this->assertIsArray($shareTargets, 'Google_Entity', __METHOD__);
    $this->shareTargets = $shareTargets;
  }
  public function getShareTargets() {
    return $this->shareTargets;
  }
  public function setSpeakableText($speakableText) {
    $this->speakableText = $speakableText;
  }
  public function getSpeakableText() {
    return $this->speakableText;
  }
  public function setText($text) {
    $this->text = $text;
  }
  public function getText() {
    return $this->text;
  }
  public function setTitle($title) {
    $this->title = $title;
  }
  public function getTitle() {
    return $this->title;
  }
  public function setUpdated($updated) {
    $this->updated = $updated;
  }
  public function getUpdated() {
    return $this->updated;
  }
}

class Google_TimelineListResponse extends Google_Model {
  protected $__itemsType = 'Google_TimelineItem';
  protected $__itemsDataType = 'array';
  public $items;
  public $kind;
  public $nextPageToken;
  public function setItems(/* array(Google_TimelineItem) */ $items) {
    $this->assertIsArray($items, 'Google_TimelineItem', __METHOD__);
    $this->items = $items;
  }
  public function getItems() {
    return $this->items;
  }
  public function setKind($kind) {
    $this->kind = $kind;
  }
  public function getKind() {
    return $this->kind;
  }
  public function setNextPageToken($nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }
  public function getNextPageToken() {
    return $this->nextPageToken;
  }
}
