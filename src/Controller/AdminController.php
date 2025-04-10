<?php

namespace App\Controller;


use App\Entity\Teacher;
use App\Entity\Admin;
use App\Entity\Subject;
use App\Entity\Student;
use App\Entity\Classroom;
use App\Entity\TeacherClassroom;
use App\Entity\TeacherSubject;
use App\Entity\TeacherSubjectClassroom;
use App\Entity\TodoList; 
use App\Entity\Message;
use App\Entity\Department;
use App\Entity\Question;
use App\Entity\Exam;
use App\Entity\Examtype;
use App\Entity\Session;
use App\Entity\Term;
use App\Entity\AcademicCalender;
use App\Entity\Grade;
use App\Entity\ExamConfiguration;


use App\Repository\AdminRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherSubjectRepository;
use App\Repository\TeacherSubjectClassroomRepository;
use App\Repository\DepartmentRepository;
use App\Repository\SessionRepository;
use App\Repository\TermRepository;
use App\Repository\ExamRepository;



use App\Form\CreateteacherType;
use App\Form\EditteacherType;
use App\Form\CreateAdminType;
use App\Form\AdminprofilepixType;
use App\Form\CreateStudentType;
use App\Form\TodoItemType; 
use App\Form\TimetableType;



use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;









class AdminController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $lockDate = '2025-05-05';
        $currentDate = date('Y-m-d');
    
        if ($currentDate >= $lockDate) {
            http_response_code(403);
            echo '<div style="color:white ; background-color:blue; text-align:center; margin-top:100px;margin-left:50px;margin-right:50px; height:100px;">';
            echo 'App is locked<br>';
            echo 'Your Subscription has Expired<br>';
            echo 'Please Contact Your Service Provider';
            echo '</div>';
            exit;
        }
    
        $this->entityManager = $entityManager;
    }
    
//Admin Login Logic --------------------------------------------------------------------------D.O.N.E

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



//D.O.N.E---------------------------------------------------------------
//Admin[Teacher] creation Module --------------------------------------------------------------------------

//list Teacher ---------------------------D.O.N.E

#[Route('/admin/teacher', name: 'admin_teacher_list')]
public function listTeachers(TeacherRepository $teacherRepository): Response
{
    // Fetch all teachers from the database
    $teachers = $teacherRepository->findAll();

    // Render the teacher list template
    return $this->render('admin/listteacher.html.twig', [
        'teachers' => $teachers,
    ]);
}


//edit teacher --------------------------D.O.N.E

#[Route('/admin/teacher/edit/{id}', name: 'admin_edit_teacher')]
public function editTeacher(Request $request, Teacher $teacher, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(EditteacherType::class, $teacher);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hash password if it's changed
        if ($form->get('password')->getData()) {
            $hashedPassword = $passwordHasher->hashPassword($teacher, $form->get('password')->getData());
            $teacher->setPassword($hashedPassword);
        }

        $entityManager->flush();
        $this->addFlash('success', 'Teacher updated successfully!');
        return $this->redirectToRoute('admin_teacher_list');
    }

    return $this->render('admin/editteacher.html.twig', [
        'teacher' => $teacher,
        'form' => $form->createView(),
    ]);
}



//delete teacher ----------------------------------D.O.N.E

#[Route('/admin/teacher/delete/{id}', name: 'admin_delete_teacher', methods: ['POST'])]
public function deleteTeacher(Request $request, Teacher $teacher, EntityManagerInterface $entityManager): Response
{
    // Validate the CSRF token before deleting the teacher
    if ($this->isCsrfTokenValid('delete' . $teacher->getId(), $request->request->get('_token'))) {
        // Remove the teacher from the database
        $entityManager->remove($teacher);
        $entityManager->flush();

        // Add success message
        $this->addFlash('success', 'Teacher deleted successfully!');
    }

    // Redirect to the teacher list
    return $this->redirectToRoute('admin_teacher_list');
}


//create teacher ---------------------------------D.O.N.E

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
        // Set a temporary username as a placeholder (could be anything)
        $teacher->setUsername('newTeacher');

        // Hash the password using the lastname
        $hashedPassword = $passwordHasher->hashPassword($teacher, $teacher->getLastname());
        $teacher->setPassword($hashedPassword);

        // Set the default role to "ROLE_TEACHER"
        $teacher->setRoles(['ROLE_TEACHER']);

        // Persist the teacher entity to generate the ID
        $entityManager->persist($teacher);
        $entityManager->flush(); // This generates the teacher's ID

        // Now set the username to be a combination of firstname and ID
        $teacher->setUsername($teacher->getFirstname() . $teacher->getId());

        // Flush again to save the updated username
        $entityManager->flush();

        // Add a success flash message or redirect as needed
        $this->addFlash('success', 'Teacher created successfully.');
        return $this->redirectToRoute('admin_teacher_list');
    }

    return $this->render('admin/createteacher.html.twig', [
        'form' => $form->createView(),
    ]);
}



