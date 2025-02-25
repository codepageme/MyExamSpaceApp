<?php

// src/Controller/TeacherController.php
namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;



use App\Entity\TeacherSubject;
use App\Entity\Teacher;
use App\Entity\TeacherSubjectClassroom;
use App\Entity\Examtype;
use App\Entity\QuestionType;
use App\Entity\AcademicCalender;
use App\Entity\Subject; // Correct namespace
use App\Entity\Classroom;
use App\Entity\RadioOption;
use App\Entity\Term; 
use App\Entity\Session; 
use App\Entity\CheckboxOption;
use App\Entity\BooleanOption;
use App\Entity\GermanOption;
use App\Entity\DropdownOption;
use App\Entity\RegisterOption;
use App\Entity\ImagesOption;
use App\Entity\Theory;










use App\Repository\TeacherRepository;

use App\Entity\TeacherNote;
use App\Form\TeacherNoteType;
use App\Repository\TeacherNoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Exam;
use App\Form\SetExamType;
use App\Entity\Question;
use App\Form\AQuestionType;
use App\Form\TeacherProfilePictureType;


use App\Repository\TeacherSubjectClassroomRepository;
use App\Repository\SubjectRepository;
use App\Repository\QuestionRepository;
use App\Repository\TeacherClassroomRepository;



class TeacherController extends AbstractController
{

    private $entityManager;
    private $doctrine;

    // Updated constructor with both dependencies
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $doctrine)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine; // Save the ManagerRegistry for later use
    }



//Teacher Login Logic --------------------------------------------------------------------------D.O.N.E

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


//Teacher Account Controller  -------------------------------------------- ; Note ; starts here 

