@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../nesbot/carbon/bin/carbon
php "%BIN_TARGET%" %*
