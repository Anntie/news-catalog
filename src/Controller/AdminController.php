<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin.home")
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(Security $security)
    {
        $user = $security->getUser();
        return $this->render('admin/index.html.twig', compact('user'));
    }

}