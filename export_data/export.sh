#!/bin/bash

# Recreate table users and blog post.
echo Recreate DB Tables.
`drush sql-connect` < createTable.sql
drush scr export.php

# The command needs drush alias
drush @euwi scr export.php --site-name=euwi

# Export DB tables to SQL file.
drush sql-dump --tables-list=_gizra_user,_gizra_node_blog_post,_gizra_node_document,_gizra_node_event,_gizra_og_membership,_gizra_comment > exported-data.sql
