#!/bin/bash

# Recreate table
`drush sql-connect` < createTable.sql
echo DB Table recreated successful.

# Export news as blog post.
drush scr export_news.php
echo Exported news as blog post. Done!
