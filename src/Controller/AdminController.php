<?php

namespace App\Controller;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/create-teacher', name: 'admin_create_teacher')]
    public function createTeacher(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $teacher = new Teacher();
            $teacher->setPrefix($request->request->get('prefix'));
            $teacher->setFirstName($request->request->get('firstName'));
            $teacher->setLastName($request->request->get('lastName'));
            $teacher->setUsername($request->request->get('username'));
            $teacher->setPassword(password_hash($request->request->get('password'), PASSWORD_BCRYPT));
            $teacher->setNumber($request->request->get('number'));
            $teacher->setEmail($request->request->get('email'));
            $roles = (array) $request->request->get('roles');
            $teacher->setRoles(['ROLE_TEACHER']); // Ensure roles is an array

            $em->persist($teacher);
            $em->flush();

            $this->addFlash('success', 'Teacher created successfully!');

            return $this->redirectToRoute('admin_create_teacher');
        }

        return $this->render('admin/createteacher.html.twig');
    }
}
