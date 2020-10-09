#How to do migrations

Before running migration you should use
php bin/console plugin:refresh 

to fetch data.  
also you need to have installed the plugin and set that plugin to be active

## 1. Creating migration based on plugin

php bin/console database:create-migration --name Bundle -p TheCorrectPluginName

## 2. Create sql database schema in vendor folder
php bin/console dal:create:schema

## 3. Paste all the SQL Statements from ProjectRoot/schem/swa.sql into seperate
Use the InheritanceUpdaterTrait to update inheritance of 'bundles' for the product entity


if plugin is already installed and you just want to execute migration just use that
php bin/console database:migrate --all SwagExamplePlugin