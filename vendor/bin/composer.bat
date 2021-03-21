@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../composer/composer/bin/composer
php "%BIN_TARGET%" %*
