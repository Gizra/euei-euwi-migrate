#!/bin/bash

# Recreate table users and blog post.
echo Recreate DB Tables.
`drush sql-connect` < createTable.sql
drush scr export.php

# The command needs drush alias
`drush @euwi sql-connect` < createTable.sql
drush @euwi export.php --site-name=euwi
