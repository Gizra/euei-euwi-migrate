#!/bin/bash

# Recreate table
`drush sql-connect` < createTable.sql
echo DB Table recreated successful.




