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
     * @Route("/teacher", name="teacher_login_form")
     */
    public function loginForm(): Response
    {
        return $this->render('teacher/index.html.twig');
    }

    /**
     * @Route("/teacher/authenticate", name="teacher_authenticate", methods={"POST"})
     */
    public function authenticate(Request $request, TeacherRepository $teacherRepository): Response
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $teacher = $teacherRepository->findOneBy(['username' => $username]);

        if ($teacher && $teacher->getPassword() === $password) {
            return $this->redirectToRoute('teacher_dashboard');
        } else {
            $this->addFlash('error', 'Invalid username or password');
            return $this->redirectToRoute('teacher_login_form');
        }
    }

    /**
     * @Route("/teacher/dashboard", name="teacher_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('teacher/dashboard.html.twig');
    }


    /**
     * @Route("/teacher/account", name="teacher_account")
     */
    public function account(): Response
    {
        // Optionally, fetch the current logged-in teacher's details if needed
        //$teacher = $this->getUser();

        return $this->render('teacher/account.html.twig', [
           // 'teacher' => $teacher,
        ]);
    }


    /**
     * @Route("/teacher/edit-account", name="teacher_edit_account")
     */
    public function editAccount(): Response
    {
        // Fetch and handle the form for editing teacher details here

    return $this->render('teacher/edit_account.html.twig');
    }


    /**
     * @Route("/teacher/update-account", name="teacher_update_account", methods={"POST"})
     */
    //public function updateAccount(Request $request): Response   
    //{
    // Handle form submission and update teacher details

    // Redirect or render a response
    //}


}
