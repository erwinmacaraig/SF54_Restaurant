<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    
    /**
     * @Route("/start/{name?}", name="start")
     */
    public function start(Request $request, $name)
    {
        return new Response('<h1>'.$name.'</h1>');
    }

    /**
     * @Route("/test/view", name="view_template")
     */
    public function view(){
        $tag = date("l");
        
        return $this->render('test/view.html.twig', [
            'd' => $tag
        ]);
    }


}
