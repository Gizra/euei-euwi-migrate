<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12/17/14
 * Time: 9:18 AM
 */

require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeDocument.php';
require '/vagrant/wordpress/build/euei/export_data/node/ExportNodeNews.php';


// Document.
$handler = new ExportNodeDocument();
$handler->export();

// News
$handler = new ExportNodeNews();
$handler->export();