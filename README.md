# Integration of EUEI and EUWI communities of practice on Capacity4dev

## Pre-setup
1. Protect user emails. (add .test to end of a users emails)  
`drush scr export_data/prepare/protect_email.php`  
For remove .test run this script with `--unprotect=1` option  
`drush scr export_data/prepare/protect_email.php --unprotect=1`
2. Setup path of directory that contain source files.  
`drush vset c4d_migrate_files_path "/home/ilya/projects/migrate/distr/"`
3. Patch drupal __dbtng__ module.  
In file __/sites/all/modules/contrib/dbtng/database/query.inc__ fix `__clone` method.  
```php
function __clone() {
  $this->changed = TRUE;
  foreach ($this->conditions as $key => $condition) {
    if ($key !== '#conjunction' && $condition['field'] instanceOf QueryConditionInterface) {
      $this->conditions[$key]['field'] = clone($condition['field']);
    }
  }
}
```
4. For make work clean url on __dev__ version need put _.htaccess_ file in root directory.  
https://github.com/drupal/drupal/blob/6.x/.htaccess
5. Order of run migration:  
``First Part: EuUser -> EuProfile -> EuMembership``  
``Second Part: EuNews -> EuEvent -> EuDocument -> EuComment``  
6. Before migrate `EuMembership` apply next path via ``/devel/php`` link:  
```php
$ret = array();
db_add_field($ret, 'migrate_map_eumembership', 'destid1', array('type' => 'int', 'length' => 11));
$ret = array();
db_add_field($ret, 'migrate_map_eumembership', 'destid2', array('type' => 'int', 'length' => 11));
```
7. Before migrate `EuProfile` disable auto title generation for `People` type in `Content-type -> People -> Edit`.

## Post-setup
1. Enable auto title generation for the `People` contenty type via `Content-type -> People -> Edit`.
2. Remove .test on the end of email for all users  
`drush scr export_data/prepare/protect_email.php --unprotect=1`
3. Put .htaccess files to EUEI and EUWI server for make redirects from old groups to new on C4D.

## Stuff

On current step all groups should be made manually.

### Script for setup C4D system from zero on dev machine. 
```bash
#!/bin/sh

# Drop exists database.
echo "Drop old database\n";
yes | drush sql-drop

# Import a dump of live C4D database.
echo "Import a new database.\n"
drush sql-cli < ~/projects/migrate/distr/C4D_original_dump_last.sql
# drush sql-cli < ~/projects/migrate/distr/C4D.sql

# Change password for admin to 'admin'.
echo "Update password for the admin\n"
drush sql-query "UPDATE users SET pass='21232f297a57a5a743894a0e4a801fc3' WHERE uid='1'"

# Import a 'exported tables'.
echo "Import 'exported tables'"
drush sql-cli < ~/projects/migrate/distr/exported-data.sql

# Import a new users. (Limited version)
#echo "Import _gizra_user_limit\n"
#drush sql-cli < ~/projects/migrate/distr/_gizra_user_limit.sql

# Import a new news. (Limited version)
#echo "Import _gizra_node_blog_post_limit\n"
#drush sql-cli < ~/projects/migrate/distr/_gizra_node_blog_post_limit.sql

# Import a new events. (Limited version)
#echo "Import _gizra_node_event_limit\n"
#drush sql-cli < ~/projects/migrate/distr/_gizra_node_event_limit.sql

# And clean a cache.
echo "Clean a cache\n"
drush cc all

# Enable migrate_ui module
echo "Enable migrate_ui module\n"
yes | drush en migrate_ui

# Reenable migrate_eu module.
echo "Reenable migrate_eu module\n"
yes | drush dis migrate_eu 
yes | drush en migrate_eu

# Enable devel module.
echo "Enable devel module\n"
yes | drush en devel

# finish
echo "Finished!\n"
```
