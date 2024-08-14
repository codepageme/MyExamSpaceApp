<?php
// src/Controller/TeacherController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Teacher;
use App\Repository\TeacherRepository;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="teacher_index")
     */
    public function index()
    {
        return $this->render('teacher/index.html.twig');
    }

    /**
     * @Route("/teacher/login", name="teacher_login")
     */
    public function login(Request $request, TeacherRepository $teacherRepository)
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');

            $teacher = $teacherRepository->findOneBy(['Username' => $username]);

            if ($teacher && $teacher->getPassword() === $password) {
                return $this->redirectToRoute('teacher_dashboard');
            } else {
                $this->addFlash('error', 'Invalid username or password');
            }
        }

        return $this->render('teacher/login.html.twig');
    }

    /**
     * @Route("/teacher/dashboard", name="teacher_dashboard")
     */
    public function dashboard()
    {
        return $this->render('teacher/dashboard.html.twig');
    }
}