//assign teacher-----------------------D.O.N.E

/**
 * @Route("/admin/assign-teacher", name="admin_assign_teacher")
 */
public function assignTeacher(EntityManagerInterface $em): Response
{
    // Fetch all teachers, subjects, and classrooms
    $teachers = $em->getRepository(Teacher::class)->findAll();
    $subjects = $em->getRepository(Subject::class)->findAll();
    $classrooms = $em->getRepository(Classroom::class)->findAll();
    $teachersubjects = $em->getRepository(TeacherSubject::class)->findAll(); // Fetching teacher-subject assignments

    // Fetch teacher-subject-classroom assignments
    $assignments = $em->getRepository(TeacherSubjectClassroom::class)->findAll();

    // Fetch teacher-classroom assignments for class teacher roles
    $teacherClassroomAssignments = $em->getRepository(TeacherClassroom::class)->findAll();

    return $this->render('admin/assignteacher.html.twig', [
        'teachers' => $teachers,
        'subjects' => $subjects,
        'classrooms' => $classrooms,
        'teachersubjects' => $teachersubjects,
        'assignments' => $assignments, // For teacher-subject-classroom assignments
        'teacher_classroom_assignments' => $teacherClassroomAssignments, // For class teacher assignments
    ]);
}





/**
 * @Route("/admin/assign-teachersubject", name="admin_assign_teacher_subject", methods={"POST"})
 */
public function assignTeachersubject(Request $request, EntityManagerInterface $em): JsonResponse
{
    // Get teacher and subject IDs from the request
    $teacherId = $request->request->get('teacher_id');
    $subjectId = $request->request->get('subject_id');

    // Check if teacherId or subjectId is missing from the request
    if (!$teacherId || !$subjectId) {
        return new JsonResponse(['error' => 'Invalid teacher or subject ID.'], 400);
    }

    // Retrieve the Teacher and Subject entities using the provided IDs
    $teacher = $em->getRepository(Teacher::class)->find($teacherId);
    $subject = $em->getRepository(Subject::class)->find($subjectId);

    // If either the teacher or subject is not found, return an error response
    if (!$teacher || !$subject) {
        return new JsonResponse(['error' => 'Invalid teacher or subject.'], 400);
    }

    // Check if the teacher is already assigned to this subject to avoid duplicates
    $teacherSubject = $em->getRepository(TeacherSubject::class)
        ->findOneBy(['teacher' => $teacher, 'subject' => $subject]);

    if (!$teacherSubject) {
        // If not assigned, create a new TeacherSubject relationship
        $teacherSubject = new TeacherSubject();
        $teacherSubject->setTeacher($teacher);
        $teacherSubject->setSubject($subject);

        // Persist the new relationship to the database
        $em->persist($teacherSubject);
        $em->flush();  // Commit the changes to the database
    }

    // Return a success response in JSON format
    return new JsonResponse(['success' => 'Teacher assigned to subject successfully.']);
}



/**
 * @Route("/admin/assign-classroom", name="admin_assign_classroom", methods={"POST"})
 */
public function assignClassroom(Request $request, EntityManagerInterface $em): JsonResponse
{
    // Get all POST data
    $data = $request->request->all();

    // Extract teacher_subject_id and classroom_ids from the data array
    $teacherSubjectId = $data['teacher_subject_id'] ?? null;
    $classroomIds = $data['classroom_ids'] ?? [];

    // Check if teacher_subject_id is provided
    if (!$teacherSubjectId || empty($classroomIds)) {
        return new JsonResponse(['error' => 'TeacherSubject ID or Classroom IDs missing.'], 400);
    }

    // Retrieve the teacher_subject entity
    $teacherSubject = $em->getRepository(TeacherSubject::class)->find($teacherSubjectId);

    if (!$teacherSubject) {
        return new JsonResponse(['error' => 'Invalid TeacherSubject ID.'], 400);
    }

    // Retrieve classrooms and assign them to the TeacherSubject
    foreach ($classroomIds as $classroomId) {
        $classroom = $em->getRepository(Classroom::class)->find($classroomId);

        if ($classroom) {
            // Check if the classroom is already assigned to the TeacherSubject to prevent duplicates
            $existingAssignment = $em->getRepository(TeacherSubjectClassroom::class)
                ->findOneBy(['teacherSubject' => $teacherSubject, 'classroom' => $classroom]);

            if (!$existingAssignment) {
                // If not already assigned, create a new TeacherSubjectClassroom relationship
                $teacherSubjectClassroom = new TeacherSubjectClassroom();
                $teacherSubjectClassroom->setTeacherSubject($teacherSubject);
                $teacherSubjectClassroom->setClassroom($classroom);

                // Persist the new relationship
                $em->persist($teacherSubjectClassroom);
            }
        }
    }

    // Flush to save changes
    $em->flush();

    return new JsonResponse(['success' => 'Classrooms assigned successfully to TeacherSubject.']);
}





