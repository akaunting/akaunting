// Ajouter dans routes/web.php
Route::any('/public/api/register.php', function() {
    return require base_path('api/register.php');
});

Route::any('/api/login.php', function() {
    return require base_path('/public/api/login.php');
});

Route::any('/api/check-auth.php', function() {
    return require base_path('/public/api/check-auth.php');
});