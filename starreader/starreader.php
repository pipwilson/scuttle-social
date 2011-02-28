<?php
require_once('../header.inc.php');

define('GREADER_USER_ID', '');
define('ITEM_COUNT', '20');

define('SCUTTLE_USERNAME', '');
define('SCUTTLE_PASSWORD', '');

$userservice =& ServiceFactory::getServiceInstance('UserService');

$login = $userservice->login(SCUTTLE_USERNAME, SCUTTLE_PASSWORD);

// "javascript" can be replaced with "atom" to get that representation
// http://www.google.com/reader/public/javascript/user/blah/state/com.google/starred?n=2
$json = file_get_contents('http://www.google.com/reader/public/javascript/user/'.GREADER_USER_ID.'/state/com.google/starred?n='.ITEM_COUNT);

$decoded = json_decode($json);

$bookmarkservice =& ServiceFactory::getServiceInstance('BookmarkService');

$num_returned_items = sizeof($decoded->items);

for ($i = 0; $i < $num_returned_items; $i ++) {
    $url = $decoded->items[$i]->alternate->href;
    $title = $decoded->items[$i]->title;
    $description = $decoded->items[$i]->content;

    $dt = date('c', $decoded->items[$i]->published);

    // code below re-used from api/posts_add.php

    // Error out if there's no address or description
    if (is_null($url) || is_null($description)) {
        $added = FALSE;
    } else {
    // We're good with info; now insert it!
        if ($bookmarkservice->bookmarkExists($url, $userservice->getCurrentUserId()))
            $added = FALSE;
        else
            $added = $bookmarkservice->addBookmark($url, $title, $description, null, 'starred, googlereader', $dt);
    }

}

?>