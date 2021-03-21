@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../seld/jsonlint/bin/jsonlint
php "%BIN_TARGET%" %*
