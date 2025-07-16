<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use AProud\TwiewBundle\Twiew;

class MainController extends AbstractController
{

    function __construct(Twiew $twiew)
    {
	    $this->view = $twiew;
	    $this->view->tplSchemaFromYaml('app_main_index', 'config/app_main.yaml');
    }

//#[Route('/blog/{id}', name: 'blog_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[Route('/', name: 'app_main_index')]
    public function index(): Response
    {
        return $this->view->render();
    }

     #[Route('/login', name: 'app_main_login')]
    public function login(): Response
    {
	    return $this->view->render();
    }

}