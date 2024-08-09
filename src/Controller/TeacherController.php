<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'teacher_login')]
    public function login(): Response
    {
        return $this->render('teacher/login.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }
}