#[Route('/teacher/account', name: 'teacher_account')]
public function account(EntityManagerInterface $em, Request $request, TeacherClassroomRepository $teacherClassroomRepository): Response
{
     // Retrieve the logged-in teacher
     $teacher = $this->getUser();

     // Check if the teacher is a classroom teacher
     $teacherClassrooms = $teacherClassroomRepository->findOneBy(['teacher' => $teacher]);
     $isClassroomTeacher = $teacherClassrooms !== null;
 
     // Get the classroom information
     $classroom = null;
     if ($isClassroomTeacher) {
         $classroom = $teacherClassrooms->getClassroom();
     }
 
     // Get the number of students in the classroom
     $studentsCount = 0;
     if ($classroom) {
         $studentsCount = count($classroom->getStudents());
     }

$teacherSubject = $em->getRepository(TeacherSubject::class)->findOneBy(['teacher' => $teacher]);
$subject = $teacherSubject->getSubject();
$subjectName = $subject->getcourse();

$questionsCount = $em->getRepository(Question::class)->countByTeacher($teacher);

    // Create a new note form
    $note = new TeacherNote();
    $noteForm = $this->createForm(TeacherNoteType::class, $note);
    $noteForm->handleRequest($request);

    if ($noteForm->isSubmitted() && $noteForm->isValid()) {
        $note->setTeacher($teacher);
        $note->setCreatedAt(new \DateTime());
        $note->setUpdatedAt(new \DateTime());

        $em->persist($note);
        $em->flush();

        return $this->redirectToRoute('teacher_account');
    }
    

    // Create Profile Picture Form
    $profileForm = $this->createForm(TeacherProfilePictureType::class);
    $profileForm->handleRequest($request);

    if ($profileForm->isSubmitted() && $profileForm->isValid()) {
        $imageFile = $profileForm->get('profilePicture')->getData();

        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            $imageFile->move($this->getParameter('profile_pictures_directory'), $newFilename);
            $teacher->setProfilePicture($newFilename);

            $em->persist($teacher);
            $em->flush();

            return $this->redirectToRoute('teacher_account');
        }
    }

    return $this->render('teacher/account.html.twig', [
    'teacher' => $teacher,
    'noteForm' => $noteForm->createView(),
    'profileForm' => $profileForm->createView(),
    'isClassroomTeacher' => $isClassroomTeacher,
    'classroom' => $classroom,
    'studentsCount' => $studentsCount,
    'subjectName' => $subjectName,
    'questionsCount' => $questionsCount,
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
//Teacher Question logic -------------------------------------------------------------- Working

//preview question

#[Route('/teacher/preview-question', name: 'teacher_preview_question')]
    public function previewQuestions(): Response
    {
        $teacher = $this->getUser(); // Get the currently logged-in teacher

        // Fetch the current academic term and session from the AcademicCalendar
        $academicCalender = $this->entityManager->getRepository(AcademicCalender::class)
            ->findOneBy([], ['id' => 'DESC']);  // Assuming latest record represents the current term/session
        
        if (!$academicCalender) {
            throw $this->createNotFoundException('Academic Calendar not found');
        }

        $currentTerm = $academicCalender->getTerm();
        $currentSession = $academicCalender->getSession();

        // Fetch questions for the logged-in teacher using the EntityManager and JOIN with related options
        $questions = $this->entityManager->getRepository(Question::class)
            ->createQueryBuilder('q')
            ->leftJoin('q.classrooms', 'c')->addSelect('c')
            ->leftJoin('q.radioOption', 'ro')->addSelect('ro')
            ->leftJoin('q.checkboxOption', 'co')->addSelect('co')
            ->leftJoin('q.germanOption', 'go')->addSelect('go')
            ->leftJoin('q.booleanOption', 'bo')->addSelect('bo')
            ->leftJoin('q.dropdownOption', 'do')->addSelect('do')
            ->leftJoin('q.registerOption', 'ropt')->addSelect('ropt')
            ->leftJoin('q.imagesOption', 'io')->addSelect('io')
            ->where('q.teacher = :teacher')
            ->setParameter('teacher', $teacher)
            ->orderBy('q.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        // Render the Twig template and pass the questions with related options
        return $this->render('teacher/previewquestion.html.twig', [
            'questions' => $questions,
            'currentTerm' => $currentTerm,
            'currentSession' => $currentSession,
        ]);
    }



//Delete action ajax
#[Route('/teacher/deletequestion/{id}', name: 'teacher_delete_question', methods: ['DELETE'])]
public function deleteQuestion(Request $request, Question $question, EntityManagerInterface $entityManager): JsonResponse
{
    // Check if the request is AJAX
    if ($request->isXmlHttpRequest()) {
        try {
            // Remove the question
            $entityManager->remove($question);
            $entityManager->flush();

            return new JsonResponse(['status' => 'success', 'message' => 'Question deleted successfully.']);
        } catch (\Exception $e) {
            // Log the error for debugging if needed
            return new JsonResponse(['status' => 'error', 'message' => 'Error deleting the question.']);
        }
    }

    return new JsonResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
}


//Editquestion
#[Route('/teacher/editquestion/{id}', name: 'teacher_edit_question', methods: ['POST'])]
public function editQuestion(Request $request, Question $question, EntityManagerInterface $entityManager): JsonResponse
{
    // Ensure the request is AJAX
    if ($request->isXmlHttpRequest()) {
        $newContent = $request->request->get('content');

        if ($newContent) {
            try {
                // Update the question content
                $question->setContent($newContent);
                $entityManager->flush();

                return new JsonResponse(['status' => 'success', 'message' => 'Question updated successfully.']);
            } catch (\Exception $e) {
                return new JsonResponse(['status' => 'error', 'message' => 'Error updating the question.']);
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Invalid content.']);
    }

    return new JsonResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
}



//Export-Question
#[Route('/teacher/exportquestions', name: 'teacher_export_questions', methods: ['POST'])]
public function exportQuestions(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Check if the request is AJAX
    if ($request->isXmlHttpRequest()) {
        // Decode the JSON payload
        $data = json_decode($request->getContent(), true);

        if (!isset($data['questionIds']) || !is_array($data['questionIds'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid questionIds input.'], 400);
        }

        $questionIds = $data['questionIds'];

        // Fetch selected questions from the database
        $questions = $entityManager->getRepository(Question::class)->findBy(['id' => $questionIds]);

        if (empty($questions)) {
            return new JsonResponse(['status' => 'error', 'message' => 'No valid questions found.'], 404);
        }

        $fileContent = '';
        foreach ($questions as $question) {
            // Ensure only Radio-Button questions are exported
            if ($question->getQuestionType()->getName() !== 'Radio-Button') {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Export failed: This Questiontype isnt available; Only Radio-Button questions are Currently allowed.',
                ], 400);
            }

            // Build the content for each question
            $fileContent .= "Question: " . $question->getContent() . "\n";

            $radioOptions = $question->getRadioOption(); // Assuming getRadioOption() fetches related options
            if ($radioOptions) {
                $fileContent .= "A: " . $radioOptions->getOptionA() . "\n";
                $fileContent .= "B: " . $radioOptions->getOptionB() . "\n";
                $fileContent .= "C: " . $radioOptions->getOptionC() . "\n";
                $fileContent .= "D: " . $radioOptions->getOptionD() . "\n";
                $fileContent .= "E: " . $radioOptions->getOptionE() . "\n";
                $fileContent .= "Correct Answer: " . $radioOptions->getCorrectAnswer() . "\n";
            }

            $fileContent .= "\n"; // Add spacing between questions
        }

        // Return the generated content for the text file
        return new JsonResponse(['status' => 'success', 'data' => $fileContent]);
    }

    // Return an error for non-AJAX requests
    return new JsonResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
}



//filters
//term
#[Route('/teacher/getTerms', name: 'teacher_get_terms', methods: ['GET'])]
public function getTerms(EntityManagerInterface $entityManager): JsonResponse
{
    // Use EntityManager to fetch terms
    $query = $entityManager->createQuery('SELECT t.id, t.name FROM App\Entity\Term t');
    $terms = $query->getResult();

    return new JsonResponse(['terms' => $terms]);
}




//--------------------------------------------------------------------------------------------END HERE ; 
//CREATE QUESTION BEGINS HERE -------------------------------------------------------------------------------;

//Create-Question

#[Route('/teacher/create-question', name: 'teacher_create_question')]
public function createQuestion(Request $request): Response
{
    // Get the logged-in teacher
    $teacher = $this->getUser();

    // Fetch the newest AcademicCalendar record
    $academicCalendar = $this->entityManager->getRepository(AcademicCalender::class)
    ->findOneBy([], ['startDate' => 'DESC']); // Order by startDate descending

    // Retrieve TeacherSubject records for this teacher
    $teacherSubjects = $this->entityManager->getRepository(TeacherSubject::class)->findBy(['teacher' => $teacher]);
    
    // Check if an AcademicCalendar exists
    if ($academicCalendar) {
        // Get the Term and Session related to the AcademicCalendar
        $term = $academicCalendar->getTerm();  // Get the Term object
        $session = $academicCalendar->getSession();  // Get the Session object
    
        // Retrieve the IDs for term and session
        $termId = $term ? $term->getId() : null;
        $sessionId = $session ? $session->getId() : null;
    
        // Retrieve their names (or any other properties if needed)
        $term = $term ? $term->getName() : null;
        $session = $session ? $session->getName() : null;
    } else {
        $termId = $sessionId = $termName = $sessionName = null; // Handle case if there's no AcademicCalendar
    }


    return $this->render('teacher/createquestion.html.twig', [
        'teacherSubjects' => $teacherSubjects,
        'currentTerm' => $term,
        'currentSession' => $session,
        'termId' => $termId,
        'sessionId' => $sessionId,
    ]);
}







#[Route('/teacher/get-classrooms/{teacherSubjectId}', name: 'teacher_get_classrooms')]
public function getClassrooms(int $teacherSubjectId): JsonResponse
{
    $classrooms = $this->entityManager->getRepository(TeacherSubjectClassroom::class)
        ->findBy(['teacherSubject' => $teacherSubjectId]);

    // Check if classrooms were found
    if (!$classrooms) {
        return new JsonResponse(['message' => 'No classrooms found for this subject'], 404);
    }

    $classroomData = [];
    foreach ($classrooms as $classroomAssociation) {
        $classroom = $classroomAssociation->getClassroom();
        $departmentName = $classroom->getDepartment() ? $classroom->getDepartment()->getDepartment() : 'No Department';  // Get department name

        $classroomData[] = [
            'id' => $classroom->getId(),
            'classname' => $classroom->getClassname(),
            'department' => $departmentName,  // Send department name instead of the whole department object
        ];
    }

    return new JsonResponse($classroomData);
}
    




#[Route('/teacher/get-examtype', name: 'teacher_get_examtype')]
public function getExamTypes(): JsonResponse
{
    // Fetch exam types from the database
    $examTypes = $this->entityManager->getRepository(Examtype::class)->findAll();

    $examTypeData = [];
    foreach ($examTypes as $examType) {  // Corrected variable name here
        $examTypeData[] = [
            'id' => $examType->getId(),
            'name' => $examType->getName(),
        ];
    }

    return new JsonResponse($examTypeData);
}




#[Route('/teacher/get-questiontype', name: 'teacher_get_questiontype')]
public function getQuestionTypes(): JsonResponse
{
    // Fetch question types from the database
    $questionTypes = $this->entityManager->getRepository(QuestionType::class)->findAll();

    $questionTypeData = [];
    foreach ($questionTypes as $questionType) {
        $questionTypeData[] = [
            'id' => $questionType->getId(),
            'name' => $questionType->getName(),
        ];
    }

    return new JsonResponse($questionTypeData);
}




// Radio
#[Route('teacher/save-question', name: 'teacher_save_question', methods: ['POST'])]
#[IsGranted('ROLE_TEACHER')] // Ensure only teachers can access this route
public function saveQuestion(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Extract data from the request
    $content = $data['content'] ?? null;
    $optionA = $data['optionA'] ?? null;
    $optionB = $data['optionB'] ?? null;
    $optionC = $data['optionC'] ?? null;
    $optionD = $data['optionD'] ?? null;
    $optionE = $data['optionE'] ?? null;
    $correctAnswer = strtoupper($data['correctAnswer'] ?? ''); // Ensure uppercase
    $subjectId = $data['subjectId'] ?? null;
    $classroomIds = $data['classroomId'] ?? []; // Array of classroom IDs
    $examTypeId = $data['examTypeId'] ?? null;
    $questionTypeId = $data['questionTypeId'] ?? null;
    $termId = $data['termId'] ?? null; // ID of the term
    $sessionId = $data['sessionId'] ?? null; // ID of the session

    // Validate input data
    if (!$content) {
        return new JsonResponse(['status' => 'error', 'message' => 'Question content is required.'], 400);
    }
    if (!$optionA || !$optionB) {
        return new JsonResponse(['status' => 'error', 'message' => 'Options A and B are required.'], 400);
    }
    if (!$correctAnswer) {
        return new JsonResponse(['status' => 'error', 'message' => 'Correct answer is required.'], 400);
    }
    if (!$subjectId || !$classroomIds || !$examTypeId || !$questionTypeId || !$termId || !$sessionId) {
        return new JsonResponse(['status' => 'error', 'message' => 'Missing required IDs (subject, classroom, exam type, question type, term, session).'], 400);
    }

    $entityManager = $this->entityManager;

    // Fetch related entities
    $subject = $entityManager->getRepository(Subject::class)->find($subjectId);
    $examType = $entityManager->getRepository(ExamType::class)->find($examTypeId);
    $questionType = $entityManager->getRepository(QuestionType::class)->find($questionTypeId);
    $term = $entityManager->getRepository(Term::class)->find($termId);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    if (!$subject || !$examType || !$questionType || !$term || !$session) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid related entities.'], 400);
    }

    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    if (!$teacher) {
        return new JsonResponse(['status' => 'error', 'message' => 'Teacher not logged in.'], 403);
    }

    // Begin transaction for atomicity
    $entityManager->getConnection()->beginTransaction();

    try {
        // Save the Question entity
        $question = new Question();
        $question->setContent($content);
        $question->setSubject($subject);
        $question->setExamType($examType);
        $question->setQuestionType($questionType);
        $question->setTerm($term); // Set the Term object
        $question->setSession($session); // Set the Session object
        $question->setTeacher($teacher); // $teacher is the currently logged-in teacher
        $question->setCreatedAt(new \DateTimeImmutable());

        // Filter and add classrooms
        $classroomIds = array_filter(array_unique($classroomIds)); // Remove duplicates and empty values
        foreach ($classroomIds as $classroomId) {
            $classroom = $entityManager->getRepository(Classroom::class)->find($classroomId);
            if ($classroom) {
                $question->addClassroom($classroom);
            } else {
                error_log("Invalid Classroom ID: $classroomId"); // Log invalid classroom IDs
            }
        }

        $entityManager->persist($question);
        $entityManager->flush();

        // Save Radio Options
        $radioOption = new RadioOption();
        $radioOption->setQuestion($question);
        $radioOption->setOptionA($optionA);
        $radioOption->setOptionB($optionB);
        $radioOption->setOptionC($optionC);
        $radioOption->setOptionD($optionD);
        $radioOption->setOptionE($optionE);
        $radioOption->setCorrectAnswer($correctAnswer);

        $entityManager->persist($radioOption);
        $entityManager->flush();

        // Commit transaction
        $entityManager->getConnection()->commit();

        // Return success response with additional data
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Question saved successfully.',
            'data' => [
                'questionId' => $question->getId(),
            ],
        ]);
    } catch (\Exception $e) {
        // Rollback transaction on failure
        $entityManager->getConnection()->rollBack();

        // Log the exception
        error_log($e->getMessage());

        return new JsonResponse(['status' => 'error', 'message' => 'Failed to save question.'], 500);
    }
}






//checkbox
#[Route('/teacher/savecheckbox-question', name: 'teacher_savecheckbox_question', methods: ['POST'])]
public function saveCheckboxQuestion(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Extract data
    $content = $data['content'] ?? null;
    $optionA = $data['optionA'] ?? null;
    $optionB = $data['optionB'] ?? null;
    $optionC = $data['optionC'] ?? null;
    $optionD = $data['optionD'] ?? null;
    $optionE = $data['optionE'] ?? null;
    $correctAnswers = $data['correctAnswers'] ?? []; // Array of checked values
    $subjectId = $data['subjectId'] ?? null;
    $classroomIds = $data['classroomId'] ?? []; // Array of classroom IDs
    $examTypeId = $data['examTypeId'] ?? null;
    $questionTypeId = $data['questionTypeId'] ?? null;
    $termId = $data['termId'] ?? null;
    $sessionId = $data['sessionId'] ?? null;

    // Validate input
    if (!$content || !$optionA || !$optionB || empty($correctAnswers) || !$subjectId || !$classroomIds || !$examTypeId || !$questionTypeId || !$termId || !$sessionId) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid or incomplete data.'], 400);
    }

    $entityManager = $this->entityManager;

    // Fetch related entities
    $subject = $entityManager->getRepository(Subject::class)->find($subjectId);
    $examType = $entityManager->getRepository(ExamType::class)->find($examTypeId);
    $questionType = $entityManager->getRepository(QuestionType::class)->find($questionTypeId);
    $term = $entityManager->getRepository(Term::class)->find($termId);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    if (!$subject || !$examType || !$questionType || !$term || !$session) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid related entities.'], 400);
    }

    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    if (!$teacher) {
        return new JsonResponse(['status' => 'error', 'message' => 'Teacher not logged in.'], 403);
    }

    // Begin transaction
    $entityManager->beginTransaction();

    try {
        // Save the Question entity
        $question = new Question();
        $question->setContent($content);
        $question->setSubject($subject);
        $question->setExamType($examType);
        $question->setQuestionType($questionType);
        $question->setTerm($term);
        $question->setSession($session);
        $question->setTeacher($teacher);
        $question->setCreatedAt(new \DateTimeImmutable());

        // Add classrooms
        foreach ($classroomIds as $classroomId) {
            $classroom = $entityManager->getRepository(Classroom::class)->find($classroomId);
            if ($classroom) {
                $question->addClassroom($classroom);
            }
        }

        $entityManager->persist($question);
        $entityManager->flush();  // Flush to save the question

        // Save Checkbox Options
        $checkboxOption = new CheckboxOption();
        $checkboxOption->setQuestion($question);
        $checkboxOption->setOptionA($optionA);
        $checkboxOption->setOptionB($optionB);
        $checkboxOption->setOptionC($optionC);
        $checkboxOption->setOptionD($optionD);
        $checkboxOption->setOptionE($optionE);
        $checkboxOption->setCorrectAnswers($correctAnswers); // Store array of correct answers

        $entityManager->persist($checkboxOption);
        $entityManager->flush();  // Flush to save the checkbox options

        // Commit transaction
        $entityManager->commit();

        return new JsonResponse(['status' => 'success', 'message' => 'Checkbox question saved successfully.']);
    } catch (\Exception $e) {
        // Rollback transaction if any error occurs
        $entityManager->rollback();
        return new JsonResponse(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}








//Boolean
#[Route('/teacher/saveboolean-question', name: 'teacher_saveboolean_question', methods: ['POST'])]
public function saveBooleanQuestion(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Extract data
    $content = $data['content'] ?? null;
    $correctAnswer = $data['correctAnswer'] ?? null; // Should be 'true' or 'false'
    $subjectId = $data['subjectId'] ?? null;
    $classroomIds = $data['classroomId'] ?? []; // Array of classroom IDs
    $examTypeId = $data['examTypeId'] ?? null;
    $questionTypeId = $data['questionTypeId'] ?? null;
    $termId = $data['termId'] ?? null;
    $sessionId = $data['sessionId'] ?? null;

    // Validate input
    if (!$content || !$correctAnswer || !$subjectId || !$classroomIds || !$examTypeId || !$questionTypeId || !$termId || !$sessionId) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid or incomplete data.'], 400);
    }

    $entityManager = $this->entityManager;

    // Fetch related entities
    $subject = $entityManager->getRepository(Subject::class)->find($subjectId);
    $examType = $entityManager->getRepository(ExamType::class)->find($examTypeId);
    $questionType = $entityManager->getRepository(QuestionType::class)->find($questionTypeId);
    $term = $entityManager->getRepository(Term::class)->find($termId);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    if (!$subject || !$examType || !$questionType || !$term || !$session) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid related entities.'], 400);
    }

    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    if (!$teacher) {
        return new JsonResponse(['status' => 'error', 'message' => 'Teacher not logged in.'], 403);
    }

    // Begin transaction
    $entityManager->beginTransaction();

    try {
        // Save the Question entity
        $question = new Question();
        $question->setContent($content);
        $question->setSubject($subject);
        $question->setExamType($examType);
        $question->setQuestionType($questionType);
        $question->setTerm($term);
        $question->setSession($session);
        $question->setTeacher($teacher);
        $question->setCreatedAt(new \DateTimeImmutable());

        // Add classrooms
        foreach ($classroomIds as $classroomId) {
            $classroom = $entityManager->getRepository(Classroom::class)->find($classroomId);
            if ($classroom) {
                $question->addClassroom($classroom);
            }
        }

        $entityManager->persist($question);
        $entityManager->flush(); // Flush to save the question

        // Save Boolean Option
        $booleanOption = new BooleanOption(); // Assuming a BooleanOption entity exists
        $booleanOption->setQuestion($question);
        $booleanOption->setCorrectAnswer(filter_var($correctAnswer, FILTER_VALIDATE_BOOLEAN)); // Convert 'true'/'false' to boolean

        $entityManager->persist($booleanOption);
        $entityManager->flush(); // Flush to save the boolean option

        // Commit transaction
        $entityManager->commit();

        return new JsonResponse(['status' => 'success', 'message' => 'Boolean question saved successfully.']);
    } catch (\Exception $e) {
        // Rollback transaction if any error occurs
        $entityManager->rollback();
        return new JsonResponse(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}






//German
#[Route('/teacher/savegerman-question', name: 'teacher_savegerman_question', methods: ['POST'])]
public function saveGermanQuestion(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Extract data
    $content = $data['content'] ?? null;
    $correctAnswer = $data['correctAnswer'] ?? null;
    $subjectId = $data['subjectId'] ?? null;
    $classroomIds = $data['classroomId'] ?? []; // Array of classroom IDs
    $examTypeId = $data['examTypeId'] ?? null;
    $questionTypeId = $data['questionTypeId'] ?? null;
    $termId = $data['termId'] ?? null;
    $sessionId = $data['sessionId'] ?? null;

    // Validate input
    if (!$content || !$correctAnswer || !$subjectId || !$classroomIds || !$examTypeId || !$questionTypeId || !$termId || !$sessionId) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid or incomplete data.'], 400);
    }

    $entityManager = $this->entityManager;

    // Fetch related entities
    $subject = $entityManager->getRepository(Subject::class)->find($subjectId);
    $examType = $entityManager->getRepository(ExamType::class)->find($examTypeId);
    $questionType = $entityManager->getRepository(QuestionType::class)->find($questionTypeId);
    $term = $entityManager->getRepository(Term::class)->find($termId);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    if (!$subject || !$examType || !$questionType || !$term || !$session) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid related entities.'], 400);
    }

    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    if (!$teacher) {
        return new JsonResponse(['status' => 'error', 'message' => 'Teacher not logged in.'], 403);
    }

    // Begin transaction
    $entityManager->beginTransaction();

    try {
        // Save the Question entity
        $question = new Question();
        $question->setContent($content);
        $question->setSubject($subject);
        $question->setExamType($examType);
        $question->setQuestionType($questionType);
        $question->setTerm($term);
        $question->setSession($session);
        $question->setTeacher($teacher);
        $question->setCreatedAt(new \DateTimeImmutable());

        // Add classrooms
        foreach ($classroomIds as $classroomId) {
            $classroom = $entityManager->getRepository(Classroom::class)->find($classroomId);
            if ($classroom) {
                $question->addClassroom($classroom);
            }
        }

        $entityManager->persist($question);
        $entityManager->flush(); // Save the question to generate its ID

        // Save the German Correct Answer
        $germanOption = new GermanOption();
        $germanOption->setQuestion($question);
        $germanOption->setCorrectAnswer($correctAnswer);

        $entityManager->persist($germanOption);
        $entityManager->flush(); // Save the German option

        // Commit transaction
        $entityManager->commit();

        return new JsonResponse(['status' => 'success', 'message' => 'German question saved successfully.']);
    } catch (\Exception $e) {
        // Rollback transaction if any error occurs
        $entityManager->rollback();
        return new JsonResponse(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}





//Dropdown
#[Route('/teacher/savedropdown-question', name: 'teacher_savedropdown_question', methods: ['POST'])]
public function saveDropdownQuestion(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Extract data
    $content = $data['content'] ?? null;
    $correctAnswer = $data['correctAnswer'] ?? null;
    $subjectId = $data['subjectId'] ?? null;
    $classroomIds = $data['classroomId'] ?? []; // Array of classroom IDs
    $examTypeId = $data['examTypeId'] ?? null;
    $questionTypeId = $data['questionTypeId'] ?? null;
    $termId = $data['termId'] ?? null;
    $sessionId = $data['sessionId'] ?? null;

    // Validation: Ensure all required data is present
    if (!$content || !$correctAnswer || !$subjectId || !$classroomIds || !$examTypeId || !$questionTypeId || !$termId || !$sessionId) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid or incomplete data.'], 400);
    }

    // Regex to match exactly one "[/]" in the content
    if (!preg_match_all('/\[.*?\/.*?\]/', $content, $matches) || count($matches[0]) !== 1) {
        return new JsonResponse(['status' => 'error', 'message' => 'The question must contain exactly one "[/]".'], 400);
    }

    // Extract dropdown options
    preg_match('/\[(.*?)\]/', $content, $matches);
    $dropdownOptions = explode('/', $matches[1] ?? '');

    // Validate that the correct answer is part of the dropdown options
    if (!in_array($correctAnswer, $dropdownOptions)) {
        return new JsonResponse(['status' => 'error', 'message' => 'Correct answer must be one of the options.'], 400);
    }

    $entityManager = $this->entityManager;

    // Fetch related entities
    $subject = $entityManager->getRepository(Subject::class)->find($subjectId);
    $examType = $entityManager->getRepository(ExamType::class)->find($examTypeId);
    $questionType = $entityManager->getRepository(QuestionType::class)->find($questionTypeId);
    $term = $entityManager->getRepository(Term::class)->find($termId);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    if (!$subject || !$examType || !$questionType || !$term || !$session) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid related entities.'], 400);
    }

    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    if (!$teacher) {
        return new JsonResponse(['status' => 'error', 'message' => 'Teacher not logged in.'], 403);
    }

    // Begin transaction
    $entityManager->beginTransaction();

    try {
        // Save the Question entity
        $question = new Question();
        $question->setContent($content);
        $question->setSubject($subject);
        $question->setExamType($examType);
        $question->setQuestionType($questionType);
        $question->setTerm($term);
        $question->setSession($session);
        $question->setTeacher($teacher);
        $question->setCreatedAt(new \DateTimeImmutable());

        // Add classrooms
        foreach ($classroomIds as $classroomId) {
            $classroom = $entityManager->getRepository(Classroom::class)->find($classroomId);
            if ($classroom) {
                $question->addClassroom($classroom);
            }
        }

        $entityManager->persist($question);
        $entityManager->flush(); // Flush to save the question

        // Save Dropdown Options
        $dropdownOption = new DropdownOption();
        $dropdownOption->setQuestion($question);
        $dropdownOption->setCorrectAnswer($correctAnswer);

        $entityManager->persist($dropdownOption);
        $entityManager->flush(); // Flush to save the dropdown options

        // Commit transaction
        $entityManager->commit();

        return new JsonResponse(['status' => 'success', 'message' => 'Dropdown question saved successfully.']);
    } catch (\Exception $e) {
        // Log the error and rollback
        $entityManager->rollback();
        return new JsonResponse(['status' => 'error', 'message' => 'An error occurred.'], 500);
    }
}






