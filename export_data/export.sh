#!/bin/bash

YELLOW='\033[00;33m'
RESTORE='\033[0m'

echo "${YELLOW}Recreate exported tables.${RESTORE}"
drush sql-cli < createTable.sql

echo "${YELLOW}Run main export script for @euei project.${RESTORE}"
# drush @euei scr export.php --site-name=euei
drush scr export.php

echo "${YELLOW}Run main export script for @euwi project.${RESTORE}"
drush @euwi scr export.php --site-name=euwi

# Export DB tables to SQL file.
echo "${YELLOW}Export 'exported tables' to sql dump.${RESTORE}"
drush sql-dump --tables-list=_gizra_user,_gizra_node_blog_post,_gizra_node_document,_gizra_node_event,_gizra_og_membership,_gizra_comment > exported-data.sql

echo "${YELLOW}Finished.${RESTORE}"
