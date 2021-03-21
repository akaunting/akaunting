@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../symfony/var-dumper/Resources/bin/var-dump-server
php "%BIN_TARGET%" %*
