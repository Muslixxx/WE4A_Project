{% extends 'base.html.twig' %}

{% block body %}
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h4>Connexion</h4>
            </div>
            <div class="card-body">
                <form action="{{ path('app_login') }}" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" id="username" class="form-control" name="_username" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" class="form-control" name="_password" required>
                    </div>

                    <div class="Ed-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                        <a href="{{ path('app_register') }}" class="btn btn-link">S'inscrire</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
