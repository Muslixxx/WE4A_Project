<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMenuController extends AbstractController
{
    #[Route('/admin/menu', name: 'admin_menu_app')]
    public function index(): Response
    {
        return $this->render('admin_menu.html.twig');
    }
}