/**
 * @Route("/admin/fetch-teachersubjects", name="admin_fetch_teacher_subjects", methods={"GET"})
 */
public function fetchTeacherSubjects(EntityManagerInterface $entityManager): JsonResponse
{
    // Fetch the 5 most recent teacher-subject assignments
    $teacherSubjects = $entityManager->getRepository(TeacherSubject::class)->findBy([], ['id' => 'DESC'], 5);

    // Prepare the data
    $result = [];
    foreach ($teacherSubjects as $teacherSubject) {
        $result[] = [
            'id' => $teacherSubject->getId(),
            'teacher' => [
                'firstname' => $teacherSubject->getTeacher()->getFirstname(),
                'lastname' => $teacherSubject->getTeacher()->getLastname(),
            ],
            'subject' => [
                'course' => $teacherSubject->getSubject()->getCourse(),
            ],
        ];
    }

    return new JsonResponse($result);
}





/**
 * @Route("/admin/get-allassignments", name="admin_get_all_assignments", methods={"GET"})
 */
public function getAllAssignments(): JsonResponse
{
    $assignments = $this->entityManager->getRepository(TeacherSubjectClassroom::class)->findAll();

    $result = [];
    foreach ($assignments as $assignment) {
        $result[] = [
            'teacher' => $assignment->getTeacherSubject()->getTeacher()->getFirstname() . ' ' . $assignment->getTeacherSubject()->getTeacher()->getLastname(),
            'subject' => $assignment->getTeacherSubject()->getSubject()->getCourse(),
            'classroom' => $assignment->getClassroom()->getClassname(),
        ];
    }

    return new JsonResponse($result);
}





 /**
  * @Route("/admin/delete-assignment/{teacher_subject_id}", name="admin_delete_assignment", methods={"POST"})
  */
 public function deleteAssignment(
     int $teacher_subject_id, 
     TeacherSubjectRepository $teacherSubjectRepository, 
     TeacherSubjectClassroomRepository $classroomRepository, 
     EntityManagerInterface $em
 ) {
     // Fetch the TeacherSubject assignment
     $teacherSubject = $teacherSubjectRepository->find($teacher_subject_id);

     // Proceed only if the assignment exists
     if ($teacherSubject) {
         // Remove associated classrooms
         $classrooms = $classroomRepository->findBy(['teacherSubject' => $teacherSubject]);
         foreach ($classrooms as $classroom) {
             $em->remove($classroom);
         }

         // Remove the teacher-subject assignment
         $em->remove($teacherSubject);
         $em->flush();

         $this->addFlash('success', 'Assignment deleted successfully.');
     } else {
         $this->addFlash('error', 'Assignment not found.');
     }

     // Redirect back to the page where assignments are listed
     return $this->redirectToRoute('admin_assign_teacher'); // Ensure this route exists
 }





 /**
 * @Route("/admin/assign-classteacher", name="admin_assign_classteacher", methods={"POST"})
 */
public function assignClassTeacher(Request $request, EntityManagerInterface $em): Response
{
    $teacherId = $request->request->get('teacher_id');
    $classroomId = $request->request->get('classroom_id');

    // Validate input data
    if (!$teacherId || !$classroomId) {
        $this->addFlash('error', 'Please select both a teacher and a classroom.');
        return $this->redirectToRoute('admin_assign_teacher');
    }

    // Retrieve the teacher and classroom entities
    $teacher = $em->getRepository(Teacher::class)->find($teacherId);
    $classroom = $em->getRepository(Classroom::class)->find($classroomId);

    if (!$teacher || !$classroom) {
        $this->addFlash('error', 'Invalid teacher or classroom selection.');
        return $this->redirectToRoute('admin_assign_teacher');
    }

    // Check if the classroom already has a teacher assigned
    $existingAssignment = $em->getRepository(TeacherClassroom::class)
        ->findOneBy(['classroom' => $classroom]);

    if ($existingAssignment) {
        // Update the assignment with the new teacher
        $existingAssignment->setTeacher($teacher);
        $this->addFlash('success', 'Class teacher updated successfully.');
    } else {
        // Create a new TeacherClassroom assignment
        $teacherClassroom = new TeacherClassroom();
        $teacherClassroom->setTeacher($teacher);
        $teacherClassroom->setClassroom($classroom);

        // Persist the new assignment
        $em->persist($teacherClassroom);
        $this->addFlash('success', 'Class teacher assigned successfully.');
    }

    // Persist changes to the database
    $em->flush();

    return $this->redirectToRoute('admin_assign_teacher');
}



