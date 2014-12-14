#!/bin/bash

# Recreate table users and blog post.
echo Recreate DB Tables.
`drush sql-connect` < createTable.sql

drush scr node/document.php
drush scr node/news.php


