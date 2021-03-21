@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../phpstan/phpstan/phpstan.phar
php "%BIN_TARGET%" %*
