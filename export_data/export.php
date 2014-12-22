<?php

require '/vagrant/wordpress/build/euei/export_data/ExportNodeBase.php';
require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeDocument.php';
require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeNews.php';
require '/vagrant/wordpress/build/euei/export_data/user/ExportUser.php';
require '/vagrant/wordpress/build/euei/export_data/membership/ExportOgMembership.php';


// Document.
//$handler = new ExportNodeDocument();
//$handler->export();

// News
//$handler = new ExportNodeNews();
//$handler->export();

// Users
//$handler = new ExportUser();
//$handler->export();

// Membership
$handler = new ExportOgMembership();
$handler->export();