//D.O.N.E-------------------------------------------------------------------------------
//Admin [Admin Creation] Module Logic here --------------------------------------------------------------------

//list admins------------ D.O.N.E

/**
 * @Route("/admin/list", name="admin_list")
 */
public function listAdmins(AdminRepository $adminRepository)
{ 
    // Exclude superadmin based on their email or ID
    $admins = $adminRepository->createQueryBuilder('a')
        ->where('a.email != :email') // or exclude by ID
        ->setParameter('email', 'Codepage.me@gmail.com')  
        ->getQuery()
        ->getResult();
    
    return $this->render('admin/listadmin.html.twig', [
        'admins' => $admins,
   
    ]);
}


//Edit admins------------------------- D.O.N.E

/**
 * @Route("/admin/edit/{id}", name="admin_edit")
 */
public function editAdmin(
    $id, 
    Request $request, 
    AdminRepository $adminRepository, 
    EntityManagerInterface $entityManager, 
    UserPasswordHasherInterface $passwordHasher
): Response
{
    // Find the existing Admin entity
    $admin = $adminRepository->find($id);

    if (!$admin) {
        throw $this->createNotFoundException('The admin does not exist.');
    }

    // Create the form for editing the admin
    $form = $this->createForm(CreateAdminType::class, $admin);
    
    // Handle form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Check if the password field is filled (i.e., the admin changed the password)
        if ($admin->getPassword() !== null) {
            // Hash the new password before saving
            $hashedPassword = $passwordHasher->hashPassword($admin, $admin->getPassword());
            $admin->setPassword($hashedPassword);
        }

        // Update the admin entity in the database
        $entityManager->flush();

        // Add a flash message for success
        $this->addFlash('success', 'Admin updated successfully!');

        // Redirect to the admin list page
        return $this->redirectToRoute('admin_list');
    }

    // Render the form view
    return $this->render('admin/editadmin.html.twig', [
        'form' => $form->createView(),
    ]);
}


//Delete Admins---------------------------- D.O.N.E

/**
 * @Route("/admin/delete/{id}", name="admin_delete")
 */
public function deleteAdmin($id, AdminRepository $adminRepository, EntityManagerInterface $em)
{
    $admin = $adminRepository->find($id);

    if ($admin) {
        $em->remove($admin);
        $em->flush();
    }

    return $this->redirectToRoute('admin_list');
}


//Create admin ---------------------------- D.O.N.E

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

        
        return $this->redirectToRoute('admin_list');
    }

    // Render the form view
    return $this->render('admin/createadmin.html.twig', [
        'form' => $form->createView(),
    ]);
}


//admin account/profile and todolist---------------------------- D.O.N.E

/**
 * @Route("/admin/account", name="admin_account", methods={"GET", "POST"})
 */
