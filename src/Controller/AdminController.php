<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\CreateteacherType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Admin;
use App\Form\CreateAdminType;
use App\Repository\AdminRepository;
use App\Entity\Student;
use App\Form\CreateStudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{

//Admin Login Logic --------------------------------------------------------------------------

    /**
 * @Route("/admin", name="admin_login_form")
 */
public function loginForm(): Response
{
    return $this->render('admin/index.html.twig');
}




/**
 * @Route("/admin/authenticate", name="admin_authenticate", methods={"POST"})
 */
public function authenticate(Request $request, AdminRepository $adminRepository, UserPasswordHasherInterface $passwordHasher): Response
{
    $username = $request->request->get('_username');
    $password = $request->request->get('_password');
    $csrfToken = $request->request->get('_csrf_token'); // Get the CSRF token

    // Validate CSRF token
    if (!$this->isCsrfTokenValid('authorize', $csrfToken)) {
        $this->addFlash('error', 'Invalid CSRF token');
        return $this->redirectToRoute('admin_login_form');
    }

    // Find the admin by username
    $admin = $adminRepository->findOneBy(['username' => $username]);

    // Check if admin exists and the password is valid
    if ($admin && $passwordHasher->isPasswordValid($admin, $password)) {
        // Redirect to the admin dashboard after successful login
        return $this->redirectToRoute('admin_dashboard');
    } else {
        // Flash error and redirect back to login form
        $this->addFlash('error', 'Invalid username or password');
        return $this->redirectToRoute('admin_login_form');
    }
}

//Admin Dashboard Logic --------------------------------------------------------------------------



/**
 * @Route("/admin/dashboard", name="admin_dashboard")
 * @IsGranted('ROLE_ADMIN')
 */
public function dashboard(): Response
{
    $admin = $this->getUser();

    if (!$admin instanceof Admin) {
        throw $this->createAccessDeniedException('Access denied.');
    }

    return $this->render('admin/dashboard.html.twig', [
        'name' => $admin->getname(),
        'role' => $admin->getRoles()[0] // Assuming 'ROLE_ADMIN' is present
    ]);
}



//Admin Logout Logic ------------------------------------------------------------------------


/**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout(): void
    {
        // Symfony handles the logout automatically, so this method can be blank.
        // This method will never be executed because Symfony will intercept this route and handle the logout for you.
        throw new \Exception('This method should not be reached!');
    }





//Admin create Logic --------------------------------------------------------------------------

 /**
     * @Route("/admin/create-teacher", name="admin_create_teacher")
     */
    public function createTeacher(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $teacher = new Teacher();
        $form = $this->createForm(CreateteacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set username to be the same as firstname
            $teacher->setUsername($teacher->getFirstname());

            // Set the password to be the same as lastname, but hashed
            $hashedPassword = $passwordHasher->hashPassword($teacher, $teacher->getLastname());
            $teacher->setPassword($hashedPassword);

             // Set the default role to "ROLE_TEACHER"
             $teacher->setRoles(['ROLE_TEACHER']);

            // Save the teacher entity to the database
            $entityManager->persist($teacher);
            $entityManager->flush();

            // Add a success flash message or redirect as needed
            $this->addFlash('success', 'Teacher created successfully.');
            return $this->redirectToRoute('admin_create_teacher');
        }

        return $this->render('admin/createteacher.html.twig', [
            'form' => $form->createView(),
        ]);
    }







//Admin Creation Logic here --------------------------------------------------------------------

   /**
 * @Route("/admin/create-admin", name="admin_create_admin")
 */
public function createAdmin(
    Request $request, 
    EntityManagerInterface $entityManager, 
    UserPasswordHasherInterface $passwordHasher
    ): Response
{
    // Create a new Admin entity
    $admin = new Admin();

    // Create the form for admin creation
    $form = $this->createForm(CreateAdminType::class, $admin);
    
    // Handle form submission
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Hash the password
        $hashedPassword = $passwordHasher->hashPassword($admin, $admin->getPassword());
        $admin->setPassword($hashedPassword);

        // Set the default role to "ROLE_ADMIN"
        $admin->setRoles(['ROLE_ADMIN']);
        
        // Persist the admin entity to the database
        $entityManager->persist($admin);
        $entityManager->flush();

        // Add a flash message for success
        $this->addFlash('success', 'Admin created successfully!');

        // Redirect to the admin dashboard
        return $this->redirectToRoute('admin_create_admin');
    }

    // Render the form view
    return $this->render('admin/createadmin.html.twig', [
        'form' => $form->createView(),
    ]);
}







/**
     * @Route("/admin/create-student", name="admin_create_student")
     */
    public function createStudent(Request $request, 
    EntityManagerInterface $entityManager): Response
    {
        // Create a new student instance
        $student = new Student();

        // Create the form
        $form = $this->createForm(CreateStudentType::class, $student);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Ensure that the student has the role ROLE_STUDENT
            $student->setRoles(['ROLE_STUDENT']);
 
            // Save the student entity
            $entityManager->persist($student);
            $entityManager->flush();

            // Add a success flash message and redirect to the desired page
            $this->addFlash('success', 'Student created successfully!');

            return $this->redirectToRoute('admin_create_student'); // Redirect list page
        }

        // Render the form
        return $this->render('admin/createstudent.html.twig', [
            'form' => $form->createView(),
        ]);
    }




// Admin Create  logic Ends -----------------------------------------------------------------------------------


}