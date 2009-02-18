<?php
/***************************************************************************
Copyright (C) 2005 - 2006 Scuttle project
http://sourceforge.net/projects/scuttle/
http://scuttle.org/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
***************************************************************************/

header('Content-Type: text/xml; charset=UTF-8');
header('Last-Modified: '. gmdate("D, d M Y H:i:s") .' GMT');
header('Cache-Control: no-cache, must-revalidate');
require_once('header.inc.php');

$bookmarkservice = & ServiceFactory :: getServiceInstance('BookmarkService');
$bookmark = intval($_GET['id']);
if (!$bookmarkservice->editAllowed($bookmark)) {
    $result = T_('You are not allowed to delete this bookmark');
} elseif ($bookmarkservice->deleteBookmark($bookmark)) {
    $result = 'true';
} else {
    $result = T_('Failed to delete bookmark');
}
?>
<response>
  <method>deleteConfirmed</method>
  <result><?php echo $result; ?></result>
</response>