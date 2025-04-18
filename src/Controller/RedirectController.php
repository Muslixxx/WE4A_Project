<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RedirectController extends AbstractController
{
    #[Route('/redirect_by_role', name: 'redirect_by_role')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function redirectByRole(): Response
    {
        $user = $this->getUser();

        if (!$user || !method_exists($user, 'getRole')) {
            return $this->redirectToRoute('app_login');
        }

        return match ($user->getRole()) {
            'ROLE_ADMIN'    => $this->redirectToRoute('app_admin'),
            default         => $this->redirectToRoute('app_menu'),
        };
    }
}
