<?php
   require('../../php-delicious.inc.php');
   
   define('DELICIOUS_USER', 'YOUR_USER');
   define('DELICIOUS_PASS', 'YOUR_PASS');
   
   $oDelicious = new PhpDelicious(DELICIOUS_USER, DELICIOUS_PASS);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Simple Table of All Posts</title>
	<style type="text/css">
	   body { font-family: Arial, sans-serif; }
	   h1 { font-size: 121%; }
	   table { font-size: 92%; }
	   th { background: #ccc; padding: 7px; text-align: left; }
	   td { border-bottom: 1px solid #ccc; padding: 7px; }
	</style>
</head>

<body>
   <h1>Simple Table of All Posts</h1>
	<?php if ($aPosts = $oDelicious->GetAllPosts()) { ?>
	   <p><?=count($aPosts) ?> posts in this account. Results cached for 10 seconds (by default).</p>
      <table>
      <tr>
         <th>Description</th>
         <th>Notes</th>
         <th>Last Updated</th>
      </tr>
         <?php foreach ($aPosts as $aPost) { ?>
            <tr>
               <td><a href="<?=$aPost['url'] ?>"><?=$aPost['desc'] ?></a></td>
               <td><?=$aPost['notes'] ?></td>
               <td><?=$aPost['updated'] ?></td>
            </tr>
         <?php } ?>
      </table>
   <?php
      } else {
         echo $oDelicious->LastErrorString();
      }
   ?>
</body>

</html>