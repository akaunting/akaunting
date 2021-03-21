@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../psy/psysh/bin/psysh
php "%BIN_TARGET%" %*
