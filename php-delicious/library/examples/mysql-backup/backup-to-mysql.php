<?php
   // Export from del.icio.us to MySql
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
   
   if ($aPosts = $oDelicious->GetAllPosts()) {
      if ($oDb = mysql_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS)) {
         if (mysql_select_db(MYSQL_DB, $oDb)) {
            mysql_query('delete from '.POSTS_TABLE, $oDb);
            mysql_query('delete from '.TAGS_TABLE, $oDb);
            $sInsertPosts = 'insert into '.POSTS_TABLE.' (url, description, notes, hash, updated) values';
            $sInsertTags = 'insert into '.TAGS_TABLE.' (hash, tag) values';
            foreach ($aPosts as $aPost) {
               $sInsertPosts .= sprintf(" ('%s', '%s', '%s', '%s', '%s'),", 
                  mysql_real_escape_string($aPost['url'], $oDb), 
                  mysql_real_escape_string($aPost['desc'], $oDb), 
                  mysql_real_escape_string($aPost['notes'], $oDb),
                  mysql_real_escape_string($aPost['hash'], $oDb), 
                  mysql_real_escape_string($aPost['updated'], $oDb)
               );
               foreach ($aPost['tags'] as $sTag) {
                  $sInsertTags .= sprintf(" ('%s', '%s'),", 
                     mysql_real_escape_string($aPost['hash'], $oDb), 
                     mysql_real_escape_string($sTag, $oDb)
                  );
               }
            }
            mysql_query(rtrim($sInsertPosts, ','), $oDb);
            mysql_query(rtrim($sInsertTags, ','), $oDb);
         } else {
            echo mysql_error($oDb);
         }
         mysql_close($oDb);
      } else {
         echo "Could not connect to MySql server.";
      }
   } else {
      echo $oDelicious->LastErrorString();
   }
?>