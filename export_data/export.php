<?php

require '/vagrant/wordpress/build/euei/export_data/ExportNodeBase.php';
require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeDocument.php';
require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeNews.php';


// Document.
$handler = new ExportNodeDocument();
$handler->export();

// News
//$handler = new ExportNodeNews();
//$handler->export();

