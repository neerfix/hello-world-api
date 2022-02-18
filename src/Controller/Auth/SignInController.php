<?php

namespace App\Controller\Auth;


use App\Controller\HelloworldController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignInController extends HelloworldController
{
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SignInController.php',
        ]);
    }
}
