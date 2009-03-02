<?php

require('php-delicious/php-delicious.inc.php'); // see www.ejeliot.com/pages/php-delicious for more details on library

class DeliciousService {
    var $db;
    var $oDelicious;

    // getInstance must take & $db
    function & getInstance(& $db) {
        static $instance;
        if (!isset ($instance))
            $instance = & new DeliciousService($db);
        return $instance;
    }

    function DeliciousService(& $db) {
        global $delicious_user, $delicious_password;
        $this->db = & $db;
        $this->oDelicious = new PhpDelicious($delicious_user, $delicious_password);
    }

    function AddBookmark($url, $title, $description, $tags, $status) {
        // in scuttle a bookmark->status of '2' means private
        if ($status==2) {
            $shared = false;
        } else {
            $shared = true;
        }

        $tags = explode(",", $tags);

        $this->oDelicious->AddPost($url, $title, $description, $tags, $shared);
    }
}
?>
