<?php

namespace App\Controller;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\HttpFoundation\RequestStack;

class ClassTeacherController extends AbstractController
{
    private $entityManager; // Declare the entityManager property
    private $session; // Declare the session property

    // Inject EntityManagerInterface and RequestStack via the constructor
    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager; // Assign EntityManager in the constructor
        $this->session = $requestStack->getSession(); // Get session from RequestStack
    }



    // Class teacher login form route
    #[Route('/classteacher', name: 'classteacher_login_form')]
    public function loginForm(): Response
    {
        return $this->render('classteacher/index.html.twig');
    }



    
    // Handle the class teacher authentication request
    #[Route('/classteacher/access', name: 'classteacher_access', methods: ['POST'])]
    public function authenticate(Request $request): Response
    {
        // Get data from request
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        $csrfToken = $request->request->get('_csrf_token');

        // Validate CSRF token
        if (!$this->isCsrfTokenValid('login', $csrfToken)) {
            throw new InvalidCsrfTokenException('Invalid CSRF token');
        }

        // Use entity manager to find the teacher by username
        $teacherRepository = $this->entityManager->getRepository(Teacher::class);
        $teacher = $teacherRepository->findOneBy(['username' => $username]);

        // Check if teacher exists and password is valid
        if ($teacher && password_verify($password, $teacher->getPassword())) {
            // Check if the teacher has any classrooms assigned
            if (!$teacher->getClassroom()->isEmpty()) {
                // Store teacher ID in the session
                $this->session->set('classteacher_id', $teacher->getId());

                // Redirect to class teacher dashboard
                return $this->redirectToRoute('classteacher_dashboard');
            } else {
                // No classroom assigned to the teacher
                $this->addFlash('error', 'No classroom assigned to this teacher');
            }
        } else {
            // Invalid credentials
            $this->addFlash('error', 'Invalid username or password');
        }

        // Redirect back to login form on error
        return $this->redirectToRoute('classteacher_login_form');
    }



//Classteacher dashboard logic -------------------------------------------------------------------

    #[Route('/classteacher/dashboard', name: 'classteacher_dashboard')]
    public function dashboard(): Response
    {
        // Fetch the class teacher ID from session
        $classteacherId = $this->session->get('classteacher_id');
        
        // If no teacher is logged in, redirect to login form
        if (!$classteacherId) {
            return $this->redirectToRoute('classteacher_login_form');
        }

        // Fetch teacher entity based on the ID stored in session
        $teacher = $this->entityManager->getRepository(Teacher::class)->find($classteacherId);

        // Render the dashboard with teacher and classrooms information
        return $this->render('classteacher/dashboard.html.twig', [
            'teacher' => $teacher,
            'classrooms' => $teacher->getClassroom(),
        ]);
    }


//Classteacher logout logic ----------------------------------------------------------------

    #[Route('/classteacher/logout', name: 'classteacher_logout')]
    public function logout(): void
    {
        // Symfony will handle session destruction
    }




}