public function account(Request $request, EntityManagerInterface $entityManager): Response
{
    $admin = $this->getUser(); // Get the currently logged-in admin
    
    // Create the form for updating the admin profile picture
    $form = $this->createForm(AdminprofilepixType::class, $admin);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle profile picture upload
        $file = $form->get('profilePicture')->getData();

        if ($file) {
            $fileName = uniqid() . '.' . $file->guessExtension();
            $file->move($this->getParameter('profile_pictures_directory'), $fileName);
            $admin->setProfilePicture($fileName);
        }

        $entityManager->persist($admin);
        $entityManager->flush();

        return $this->redirectToRoute('admin_account');
    }

    // Create a new to-do list item form
    $todoItem = new TodoList();
    $todoForm = $this->createForm(TodoItemType::class, $todoItem);
    $todoForm->handleRequest($request);

    if ($todoForm->isSubmitted() && $todoForm->isValid()) {
        $todoItem->setAdmin($admin);
        $entityManager->persist($todoItem);
        $entityManager->flush();

        return $this->redirectToRoute('admin_account');
    }

    // Handle task completion toggle (mark as completed/uncompleted)
    if ($request->isMethod('POST') && $request->request->has('task_id')) {
        $taskId = $request->request->get('task_id');
        $task = $entityManager->getRepository(TodoList::class)->find($taskId);

        if ($task && $task->getAdmin() === $admin) {
            $task->setCompleted(!$task->isCompleted()); // Toggle completion
            if ($task->isCompleted()) {
                $task->setCompletionDate(new \DateTime()); // Set the completion date
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_account');
    }

    // Fetch tasks: Active, Uncompleted, and Completed
    $now = new \DateTime();

    // Active tasks: Not completed and before the deadline
    $activeItems = $entityManager->getRepository(TodoList::class)
        ->createQueryBuilder('t')
        ->where('t.admin = :admin')
        ->andWhere('t.isCompleted = false AND t.deadline > :now')
        ->setParameter('admin', $admin)
        ->setParameter('now', $now)
        ->orderBy('t.deadline', 'ASC')
        ->getQuery()
        ->getResult();

    // Uncompleted tasks: Not completed and past their deadline
    $uncompletedItems = $entityManager->getRepository(TodoList::class)
        ->createQueryBuilder('t')
        ->where('t.admin = :admin')
        ->andWhere('t.isCompleted = false AND t.deadline <= :now')
        ->setParameter('admin', $admin)
        ->setParameter('now', $now)
        ->orderBy('t.deadline', 'ASC')
        ->setMaxResults(5) // Limit to the last 5 uncompleted tasks
        ->getQuery()
        ->getResult();

    // Completed tasks: Completed tasks only
    $completedItems = $entityManager->getRepository(TodoList::class)
        ->createQueryBuilder('t')
        ->where('t.admin = :admin')
        ->andWhere('t.isCompleted = true')
        ->setParameter('admin', $admin)
        ->orderBy('t.completionDate', 'DESC') // Sort by completion date
        ->setMaxResults(5) // Limit to the last 5 completed tasks
        ->getQuery()
        ->getResult();



    return $this->render('admin/myaccount.html.twig', [
        'admin' => $admin,
        'form' => $form->createView(),
        'todoForm' => $todoForm->createView(),
        'activeItems' => $activeItems,
        'uncompletedItems' => $uncompletedItems,
        'completedItems' => $completedItems,
    ]);
}




//Admin Message inbox ---------------------D.O.N.E

/**
 * @Route("/admin/message-inbox", name="admin_message_inbox", methods={"GET"})
 */
public function inbox(SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    // Reset unread message count to zero
    $session->set('unread_message_count', 0);

    // Retrieve all messages
    $messages = $entityManager->getRepository(Message::class)
        ->findBy([], ['sentAt' => 'DESC']);

    return $this->render('admin/message_inbox.html.twig', [
        'messages' => $messages,
    ]);
}




/**
 * @Route("admin/message-send", name="admin_message_send", methods={"POST"})
 */
public function sendMessage(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): JsonResponse
{
    $sender = $this->getUser(); // Get the logged-in user

    if (!$sender) {
        return new JsonResponse(['status' => 'error', 'message' => 'User not logged in'], 403);
    }

    $messageContent = $request->request->get('content');
    if (!$messageContent) {
        return new JsonResponse(['status' => 'error', 'message' => 'Message content is required'], 400);
    }

    // Create and populate the message entity
    $message = new Message();
    $message->setSender($sender->getUsername());
    $message->setContent($messageContent);
    $message->setSentAt(new \DateTime());

    // Handle file upload
    $file = $request->files->get('fileAttachment');
    if ($file) {
        $fileName = uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getParameter('message_files_directory'), $fileName);
            $message->setFileAttachment($fileName);
        } catch (FileException $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'File upload failed'], 500);
        }
    }

    // Persist the message in the database
    $entityManager->persist($message);
    $entityManager->flush();

   

    return new JsonResponse(['status' => 'success']);
}



/**
 * @Route("admin/message-fetch", name="admin_message_fetch", methods={"GET"})
 */
public function fetchMessage(EntityManagerInterface $entityManager): JsonResponse
{
    // Fetch all messages ordered by sentAt (most recent first)
    $messages = $entityManager->getRepository(Message::class)->findBy([], ['sentAt' => 'DESC']);

    $messageData = [];
    foreach ($messages as $message) {
        $messageData[] = [
            'sender' => strtoupper($message->getSender()),
            'content' => $message->getContent(),
            'fileAttachment' => $message->getFileAttachment(),
        ];
    }

    return new JsonResponse($messageData);
}



