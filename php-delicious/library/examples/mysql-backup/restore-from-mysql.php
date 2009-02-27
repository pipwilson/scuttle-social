<?php
   // Import into del.icio.us from MySql
   // Written by Ed Eliot (www.ejeliot.com) - November 2006
   
   require('../../php-delicious.inc.php'); // see www.ejeliot.com/pages/php-delicious for more details on library
   
   define('DELICIOUS_USER', 'YOUR_USER');
   define('DELICIOUS_PASS', 'YOUR_PASS');
   
   define('MYSQL_SERVER', 'YOUR_HOST');
   define('MYSQL_DB', 'delicious');
   define('MYSQL_USER', 'YOUR_USER');
   define('MYSQL_PASS', 'YOUR_PASS');
   
   define('POSTS_TABLE', 'posts');
   define('TAGS_TABLE', 'tags');
   
   $oDelicious = new PhpDelicious(DELICIOUS_USER, DELICIOUS_PASS);
   
   if ($oDb = mysql_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS)) {
      if (mysql_select_db(MYSQL_DB, $oDb)) {
         if ($oPosts = mysql_query("select url, description, notes, hash, updated from posts order by updated asc", $oDb)) {
            while ($aPost = mysql_fetch_assoc($oPosts)) {
               $aTags = array();
               if ($oTags = mysql_query(sprintf("select tag from tags where hash = '%s' order by tag asc", $aPost['hash']))) {
                  while ($aTag = mysql_fetch_assoc($oTags)) {
                     $aTags[] = $aTag['tag'];
                  }
               }
               $oDelicious->AddPost($aPost['url'], $aPost['description'], $aPost['notes'], $aTags, $aPost['updated'], true);
            }
         }
      } else {
         echo mysql_error($oDb);
      }
      mysql_close($oDb);
   } else {
      echo "Could not connect to MySql server.";
   }
?>