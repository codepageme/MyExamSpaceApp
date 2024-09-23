<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class StudentController extends AbstractController
{

//Student login logic -----------------------------------------------------------------------------

    // Student login form route
    #[Route('/student', name: 'student_login_form')]
    public function index(AuthenticationUtils $authenticationUtils, ClassroomRepository $classroomRepository): Response
    {
        // Get any login errors and the last firstname entered
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastFirstname = $authenticationUtils->getLastUsername();

        // Fetch all classrooms from the database
        $classrooms = $classroomRepository->findAll();

        return $this->render('student/index.html.twig', [
            'last_firstname' => $lastFirstname,
            'error' => $error,
            'classrooms' => $classrooms, // Pass classrooms to the template
        ]);
    }



     // Handle the student authentication request
    #[Route('/student/access', name: 'student_access', methods: ['POST'])]
    public function authenticate(Request $request, StudentRepository $studentRepository): Response
    {
        // Get data from request
        $firstname = $request->request->get('_firstname');
        $lastname = $request->request->get('_lastname');
        $classroom = $request->request->get('_classroom');
        $csrfToken = $request->request->get('_csrf_token');

        // Validate CSRF token
        if (!$this->isCsrfTokenValid('access', $csrfToken)) {
            throw new InvalidCsrfTokenException('Invalid CSRF token');
        }

        // Validate that all fields are filled
        if (!$firstname || !$lastname || !$classroom) {
            $this->addFlash('error', 'All fields are required');
            return $this->redirectToRoute('student_login_form');
        }

        // Check if the student exists by firstname, lastname, and classroom
        $student = $studentRepository->findOneBy([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classroom' => $classroom
        ]);

        if ($student) {
            // Use the container to get the session service
            $this->container->get('session')->set('student_id', $student->getId());

            // Authentication successful - redirect to student dashboard
            return $this->redirectToRoute('student_dashboard');
        } else {
            // Invalid credentials - send back to login form with error message
            $this->addFlash('error', 'Invalid credentials');
            return $this->redirectToRoute('student_login_form');
        }
    }



//Student logout logic --------------------------------------------------------------------------
    
    #[Route('/student/logout', name: 'student_logout')]
    public function logout()
    {
        // The Symfony logout system will handle session destruction
    }



//Student dashboard logic --------------------------------------------------------------------------

// src/Controller/StudentController.php

#[Route('/student/dashboard', name: 'student_dashboard')]
public function dashboard(): Response
{
    // You can add more data to render for the student dashboard here
    return $this->render('student/dashboard.html.twig', [
        'student' => $this->getUser(),  // Assuming the student is logged in
    ]);
}







}