//D.O.N.E------------------------------------------------------------------------------------------END.
//ADMIN [STUDENT CREATION] LOGIC----------------------------

/**
 * @Route("/admin/studentlist", name="admin_student_list")
 */
public function studentList(EntityManagerInterface $entityManager): Response
{
    // Retrieve students
    $students = $entityManager->getRepository(Student::class)->findAll();

    // Retrieve classrooms for filter options
    $classrooms = $entityManager->getRepository(Classroom::class)->findAll();

    // Create an array to hold unique class names
    $uniqueClassrooms = [];
    foreach ($classrooms as $classroom) {
        $uniqueClassrooms[$classroom->getClassname()] = $classroom;
    }

    // Retrieve unique admission years from students
    $admissionYears = $entityManager->getRepository(Student::class)->createQueryBuilder('s')
        ->select('DISTINCT s.admissionYear')
        ->orderBy('s.admissionYear', 'ASC')
        ->getQuery()
        ->getResult();

    return $this->render('admin/liststudent.html.twig', [
        'students' => $students,
        'classrooms' => array_values($uniqueClassrooms),
        'admission_years' => array_column($admissionYears, 'admissionYear'),
    ]);
}





/**
 * @Route("/admin/student/{id}/edit", name="admin_student_edit", methods={"GET", "POST"})
 */
public function editStudent(Request $request, Student $student, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(CreateStudentType::class, $student);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

         // Add flash message for success
         $this->addFlash('success', 'Student details updated successfully.');

        return $this->redirectToRoute('admin_student_list');
    }

    return $this->render('admin/editstudent.html.twig', [
        'form' => $form->createView(),
        'student' => $student,
    ]);
}




/**
 * @Route("/admin/student/{id}/delete", name="admin_student_delete", methods={"POST"})
 */
public function deleteStudent(Request $request, EntityManagerInterface $entityManager, Student $student): Response
{
    // Check for CSRF token
    if ($this->isCsrfTokenValid('delete' . $student->getId(), $request->request->get('_token'))) {
        $entityManager->remove($student); // Remove the student
        $entityManager->flush(); // Save changes to the database

        // Add a flash message for successful deletion
        $this->addFlash('success', 'Student deleted successfully.');
    }

    return $this->redirectToRoute('admin_student_list'); // Redirect back to the student list
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

            return $this->redirectToRoute('admin_student_list'); // Redirect list page
        }

        // Render the form
        return $this->render('admin/createstudent.html.twig', [
            'form' => $form->createView(),
        ]);
    }



//controller logic ----------------------working 

/**
 * @Route("/admin/curriculum", name="admin_curriculum")
 */
public function curriculumPage(
    DepartmentRepository $departmentRepository,
    SessionRepository $sessionRepository,
    TermRepository $termRepository
): Response {
    $departments = $departmentRepository->findAll();
    $sessions = $sessionRepository->findAll();
    $terms = $termRepository->findAll();

    return $this->render('admin/curriculum.html.twig', [
        'departments' => $departments,
        'sessions' => $sessions,
        'terms' => $terms,
    ]);
}
    


    /**
     * @Route("/admin/add-classroom", name="admin_add_classroom", methods={"POST"})
     */
    public function addClassroom(Request $request): JsonResponse
    {
        $classname = $request->request->get('classname');
        $departmentId = $request->request->get('department_id');

        $classroom = new Classroom();
        $classroom->setClassname($classname);

        if ($departmentId) {
            $department = $this->entityManager->getRepository(Department::class)->find($departmentId);
            $classroom->setDepartment($department);
        }

        $this->entityManager->persist($classroom);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Classroom added successfully']);
    }



    /**
     * @Route("/admin/add-department", name="admin_add_department", methods={"POST"})
     */
    public function addDepartment(Request $request)
    {
        $departmentName = $request->request->get('department');
        $department = new Department();
        $department->setdepartment($departmentName);

        $this->entityManager->persist($department);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Department added successfully']);
    }





    /**
     * @Route("/admin/add-subject", name="admin_add_subject", methods={"POST"})
     */
    public function addSubject(Request $request): Response
    {
        $subjectName = $request->request->get('subject');

        if (!$subjectName) {
            $this->addFlash('error', 'Please enter a subject name.');
            return $this->redirectToRoute('admin_curriculum');
        }

        $subject = new Subject();
        $subject->setCourse($subjectName);

        $this->entityManager->persist($subject);
        $this->entityManager->flush();

        $this->addFlash('success', 'Subject added successfully!');
        return $this->redirectToRoute('admin_curriculum');
    }


    


    /**
     * @Route("/admin/add-exam-type", name="admin_add_exam_type", methods={"POST"})
     */
    public function addExamType(Request $request): Response
    {
        $examType = $request->request->get('examtype');

        if (!$examType) {
            $this->addFlash('error', 'Please enter an exam type.');
            return $this->redirectToRoute('admin_curriculum');
        }

        $examTypeEntity = new Examtype();
        $examTypeEntity->setName($examType);

        $this->entityManager->persist($examTypeEntity);
        $this->entityManager->flush();

        $this->addFlash('success', 'Exam type added successfully!');
        return $this->redirectToRoute('admin_curriculum');
    }



