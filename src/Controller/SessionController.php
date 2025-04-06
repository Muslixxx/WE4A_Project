<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    public function index(Request $request): Response
    {
        // Récupérer la session depuis l'objet Request.
        $session = $request->getSession();

        // Démarrer la session si elle n'est pas encore démarrée (Symfony le fait automatiquement si nécessaire).
        if (!$session->isStarted()) {
            $session->start();
        }

        // Stocker une variable dans la session.
        $session->set('mon_role', 'admin');

        // Exemple : incrémenter un compteur de visites
        $visites = $session->get('visites', 0);
        $session->set('visites', ++$visites);

        // Récupérer la variable de session
        $monRole = $session->get('mon_role');

        // Utiliser la variable pour adapter l'affichage
        if ($monRole === 'admin') {
            $message = 'Bienvenue, administrateur. Vous avez visité cette page ' . $visites . ' fois.';
        } else {
            $message = 'Bienvenue, utilisateur. Vous avez visité cette page ' . $visites . ' fois.';
        }

        return new Response("<html><body>$message</body></html>");
    }
}
