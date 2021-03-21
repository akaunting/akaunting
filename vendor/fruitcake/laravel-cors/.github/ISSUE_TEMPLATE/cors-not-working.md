---
name: CORS not working
about: CORS requests are blocked, follow these steeps
title: ''
labels: ''
assignees: ''

---

**Before you start**

[ ] Update to the latest version by running `composer update fruitcake/laravel-cors`
[ ] Make sure that Apache/nginx/Valet are NOT also adding CORS headers

**Check your config**

[ ]  Double-check your config file with the version from the repo. Make sure the `paths` property is correctly set. Start by allowing as much as possible.
[ ]  Make sure the middleware is added to the global middleware in your Http Kernel (not group)

**Clear your caches**

Please do these steps again before submitting an issue:
[ ]  Clear your config cache `php artisan config:clear`, route cache (`php artisan route:clear`) and normal cache (`php artisan cache:clear`).
[ ]  Make sure your permissions are setup correctly (eg. storage is writable)

**Make the request**

Open Chrome Devtools to see which requests are actually happening. Make sure you see the actual OPTIONS requests for POST/PUT/DELETE (see https://stackoverflow.com/questions/57410051/chrome-not-showing-options-requests-in-network-tab)

Please show the actual request + response headers as sent by the OPTIONS request and the POST request (when available)