//Register
#[Route('/teacher/saveregister-question', name: 'teacher_saveregister_question', methods: ['POST'])]
public function saveRegisterQuestion(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Extract data
    $content = $data['content'] ?? null;
    $correctAnswers = $data['correctAnswers'] ?? []; // Array of correct answers
    $subjectId = $data['subjectId'] ?? null;
    $classroomIds = $data['classroomId'] ?? []; // Array of classroom IDs
    $examTypeId = $data['examTypeId'] ?? null;
    $questionTypeId = $data['questionTypeId'] ?? null;
    $termId = $data['termId'] ?? null;
    $sessionId = $data['sessionId'] ?? null;

    // Validation: Ensure required data is provided
    if (!$content || !$subjectId || !$classroomIds || !$examTypeId || !$questionTypeId || !$termId || !$sessionId || empty($correctAnswers)) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid or incomplete data.'], 400);
    }

    // Extract all dropdown markers (e.g., [option1/option2/option3]) from the content
    preg_match_all('/\[(.*?)\]/', $content, $matches);
    $optionsList = $matches[1] ?? []; // Each match contains the options for a dropdown

    // Validation: At least 5 dropdown markers and correct answers must match their count
    if (count($optionsList) < 5) {
        return new JsonResponse(['status' => 'error', 'message' => 'The question must contain at least 5 dropdown markers.'], 400);
    }
    if (count($correctAnswers) !== count($optionsList)) {
        return new JsonResponse(['status' => 'error', 'message' => 'The number of correct answers must match the number of dropdown option markers.'], 400);
    }

    $entityManager = $this->entityManager;

    // Fetch related entities
    $subject = $entityManager->getRepository(Subject::class)->find($subjectId);
    $examType = $entityManager->getRepository(ExamType::class)->find($examTypeId);
    $questionType = $entityManager->getRepository(QuestionType::class)->find($questionTypeId);
    $term = $entityManager->getRepository(Term::class)->find($termId);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    if (!$subject || !$examType || !$questionType || !$term || !$session) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid related entities.'], 400);
    }

    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    if (!$teacher) {
        return new JsonResponse(['status' => 'error', 'message' => 'Teacher not logged in.'], 403);
    }

    // Begin transaction
    $entityManager->beginTransaction();

    try {
        // Save the Question entity
        $question = new Question();
        $question->setContent($content);
        $question->setSubject($subject);
        $question->setExamType($examType);
        $question->setQuestionType($questionType);
        $question->setTerm($term);
        $question->setSession($session);
        $question->setTeacher($teacher);
        $question->setCreatedAt(new \DateTimeImmutable());

        // Add classrooms
        foreach ($classroomIds as $classroomId) {
            $classroom = $entityManager->getRepository(Classroom::class)->find($classroomId);
            if ($classroom) {
                $question->addClassroom($classroom);
            }
        }

        $entityManager->persist($question);
        $entityManager->flush(); // Flush to save the question

        // Save RegisterOption entity
        $registerOption = new RegisterOption();
        $registerOption->setOptions(array_map(fn($opt) => explode('/', $opt), $optionsList)); // Convert options to arrays
        $registerOption->setCorrectAnswers($correctAnswers); // Save correct answers
        $question->setRegisterOption($registerOption);

        $entityManager->persist($registerOption);
        $entityManager->flush(); // Flush to save the register option

        // Commit transaction
        $entityManager->commit();

        return new JsonResponse(['status' => 'success', 'message' => 'Register question saved successfully.']);
    } catch (\Exception $e) {
        // Rollback transaction if any error occurs
        $entityManager->rollback();
        return new JsonResponse(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}







//Image
#[Route('/teacher/saveimage-question', name: 'teacher_saveimage_question', methods: ['POST'])]
public function saveImageQuestion(Request $request): JsonResponse
{
    $entityManager = $this->entityManager;

    // Retrieve the logged-in teacher
    $teacher = $this->getUser();

    if (!$teacher) {
        return new JsonResponse(['status' => 'error', 'message' => 'Teacher not logged in.'], 403);
    }

    // Handle FormData
    $content = $request->get('content');
    $correctAnswer = $request->get('correctAnswer');
    $subjectId = $request->get('subjectId');
    $examTypeId = $request->get('examTypeId');
    $questionTypeId = $request->get('questionTypeId');
    $termId = $request->get('termId');
    $sessionId = $request->get('sessionId');
    $classroomIds = $request->get('classroomId', []); // Classroom IDs as an array

    // Validate required fields
    if (
        !$content || !$correctAnswer || !$subjectId || !$examTypeId || !$questionTypeId ||
        !$termId || !$sessionId || empty($classroomIds)
    ) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid or incomplete data.'], 400);
    }

    // Fetch related entities
    $subject = $entityManager->getRepository(Subject::class)->find($subjectId);
    $examType = $entityManager->getRepository(ExamType::class)->find($examTypeId);
    $questionType = $entityManager->getRepository(QuestionType::class)->find($questionTypeId);
    $term = $entityManager->getRepository(Term::class)->find($termId);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    if (!$subject || !$examType || !$questionType || !$term || !$session) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid related entities.'], 400);
    }

    // Handle file upload
    $uploadedFile = $request->files->get('imageUpload');
    if (!$uploadedFile || !$uploadedFile->isValid()) {
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid image upload.'], 400);
    }

    $uploadsDir = $this->getParameter('message_files_directory') . '/question_images'; // Update with your uploads directory
    $fileName = uniqid() . '.' . $uploadedFile->guessExtension();

    try {
        $uploadedFile->move($uploadsDir, $fileName);
    } catch (\Exception $e) {
        return new JsonResponse(['status' => 'error', 'message' => 'Failed to upload the image.'], 500);
    }

    // Begin transaction
    $entityManager->beginTransaction();

    try {
        // Save the Question entity
        $question = new Question();
        $question->setContent($content);
        $question->setSubject($subject);
        $question->setExamType($examType);
        $question->setQuestionType($questionType);
        $question->setTerm($term);
        $question->setSession($session);
        $question->setTeacher($teacher);
        $question->setCreatedAt(new \DateTimeImmutable());

        // Add classrooms
        foreach ($classroomIds as $classroomId) {
            $classroom = $entityManager->getRepository(Classroom::class)->find($classroomId);
            if ($classroom) {
                $question->addClassroom($classroom);
            }
        }

        $entityManager->persist($question);
        $entityManager->flush(); // Save the question to generate its ID

        // Save the ImageOption entity
        $imagesOption = new ImagesOption();
        $imagesOption->setQuestion($question);
        $imagesOption->setCorrectAnswer($correctAnswer);
        $imagesOption->setImagePath('/uploads/message_files/question_images/' . $fileName);

        $entityManager->persist($imagesOption);
        $entityManager->flush(); // Save the ImageOption

        // Commit transaction
        $entityManager->commit();

        return new JsonResponse(['status' => 'success', 'message' => 'Image question saved successfully.']);
    } catch (\Exception $e) {
        // Rollback transaction if any error occurs
        $entityManager->rollback();
        return new JsonResponse(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}





//Theory
/**
 * @Route("/teacher/save-theoryquestion", name="teacher_save_theoryquestion", methods={"POST"})
 */
    public function saveTheoryQuestion(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Decode the JSON payload
        $data = json_decode($request->getContent(), true);

        // Retrieve the currently logged-in teacher
        $teacher = $this->getUser();

        if (!$teacher) {
            return new JsonResponse(['message' => 'Unauthorized. Please log in as a teacher.'], 403);
        }

        // Validate required fields
        if (empty($data['question'])) {
            return new JsonResponse(['message' => 'Question content is required.'], 400);
        }

        if (empty($data['subjectId'])) {
            return new JsonResponse(['message' => 'Subject is required.'], 400);
        }

        if (empty($data['classroomIds'])) {
            return new JsonResponse(['message' => 'At least one classroom is required.'], 400);
        }

        if (empty($data['termId']) || empty($data['sessionId'])) {
            return new JsonResponse(['message' => 'Term and session information are required.'], 400);
        }

        // Create a new TheoryQuestion
        $theory = new Theory();
        $theory->setQuestion($data['question']);
        $theory->setSubject($em->getReference(Subject::class, $data['subjectId']));
        $theory->setTerm($em->getReference(Term::class, $data['termId']));
        $theory->setSession($em->getReference(Session::class, $data['sessionId']));
        $theory->setExamType($em->getReference(ExamType::class, $data['examTypeId']));
        $theory->setQuestionType($em->getReference(QuestionType::class, $data['questionTypeId']));
        $theory->setTeacher($teacher); // Associate the question with the teacher
        $theory->setCreatedAt(new \DateTimeImmutable());

        // Add classrooms
        foreach ($data['classroomIds'] as $classroomId) {
            $classroom = $em->getReference(Classroom::class, $classroomId);
            $theory->addClassroom($classroom);
        }

        // Persist the TheoryQuestion entity
        $em->persist($theory);
        $em->flush();

        return new JsonResponse(['message' => 'Theory question saved successfully.']);
    }





    //viewscreen
    #[Route('/teacher/prev-question', name: 'teacher_prev_question', methods: ['GET'])]
    public function prevQuestions(Request $request): JsonResponse
    {
        // Fetch latest 7 general questions
        $questions = $this->entityManager->getRepository(Question::class)
            ->createQueryBuilder('q')
            ->orderBy('q.createdAt', 'DESC')
            ->setMaxResults(7)
            ->getQuery()
            ->getResult();
    
        // Fetch latest 7 theory questions
        $theoryQuestions = $this->entityManager->getRepository(Theory::class)
            ->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(7)
            ->getQuery()
            ->getResult();
    
        // Combine and sort by createdAt
        $combinedQuestions = array_merge($questions, $theoryQuestions);
        usort($combinedQuestions, function ($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });
    
        // Limit to newest 7
        $previewQuestions = array_slice($combinedQuestions, 0, 7);
    
        // Transform data for the frontend
        $data = array_map(function ($question) {
            return [
                'id' => $question->getId(),
                'content' => method_exists($question, 'getQuestion') ? $question->getQuestion() : $question->getContent(),
                // Handle classrooms as a comma-separated list
                'class' => $question->getClassrooms()->isEmpty()
                    ? 'No classes'
                    : implode(', ', $question->getClassrooms()->map(fn($classroom) => $classroom->getClassname())->toArray()),
                'subject' => $question->getSubject()->getCourse(),
                'createdAt' => $question->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $previewQuestions);
    
        return new JsonResponse($data);
    }
    

//---------------------------------------------------------------------------------------------D.O.N.E
//Result page Begins here-------------------------------------------------------------------;


#[Route('/teacher/results', name: 'teacher_results')]
public function results(EntityManagerInterface $em): Response
{
    // Get the logged-in teacher
    $teacher = $this->getUser();

    // Get subjects assigned to the teacher
    $subjects = $em->getRepository(Subject::class)->findBy(['teacher' => $teacher]);

    // Get classes assigned to the teacher
    $classes = $em->getRepository(TeacherClass::class)->findBy(['teacher' => $teacher]);

    // Get student results for the teacher's subjects
    $results = $em->getRepository(Result::class)->createQueryBuilder('r')
        ->join('r.student', 's')
        ->join('r.subject', 'sub')
        ->where('sub IN (:subjects)')
        ->setParameter('subjects', $subjects)
        ->getQuery()
        ->getResult();

    return $this->render('teacher/results.html.twig', [
        'teacher' => $teacher,
        'results' => $results,
        'subjects' => $subjects,
        'classes' => $classes,
    ]);
}






















//Teacher Exam Controller --------------------------------------------------------------Ends here




}
