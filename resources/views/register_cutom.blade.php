<!DOCTYPE html>
<html>
<head>
    <title>Inscription Sécurisée</title>
    <style>  * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            text-align: center;
            padding: 50px 20px;
            background: linear-gradient(135deg, #f4f7f6, #d9e2ec);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card */
        .card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            transition: transform 0.3s, box-shadow 0.3s;
            max-width: 400px;
            width: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        /* Titles & text */
        h1 {
            font-size: 2em;
            margin-bottom: 15px;
            color: #333;
        }
        p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #5558d8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #4346b5;
            transform: translateY(-2px);
        }
        .btn.secondary {
            background: #2dce89;
        }
        .btn.secondary:hover {
            background: #28b77b;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .card {
                padding: 30px 20px;
            }
            h1 {
                font-size: 1.5em;
            }
        } </style>
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