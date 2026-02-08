@extends('layouts.auth')

@section('content')
<div class="card">
    <div class="card-header"><h4>Créer un compte sécurisé</h4></div>
    <div class="card-body">
        <form method="POST" action="{{ route('auth.register.store') }}">
            @csrf
            <div class="mb-3">
                <label>Nom complet</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
                <small>Sécurité : Utilisez au moins 12 caractères.</small>
            </div>
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
    </div>
</div>
@endsection