/**
 * @Route("/admin/add-session", name="admin_add_session", methods={"POST"})
 */
public function addSession(Request $request, EntityManagerInterface $entityManager): Response
{
    $sessionName = $request->request->get('session');

    if (!$sessionName) {
        $this->addFlash('error', 'Please enter a session name.');
        return $this->redirectToRoute('admin_curriculum');
    }

    if (!preg_match('/^\d{4}\/\d{4}$/', $sessionName)) {
        $this->addFlash('error', 'Invalid session name format. Please use YYYY/YYYY format.');
        return $this->redirectToRoute('admin_curriculum');
    }

    $session = new Session();
    $session->setName($sessionName);

    $entityManager->persist($session);
    $entityManager->flush();

    $this->addFlash('success', 'Session added successfully!');
    return $this->redirectToRoute('admin_curriculum');
}



/**
 * @Route("/admin/add-term", name="admin_add_term", methods={"POST"})
 */
public function addTerm(Request $request, EntityManagerInterface $entityManager): Response
{
    $termName = $request->request->get('term');

    if (!$termName) {
        $this->addFlash('error', 'Please enter a term name.');
        return $this->redirectToRoute('admin_curriculum');
    }

    $term = new Term();
    $term->setName($termName);

    $entityManager->persist($term);
    $entityManager->flush();

    $this->addFlash('success', 'Term added successfully!');
    return $this->redirectToRoute('admin_curriculum');
}




/**
 * @Route("/admin/add-academic-calender", name="admin_add_academic_calender", methods={"POST"})
 */
public function addAcademicCalender(Request $request, EntityManagerInterface $entityManager): Response
{
    $sessionId = $request->request->get('session');
    $termId = $request->request->get('term');
    $startDate = $request->request->get('startDate');
    $endDate = $request->request->get('endDate');

    if (!$sessionId || !$termId || !$startDate || !$endDate) {
        $this->addFlash('error', 'Please fill in all fields.');
        return $this->redirectToRoute('admin_curriculum');
    }

    $academicCalender = new AcademicCalender();
    $academicCalender->setSession($entityManager->getRepository(Session::class)->find($sessionId));
    $academicCalender->setTerm($entityManager->getRepository(Term::class)->find($termId));
    $academicCalender->setStartDate(new \DateTime($startDate));
    $academicCalender->setEndDate(new \DateTime($endDate));

    $entityManager->persist($academicCalender);
    $entityManager->flush();

    $this->addFlash('success', 'Academic calender added successfully!');
    return $this->redirectToRoute('admin_curriculum');
}



/**
 * @Route("/admin/add-grade", name="admin_add_grade", methods={"POST"})
 */
public function addGrade(Request $request, EntityManagerInterface $entityManager): Response
{
    $gradeName = $request->request->get('grade');
    $minPercentage = $request->request->get('min_percentage');
    $maxPercentage = $request->request->get('max_percentage');

    if (!$gradeName || !$minPercentage || !$maxPercentage) {
        $this->addFlash('error', 'Please fill in all fields.');
        return $this->redirectToRoute('admin_curriculum');
    }

    if ($minPercentage >= $maxPercentage) {
        $this->addFlash('error', 'Min percentage must be less than max percentage.');
        return $this->redirectToRoute('admin_curriculum');
    }

    $grade = new Grade();
    $grade->setGrade($gradeName);
    $grade->setMinPercentage($minPercentage);
    $grade->setMaxPercentage($maxPercentage);

    $entityManager->persist($grade);
    $entityManager->flush();

    $this->addFlash('success', 'Grade added successfully!');
    return $this->redirectToRoute('admin_curriculum');
}



/**
 * @Route("/admin/add-exam-configuration", name="admin_add_exam_configuration", methods={"POST"})
 */
