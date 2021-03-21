@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../enlightn/security-checker/security-checker
php "%BIN_TARGET%" %*
