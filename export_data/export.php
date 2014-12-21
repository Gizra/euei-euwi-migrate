<?php

require '/vagrant/wordpress/build/euei/export_data/ExportNodeBase.php';
require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeDocument.php';
require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeNews.php';
require '/vagrant/wordpress/build/euei/export_data/comment/ExportComment.php';


// Document.
$handler = new ExportNodeDocument();
$handler->export();

// News
$handler = new ExportNodeNews();
$handler->export();

// Comments
$handler = new ExportComment();
$handler->export();