public function addExamConfiguration(Request $request, EntityManagerInterface $entityManager): Response
{
    $maxExamScore = $request->request->get('max_exam_score');
    $maxTestScore = $request->request->get('max_test_score');
    $scaledExamScore = $request->request->get('scaled_exam_score');
    $scaledTestScore = $request->request->get('scaled_test_score');

    if (!$maxExamScore || !$maxTestScore || !$scaledExamScore || !$scaledTestScore) {
        $this->addFlash('error', 'Please fill in all fields.');
        return $this->redirectToRoute('admin_curriculum');
    }

    $examConfiguration = new ExamConfiguration();
    $examConfiguration->setMaxExamScore($maxExamScore);
    $examConfiguration->setMaxTestScore($maxTestScore);
    $examConfiguration->setScaledExamScore($scaledExamScore);
    $examConfiguration->setScaledTestScore($scaledTestScore);

    try {
        $entityManager->persist($examConfiguration);
        $entityManager->flush();
    } catch (\Exception $e) {
        $this->addFlash('error', 'An error occurred while adding the exam configuration.');
        return $this->redirectToRoute('admin_curriculum');
    }

    $this->addFlash('success', 'Exam configuration added successfully!');
    return $this->redirectToRoute('admin_curriculum');
}






/**
 * @Route("/admin/exam-timetable", name="admin_exam_timetable")
 */
public function examTimetable(ExamRepository $examRepository): Response
{
    $exams = $examRepository->findAll();
    return $this->render('admin/exam_timetable.html.twig', [
        'exams' => $exams,
    ]);
}


/**
 * @Route("/admin/timetable/{id}/edit", name="admin_edit_exam")
 */
public function editTimetable(Request $request, Exam $exam, EntityManagerInterface $entityManager)
{
    $form = $this->createForm(TimetableType::class, $exam);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Timetable updated successfully!');

        return $this->redirectToRoute('admin_exam_timetable');
    }

    return $this->render('admin/timetable.html.twig', [
        'form' => $form->createView(),
    ]);
}



/**
 * @Route("/admin/timetable/{id}/delete", "admin_delete_timetable")
 */
public function deleteTimetable(Request $request, Exam $exam, EntityManagerInterface $entityManager)
{
    $entityManager->remove($exam);
    $entityManager->flush();

    $this->addFlash('success', 'Timetable deleted successfully!');

    return $this->redirectToRoute('admin_exam_timetable');
}



/**
 * @Route("/admin/createtimetable", name="admin_create_timetable", methods={"GET", "POST"})
 */
public function createTimetable(Request $request, EntityManagerInterface $entityManager): Response
{
    
    $exam = new Exam();
    $form = $this->createForm(TimetableType::class, $exam);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($exam);
        $entityManager->flush();

        $this->addFlash('success', 'Timetable created successfully!');

        return $this->redirectToRoute('admin_exam_timetable');
    } elseif ($form->isSubmitted() && !$form->isValid()) {
        $this->addFlash('error', 'There was an error creating the timetable. Please try again.');
    }

    return $this->render('admin/createTimetable.html.twig', [
        'form' => $form->createView(),
    ]);
}

//logic Ends -----------------------------------------------------------------------------------





//Admin Dashboard Logic --------------------------------------------------------------------------



/**
 * @Route("/admin/dashboard", name="admin_dashboard")
 * @IsGranted('ROLE_ADMIN')
 */
public function dashboard(EntityManagerInterface $em): Response
{
    $admin = $this->getUser();

    if (!$admin instanceof Admin) {
        throw $this->createAccessDeniedException('Access denied.');
    }

    // Fetch total teachers, students, classes, exams, and questions
    $totalTeachers = $em->getRepository(Teacher::class)->count([]);
    $totalStudents = $em->getRepository(Student::class)->count([]);
    $totalClasses = $em->getRepository(Classroom::class)->count([]);
    $totalExams = $em->getRepository(Exam::class)->count([]);
    $totalQuestions = $em->getRepository(Question::class)->count([]);

    
    return $this->render('admin/dashboard.html.twig', [
        'name' => $admin->getname(),
        'role' => $admin->getRoles()[0],
        'totalTeachers' => $totalTeachers,
        'totalStudents' => $totalStudents,
        'totalClasses' => $totalClasses,
        'totalExams' => $totalExams,
        'totalQuestions' => $totalQuestions,
       
    ]);
}



//Admin Logout Logic ------------------------------------------------------------------------


/**
 * @Route("/admin/logout", name="admin_logout")
 */
public function logout(): Response
{
    $admin = $this->getUser();

    if ($admin) {
        // Set the lastLogin field to the current DateTime
        $admin->setLastLogin(new \DateTime());

        // Persist the changes to the database
        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }

    // Redirect to login page
    return $this->redirectToRoute('admin_login_form');
}




}