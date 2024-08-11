<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeacherDashboardController extends AbstractController
{
    #[Route('/teacher/dashboard', name: 'teacher_dashboard')]
    public function index(): Response
    {
        return $this->render('teacher/teacher_dashboard.html.twig', [
            'controller_name' => 'TeacherDashboardController',
        ]);
    }
}
