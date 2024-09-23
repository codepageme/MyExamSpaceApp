<?php

// src/Controller/TeacherController.php
namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Teacher;
use App\Repository\TeacherRepository;

use App\Entity\TeacherNote;
use App\Form\TeacherNoteType;
use App\Repository\TeacherNoteRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Exam;
use App\Form\ExamType;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;



class TeacherController extends AbstractController
{

//Teacher Login Logic --------------------------------------------------------------------------

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
public function authenticate(Request $request, TeacherRepository $teacherRepository, UserPasswordHasherInterface $passwordHasher): Response
{
    $username = $request->request->get('_username');
    $password = $request->request->get('_password');
    $csrfToken = $request->request->get('_csrf_token'); // Get the CSRF token

    // Validate CSRF token
    if (!$this->isCsrfTokenValid('authenticate', $csrfToken)) {
        $this->addFlash('error', 'Invalid CSRF token');
        return $this->redirectToRoute('teacher_login_form');
    }

    $teacher = $teacherRepository->findOneBy(['username' => $username]);

    if ($teacher && $passwordHasher->isPasswordValid($teacher, $password)) {
        // Redirect to the teacher dashboard after successful login
        return $this->redirectToRoute('teacher_dashboard');
    } else {
        // Add flash message and redirect back to the login form on failure
        $this->addFlash('error', 'Invalid username or password');
        return $this->redirectToRoute('teacher_login_form');
    }
}


//Teacher Dashboard Logic --------------------------------------------------------------------------

    #[Route('/teacher/dashboard', name: 'teacher_dashboard')]
    #[IsGranted('ROLE_TEACHER')]
    public function dashboard(): Response
    {
        // Get the currently logged-in teacher
        $teacher = $this->getUser();

        // Ensure that $teacher is indeed an instance of the Teacher entity
        if (!$teacher instanceof Teacher) {
            throw $this->createAccessDeniedException('Access denied. Teacher not found.');
        }

        return $this->render('teacher/dashboard.html.twig', [
            'username' => $teacher->getUsername(),
            'email' => $teacher->getEmail(),
            'role' => $teacher->getRoles()[0] // Assuming 'TEACHER' is always present
        ]);

         
    }

//Teacher Logout Logic --------------------------------------------------------------------------

    /**
     * @Route("/teacher/logout", name="teacher_logout")
     */
    public function logout()
    {
        // This will never be executed because Symfony intercepts this route and handles logout automatically
        throw new \Exception('This should never be reached!');
    }
    



    
//Teacher EDit Logic -------------------------------------------------------------------------
    


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









//Teacher Account Controller / -------------------------------------------- ; Note ; starts here 

#[Route('/teacher/account', name: 'teacher_account')]
public function account(EntityManagerInterface $em, Request $request): Response
{
    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    // Create a new note
    $note = new TeacherNote();
    $form = $this->createForm(TeacherNoteType::class, $note);

    // Handle form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $note->setTeacher($teacher);
        $note->setCreatedat(new \DateTime());
        $note->setUpdatedat(new \DateTime());

        $em->persist($note);
        $em->flush();

        return $this->redirectToRoute('teacher_account');
    }

    // Fetch existing notes
    $notes = $teacher->getTeacherNotes(); // Updated method name

    return $this->render('teacher/account.html.twig', [
        'teacher' => $teacher,
        'form' => $form->createView(),
        'notes' => $notes,
    ]);
}


/**
 * @Route("/teacher/note/{id}/edit", name="edit_note")
 * @ParamConverter("note", class="App\Entity\TeacherNote")
 */
public function editNote(TeacherNote $note, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(TeacherNoteType::class, $note);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('teacher_account');
    }

    return $this->render('teacher/edit_note.html.twig', [
        'form' => $form->createView(),
    ]);
}


/**
 * @Route("/teacher/note/{id}/delete", name="delete_note")
 * @ParamConverter("note", class="App\Entity\TeacherNote")
 */
