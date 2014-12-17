<?php
/**
 * @file
 * Contains \ExportNodeNews.
 */

require '/vagrant/wordpress/build/euei/export_data/ExportNodeBase.php';

class ExportNodeNews extends  ExportNodeBase {

  protected $bundle = 'news';

  protected $originalBundle = 'blog_post';
}

// Export.
$handler = new ExportNodeNews();
$handler->export();