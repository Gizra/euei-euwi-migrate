#!/bin/bash

# Recreate table users and blog post.
echo Recreate DB Tables.
`drush sql-connect` < createTable.sql

# Export news as blog post.
echo Export news as blog post...
drush scr export_news.php

# Export all acitve users
echo Export active users...
drush scr export_users.php

