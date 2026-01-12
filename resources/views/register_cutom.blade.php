<!DOCTYPE html>
<html>
<head>
    <title>Inscription Sécurisée</title>
    <style> /* Copiez le style de l'index ci-dessus */ </style>
</head>
<body>
    <div class="card">
        <h2>Créer un compte</h2>
        <form action="/auth/register" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div style="margin-bottom: 10px;">
                <input type="text" name="name" placeholder="Nom complet" required style="padding: 8px; width: 250px;">
            </div>
            <div style="margin-bottom: 10px;">
                <input type="email" name="email" placeholder="Email" required style="padding: 8px; width: 250px;">
            </div>
            <div style="margin-bottom: 10px;">
                <input type="password" name="password" placeholder="Mot de passe (min 12)" required style="padding: 8px; width: 250px;">
            </div>
            <button type="submit" class="btn">Finaliser l'inscription</button>
        </form>
    </div>
</body>
</html>