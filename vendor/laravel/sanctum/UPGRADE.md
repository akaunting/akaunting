# Upgrade Guide

## Upgrading To 3.0 From 2.x

### Minimum Versions

The following dependency versions have been updated:

- The minimum PHP version is now v8.0.2
- The minimum Laravel version is now v9.21

### New `expires_at` Column

Sanctum now supports expiring tokens. To support this feature, a new `expires_at` column must be added to your application's `personal_access_tokens` table. To add the column to your table, create a migration with the following schema change:

```php
Schema::table('personal_access_tokens', function (Blueprint $table) {
    $table->timestamp('expires_at')->nullable()->after('last_used_at');
});
```