public function deleteNote(TeacherNote $note, EntityManagerInterface $em): Response
{
    $em->remove($note);
    $em->flush();
    return $this->redirectToRoute('teacher_account');
}

//Teacher Account Controller --------------------------------------------------------------Ends here






//Teacher Exam Controller --------------------------------------------------------------
//preview Exam

#[Route('/teacher/exam', name: 'teacher_exam')]
public function examIndex(EntityManagerInterface $em): Response
{
    // Fetch all exams for the logged-in teacher
    $teacher = $this->getUser(); 

    // Fetch exams linked to this teacher
    $exams = $em->getRepository(Exam::class)->findBy(['teacher' => $teacher]);

    // Render the exam page
    return $this->render('teacher/exam.html.twig', [
        'exams' => $exams,
    ]);
}




//create exam question
#[Route('/teacher/exam/question', name: 'teacher_create_question')]
public function createQuestion(SubjectRepository $subjectRepo)
{
    // Fetch the subjects related to the logged-in teacher (for example purposes, fetching all subjects)
    $teacher = $this->getUser(); // assuming you have a logged-in user/teacher
    $subjects = $subjectRepo->findBy(['teacher' => $teacher]);

    return $this->render('teacher/createquestion.html.twig', [
        'subjects' => $subjects,
    ]);
}




// src/Controller/TeacherController.php

#[Route('/teacher/exams/create', name: 'teacher_create_exam')]
public function createExam(Request $request, EntityManagerInterface $em): Response
{
    $teacher = $this->getUser();
    
    // Create a new Exam
    $exam = new Exam();
    $form = $this->createForm(ExamType::class, $exam);

    // Handle form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $exam->setTeacher($teacher);
        $exam->setCreatedAt(new \DateTime());
        $exam->setUpdatedAt(new \DateTime());

        $em->persist($exam);
        $em->flush();

        return $this->redirectToRoute('teacher_create_exam');
    }

    // Fetch all exams created by the teacher
    $exams = $em->getRepository(Exam::class)->findBy(['teacher' => $teacher]);

    return $this->render('teacher/create_exam.html.twig', [
        'form' => $form->createView(),
        'exams' => $exams,
    ]);
}


//view exams

#[Route('/teacher/exam/{id}', name: 'teacher_view_exam')]
public function viewExam(int $id, EntityManagerInterface $em): Response
{
    $exam = $em->getRepository(Exam::class)->find($id);
    return $this->render('teacher/view_exam.html.twig', [
        'exam' => $exam,
    ]);
}



//edit exams in list

#[Route('/teacher/exam/{id}/edit', name: 'teacher_edit_exam')]
public function editExam(int $id, Request $request, EntityManagerInterface $em): Response
{
    $exam = $em->getRepository(Exam::class)->find($id);
    $form = $this->createForm(ExamType::class, $exam);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $exam->setUpdatedAt(new \DateTime());
        $em->flush();

        return $this->redirectToRoute('teacher_create_exam');
    }

    return $this->render('teacher/edit_exam.html.twig', [
        'form' => $form->createView(),
        'exam' => $exam,
    ]);
}

//delete exams

#[Route('/teacher/exam/{id}/delete', name: 'teacher_delete_exam')]
public function deleteExam(int $id, EntityManagerInterface $em): Response
{
    $exam = $em->getRepository(Exam::class)->find($id);
    if ($exam) {
        $em->remove($exam);
        $em->flush();
    }

    return $this->redirectToRoute('teacher_create_exam');
}


//publish exams

#[Route('/teacher/exam/{id}/publish', name: 'teacher_publish_exam')]
public function publishExam(int $id, EntityManagerInterface $em): Response
{
    $exam = $em->getRepository(Exam::class)->find($id);
    if ($exam) {
        $exam->setPublished(true); // Assuming you have a published field
        $em->flush();
    }

    return $this->redirectToRoute('teacher_create_exam');
}






//Teacher Exam Controller --------------------------------------------------------------Ends here




}
