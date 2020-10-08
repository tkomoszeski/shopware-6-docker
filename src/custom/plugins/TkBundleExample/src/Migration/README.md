#How to do migrations

## 1. Creating migration based on plugin
php bin/console database:create-migration --name Bundle -p TheCorrectPluginName

## 2. Create sql database schema in vendor folder
php bin/console dal:create:schema

## 3. Paste all the SQL Statements from ProjectRoot/schem/swa.sql into seperate
Use the InheritanceUpdaterTrait to update inheritance of 'bundles' for the product entity
