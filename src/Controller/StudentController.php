<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Exam;
use App\Entity\RegisterOption;
use App\Entity\CheckboxOption;
use App\Entity\RadioOption;
use App\Entity\ImagesOption;
use App\Entity\GermanOption;
use App\Entity\BooleanOption;
use App\Entity\DropdownOption;
use App\Entity\Results;
use App\Entity\Term;
use App\Entity\AcademicCalender;
use App\Entity\Question;
use App\Entity\Responses;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ExamRepository; // For the repository
use Symfony\Component\HttpFoundation\JsonResponse; // For returning JSON responses
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;







class StudentController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

//Student login logic -----------------------------------------------------------------------------


    // Student login form route
   #[Route('/student', name: 'student_login_form')]
public function index(ClassroomRepository $classroomRepository): Response
{
    // Fetch all classrooms from the database
    $classrooms = $classroomRepository->findAll();

    // Render the template and pass the classrooms data
    return $this->render('student/index.html.twig', [
        'classrooms' => $classrooms,
        'error' => $_SESSION['error'] ?? null,
    ]);
}








#[Route('/student/access', name: 'student_access', methods: ['POST'])]
public function access(Request $request, StudentRepository $studentRepository): Response
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Get data from the form submission
    $firstname = $request->request->get('_Firstname');
    $lastname = $request->request->get('_Lastname');
    $classroomId = $request->request->get('_classroom');

    // Validate form fields
    if (!$firstname || !$lastname || !$classroomId) {
        $_SESSION['error'] = 'All fields are required';
        return $this->redirectToRoute('student_login_form');
    }

    // Search for the student in the database
    $student = $studentRepository->findOneBy([
        'Firstname' => strtolower($firstname),
        'Lastname' => strtolower($lastname),
        'classroom' => $classroomId,
    ]);

    // If the student is found
    if ($student) {
        // Save student data to the session, including roles
        $_SESSION['student'] = [
            'id' => $student->getId(),
            'name' => $student->getFirstname() . ' ' . $student->getLastname(),
            'classroom' => [
                'id' => $student->getClassroom()->getId(), // Add classroomId here
                'classname' => $student->getClassroom()->getClassname(),
                'department' => $student->getClassroom()->getDepartment()
                    ? $student->getClassroom()->getDepartment()->getName()
                    : null,
            ],
            'roles' => $student->getRoles(),
        ];

        // Redirect to the student dashboard
        return $this->redirectToRoute('student_dashboard');
    }

    // If student not found, return to login page with error message
    $_SESSION['error'] = 'Invalid credentials';
    return $this->redirectToRoute('student_login_form');
}




//Student logout logic --------------------------------------------------------------------------

#[Route('/student/logout', name: 'student_logout')]
public function logout(): Response
{
    if (!isset($_SESSION)) {
        session_start();
    }
    session_unset();
    session_destroy();
    return $this->redirectToRoute('student_login_form');
}


// Student dashboard logic --------------------------------------------------------------------------

#[Route('/student/dashboard', name: 'student_dashboard')]
public function dashboard(Request $request, ManagerRegistry $doctrine): Response
{
    // Ensure the session is started
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Redirect if the student is not logged in
    if (!isset($_SESSION['student'])) {
        return $this->redirectToRoute('student_login_form');
    }

    $student = $_SESSION['student'];

    // Fetch today's date and adjust for comparison
    $today = new \DateTime('now');
    $today->setTime(0, 0, 0); // Start of the day
    $tomorrow = clone $today;
    $tomorrow->modify('+1 day'); // Start of the next day

    // Fetch exams for today based on the student's class
    $ExamRepository = $doctrine->getRepository(Exam::class);
    $queryBuilder = $ExamRepository->createQueryBuilder('e');
    $queryBuilder
        ->where('e.Classroom = :ClassroomId')
        ->andWhere('e.Date >= :today')
        ->andWhere('e.Date < :tomorrow') // Ensure only today's exams
        ->setParameter('ClassroomId', $student['classroom']['id'])
        ->setParameter('today', $today)
        ->setParameter('tomorrow', $tomorrow);

    $todayExams = $queryBuilder->getQuery()->getResult();

    // Salted Base64 encoding for the exam tokens
    $salt = '*N+o-s?e/e\k*'; // Use a unique, secret salt value
    $examTokens = [];
    foreach ($todayExams as $exam) {
        // Combine exam ID with salt before encoding
        $saltedExamId = $salt . $exam->getId() . $salt;
        $examTokens[] = [
            'exam' => $exam, // The full exam object
            'examToken' => base64_encode($saltedExamId), // Salted and Base64-encoded token
        ];
    }

    // Prepare data for the view
    return $this->render('student/dashboard.html.twig', [
        'student' => $student,
        'hasExamToday' => !empty($todayExams),
        'exams' => $examTokens, 
    ]);
}





#[Route('/student/fetch-examdates', name: 'fetch_exam_dates', methods: ['GET'])]
public function fetchExamDates(Request $request, ExamRepository $examRepository): JsonResponse
{
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['student'])) {
        return new JsonResponse(['error' => 'Unauthorized'], 401);
    }

    $student = $_SESSION['student'];

    // Fetch exams for the student's class
    $examDates = $examRepository->createQueryBuilder('e')
        ->select('e.Date')
        ->where('e.Classroom = :ClassroomId')
        ->setParameter('ClassroomId', $student['classroom']['id'])
        ->distinct() // Ensure unique dates
        ->getQuery()
        ->getArrayResult();

    return new JsonResponse($examDates); // Returns JSON response with exam dates
}





#[Route('/student/fetch-todayexams', name: 'fetch_today_exams', methods: ['GET'])]
public function fetchTodayExams(Request $request, ExamRepository $examRepository): JsonResponse
{
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['student'])) {
        return new JsonResponse(['error' => 'Unauthorized'], 401);
    }

    $student = $_SESSION['student'];

    // Get today's date as a DateTime object
    $todayStart = new \DateTime('today');
    $todayEnd = (clone $todayStart)->modify('+1 day');

    // Fetch all exams for the class scheduled for today
    $exams = $examRepository->createQueryBuilder('e')
        ->where('e.Classroom = :ClassroomId')
        ->andWhere('e.Date >= :todayStart AND e.Date < :todayEnd')
        ->setParameter('ClassroomId', $student['classroom']['id'])
        ->setParameter('todayStart', $todayStart)
        ->setParameter('todayEnd', $todayEnd)
        ->getQuery()
        ->getResult();

    if (!empty($exams)) {
        // Prepare response data
        $response = array_map(function ($exam) {
            return [
                'subject' => $exam->getSubject()->getCourse(),
                'date' => $exam->getDate()->format('Y-m-d'),
                'start_time' => $exam->getStartTime()->format('H:i:s'),
                'end_time' => $exam->getEndTime()->format('H:i:s'),
                'examType' => $exam->getExamType()->getName(),
                'classroom' => $exam->getClassroom()->getClassName(),
                'total_questions' => $exam->getTotalQuestions(),
                'total_marks' => $exam->getTotalMarks(),
                'duration' => $exam->getDuration()->format('%H:%I:%S'),
            ];
        }, $exams);

        return new JsonResponse($response);
    } else {
        return new JsonResponse(['message' => 'No exams today']);
    }
}








#[Route('/student/examobjective/{examToken}', name: 'student_examobjective', methods: ['GET'])]
public function getExamobjective(
    string $examToken,
    ManagerRegistry $doctrine,
    TokenStorageInterface $tokenStorage,
    Request $request
): Response {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['student'])) {
        return $this->redirectToRoute('student_login_form');
    }

    $student = $_SESSION['student'];
    $examId = $this->decryptExamToken($examToken);

    $entityManager = $doctrine->getManager();
    $connection = $entityManager->getConnection();

    try {
        $connection->beginTransaction();

        // Fetch the exam
        $exam = $entityManager->getRepository(Exam::class)->find($examId);
        if (!$exam) {
            throw $this->createNotFoundException('Exam not found.');
        }

        $today = new \DateTime('now');
        $examDate = $exam->getDate();
        $studentClassroomId = $student['classroom']['id'];

        // Validate the student and exam context
        $examClassroomId = $exam->getClassroom()->getId();

        if (
            $examClassroomId !== $studentClassroomId || // Compare the classroom IDs
            $examDate->format('Y-m-d') !== $today->format('Y-m-d') // Compare the dates
        ) {
            $tokenStorage->setToken(null);
            $_SESSION = [];
            session_destroy();
            return $this->redirectToRoute('student_login_form');
        }

        // Fetch the current term and session from AcademicCalender
        $academicCalenderRepo = $entityManager->getRepository(AcademicCalender::class);
        $currentAcademicCalender = $academicCalenderRepo->findOneBy([], ['id' => 'DESC']); // Last row is current

        if (!$currentAcademicCalender) {
            throw new \Exception('Current academic term and session not found.');
        }

        $currentTerm = $currentAcademicCalender->getTerm(); // e.g., "First Term," "Second Term"
        $currentTermId = $currentTerm->getId();

        // Determine applicable terms for filtering
        $termRepository = $entityManager->getRepository(Term::class);
        $termsForQuery = [$currentTermId]; // Always include the current term
        if ($currentTerm->getName() === 'Second Term') {
            $firstTerm = $termRepository->findOneBy(['name' => 'First Term']);
            if ($firstTerm) {
                $termsForQuery[] = $firstTerm->getId();
            }
        } elseif ($currentTerm->getName() === 'Third Term') {
            $allTerms = $termRepository->findAll();
            $termsForQuery = array_map(fn($term) => $term->getId(), $allTerms);
        }

        // Fetch questions based on term and other filters
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('q', 'ro', 'co', 'go', 'bo', 'do', 'imgo', 'rego')
            ->from(Question::class, 'q')
            ->leftJoin('q.Term', 't')  // Join the Term relation
            ->leftJoin('q.classrooms', 'c')
            ->leftJoin('q.radioOption', 'ro')
            ->leftJoin('q.checkboxOption', 'co')
            ->leftJoin('q.germanOption', 'go')
            ->leftJoin('q.booleanOption', 'bo')
            ->leftJoin('q.dropdownOption', 'do')
            ->leftJoin('q.imagesOption', 'imgo')
            ->leftJoin('q.registerOption', 'rego')
            ->where('q.Subject = :SubjectId')
            ->andWhere('q.examtype = :ExamTypeId')
            ->andWhere('t.id IN (:terms)') // Filter by term
            ->andWhere('c.id = :ClassroomId')
            ->setParameter('SubjectId', $exam->getSubject()->getId())
            ->setParameter('ExamTypeId', $exam->getExamtype()->getId())
            ->setParameter('terms', $termsForQuery)  // Set terms array parameter
            ->setParameter('ClassroomId', $studentClassroomId)
            ->setMaxResults($exam->getTotalQuestions()); // Limit by totalQuestions in Exam

        $questions = $queryBuilder->getQuery()->getResult();

        // Generate and store question order specific to this student and exam
        $studentId = $student['id'];
        if (!isset($_SESSION['student_data'][$studentId]['exam'][$examId]['question_order'])) {
            shuffle($questions);
            $_SESSION['student_data'][$studentId]['exam'][$examId]['question_order'] = array_map(function ($question) {
                return $question->getId();
            }, $questions);
        }

        // Retrieve and reorder questions based on stored order
        $questionOrder = $_SESSION['student_data'][$studentId]['exam'][$examId]['question_order'];
        $orderedQuestions = [];
        foreach ($questionOrder as $questionId) {
            foreach ($questions as $question) {
                if ($question->getId() === $questionId) {
                    $orderedQuestions[] = $question;
                    break;
                }
            }
        }

        $connection->commit();
    } catch (\Exception $e) {
        $connection->rollBack();
        throw $e;
    }

    // Paginate questions
    $currentPage = max(1, (int)$request->query->get('page', 1));
    $questionsPerPage = 1;
    $totalQuestionsExpected = $exam->getTotalQuestions();
    $totalPages = (int)ceil($totalQuestionsExpected / $questionsPerPage);
    $paginatedQuestions = array_slice($orderedQuestions, ($currentPage - 1) * $questionsPerPage, $questionsPerPage);

    // Calculate exam duration
    $durationInSeconds = $this->calculateDurationInSeconds($exam->getDuration());

    // Render the exam view
    return $this->render('student/examobj.html.twig', [
        'exam' => $exam,
        'questions' => $paginatedQuestions,
        'totalQuestions' => $totalQuestionsExpected,
        'student' => $student,
        'durationInSeconds' => $durationInSeconds,
        'theoryPageUrl' => $this->generateUrl('student_examtheory', ['examToken' => $examToken]),
        'resultPageUrl' => $this->generateUrl('student_examresult', ['examToken' => $examToken]),
        'examType' => $exam->getExamType()->getName(),
        'studentId' => $student['name'],
        'examToken' => $examToken,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'questionIds' => array_map(function($question) { return $question->getId(); }, $paginatedQuestions), // Include question IDs
    ]);
}








private function calculateDurationInSeconds(\DateTime $duration): int {
    $formattedDuration = $duration->format('H:i:s');
    sscanf($formattedDuration, "%d:%d:%d", $hours, $minutes, $seconds);
    return ($hours * 3600) + ($minutes * 60) + $seconds;
}






#[Route('/student/{examToken}/questions', name: 'student_fetch_questions', methods: ['GET'])]
public function fetchQuestions(
    string $examToken,
    ManagerRegistry $doctrine,
    Request $request
): JsonResponse {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['student'])) {
        return new JsonResponse(['error' => 'Unauthorized'], 401);
    }

    $student = $_SESSION['student'];
    $examId = $this->decryptExamToken($examToken);

    // Validate the exam
    $entityManager = $doctrine->getManager();
    $exam = $entityManager->getRepository(Exam::class)->find($examId);
    if (!$exam) {
        return new JsonResponse(['error' => 'Exam not found'], 404);
    }

    // Validate session and exam context
    $studentClassroomId = $student['classroom']['id'];
    $examClassroomId = $exam->getClassroom()->getId();
    if ($examClassroomId !== $studentClassroomId) {
        return new JsonResponse(['error' => 'Unauthorized access to exam'], 403);
    }

    $studentId = $student['id'];
    $examNamespace = $_SESSION['student_data'][$studentId]['exam'][$examId] ?? null;

    if (!$examNamespace || !isset($examNamespace['question_order'])) {
        return new JsonResponse(['error' => 'Question order not initialized'], 500);
    }

    $questionOrder = $examNamespace['question_order'];
    $questionsPerPage = 1;
    $currentPage = max(1, (int)$request->query->get('page', 1));

    $totalPages = ceil(count($questionOrder) / $questionsPerPage);
    if ($currentPage > $totalPages || $currentPage < 1) {
        return new JsonResponse(['error' => 'Invalid page number'], 400);
    }

    $currentPageIds = array_slice($questionOrder, ($currentPage - 1) * $questionsPerPage, $questionsPerPage);

    try {
        // Fetch questions and options
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('q', 'ro', 'co', 'go', 'bo', 'do', 'imgo', 'rego')
            ->from(Question::class, 'q')
            ->leftJoin('q.radioOption', 'ro')
            ->leftJoin('q.checkboxOption', 'co')
            ->leftJoin('q.germanOption', 'go')
            ->leftJoin('q.booleanOption', 'bo')
            ->leftJoin('q.dropdownOption', 'do')
            ->leftJoin('q.imagesOption', 'imgo')
            ->leftJoin('q.registerOption', 'rego')
            ->where('q.id IN (:questionIds)')
            ->setParameter('questionIds', $currentPageIds);

        $questions = $queryBuilder->getQuery()->getResult();

        $orderedQuestions = [];
        foreach ($currentPageIds as $questionId) {
            foreach ($questions as $question) {
                if ($question->getId() === $questionId) {
                    $orderedQuestions[] = $question;
                    break;
                }
            }
        }

        if (empty($orderedQuestions)) {
            return new JsonResponse(['error' => 'No questions found'], 404);
        }

        $question = $orderedQuestions[0];
        $options = $this->getOptionsForQuestion($question);

        return new JsonResponse([
            'questionId' => $question->getId(),
            'question' => $question->getContent(),
            'options' => $options,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}






private function getOptionsForQuestion(Question $question): array {
    if ($question->getRadioOption()) {
        return [
            'type' => 'RadioOption',
            'options' => [
                'A' => $question->getRadioOption()->getOptionA(),
                'B' => $question->getRadioOption()->getOptionB(),
                'C' => $question->getRadioOption()->getOptionC(),
                'D' => $question->getRadioOption()->getOptionD(),
                'E' => $question->getRadioOption()->getOptionE(),
            ],
        ];
    } elseif ($question->getCheckboxOption()) {
        return [
            'type' => 'CheckboxOption',
            'options' => [
                'A' => $question->getCheckboxOption()->getOptionA(),
                'B' => $question->getCheckboxOption()->getOptionB(),
                'C' => $question->getCheckboxOption()->getOptionC(),
                'D' => $question->getCheckboxOption()->getOptionD(),
                'E' => $question->getCheckboxOption()->getOptionE(),
            ],
        ];
    } elseif ($question->getGermanOption()) {
        return [
            'type' => 'GermanOption',
            'options' => [], // No predefined options
        ];
    } elseif ($question->getBooleanOption()) {
        return [
            'type' => 'BooleanOption',
            'options' => [
                1 => 'True',
                0 => 'False',
            ],
        ];
    } elseif ($question->getDropdownOption()) {
        return [
            'type' => 'DropdownOption',
            'options' => [],
        ];
    } elseif ($question->getImagesOption()) {
        return [
            'type' => 'ImagesOption',
            'options' => [
                'imagePath' => $question->getImagesOption()->getImagePath(),
            ],
        ];
    } elseif ($question->getRegisterOption()) {
        return [
            'type' => 'RegisterOption',
            'options' => [], // Add details if available
        ];
    }

    return [
        'type' => 'Unknown',
        'options' => [],
    ];
}





private function decryptExamToken(string $token): int
{
    // Define the salt (same as used during encoding)
    $salt = '*N+o-s?e/e\k*';  // This should be the same as the salt used during encoding

    // Decode the base64 token
    $decodedToken = base64_decode($token);

    if ($decodedToken === false) {
        throw new \Exception('Token decryption failed');
    }

    // Remove the salt from both ends of the decoded string
    $tokenWithoutSalt = str_replace($salt, '', $decodedToken);

    // Return the decoded exam ID (as integer)
    return (int) $tokenWithoutSalt;
}





    // Route to save response dynamically (AJAX request)
   #[Route('/student/save-response', name: 'student_save_response', methods: ['POST'])]
public function saveResponse(Request $request): JsonResponse {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['student'])) {
        return new JsonResponse(['success' => false, 'message' => 'Unauthorized access.'], 401);
    }

    $studentId = $_SESSION['student']['id'];
    $questionId = $request->request->get('questionId');
    $examId = $request->request->get('examId');
    $responseValue = $request->request->get('answer');

    // Skip processing if no valid answer is provided
    if (!$questionId || !$examId || $responseValue === null || (is_array($responseValue) && empty($responseValue)) || trim((string)$responseValue) === '') {
        return new JsonResponse(['success' => false, 'message' => 'No answer provided. Skipping save.']);
    }

    try {
        // Decrypt the examId
        $examId = $this->decryptExamToken($examId);

        // Fetch the entities
        $student = $this->entityManager->getRepository(Student::class)->find($studentId);
        if (!$student) {
            return new JsonResponse(['success' => false, 'message' => 'Student not found.'], 404);
        }
        $question = $this->entityManager->getRepository(Question::class)->find($questionId);
        if (!$question) {
            return new JsonResponse(['success' => false, 'message' => 'Question not found.'], 404);
        }
        $exam = $this->entityManager->getRepository(Exam::class)->find($examId);
        if (!$exam) {
            return new JsonResponse(['success' => false, 'message' => 'Exam not found.'], 404);
        }
        $questionType = $question->getQuestionType()->getName();

        // Determine correct answers and student's response handling
        $correctAnswers = [];
        $responsesToSave = [];

        switch ($questionType) {
                case 'Radio-Button':
                case 'German':
                case 'Images':
                case 'Dropdown':
                    // Preprocess response value
                    $responseValue = strtoupper(trim(preg_replace('/[^\w]/', '', $responseValue)));
                
                    // Fetch the correct answer
                    $optionTable = $this->getOptionTableRepository($questionType);
                    $option = $optionTable->findOneBy(['Question' => $question]);
                    $correctAnswer = $option ? strtoupper(trim(preg_replace('/[^\w]/', '', $option->getCorrectAnswer()))) : null;
                
                    // Check if correct answer is not empty
                    if (empty($correctAnswer)) {
                        $isCorrect = false;
                    } else {
                        $isCorrect = $responseValue === $correctAnswer;
                    }
                
                    $responsesToSave[] = [
                        'Response' => $responseValue,
                        'Detail' => 0,
                        'IsCorrect' => $isCorrect,
                    ];
                    break;
                
                
            case 'Boolean':
                // Assuming the method to get the correct answer is 'isCorrectAnswer'
                $optionTable = $this->getOptionTableRepository($questionType);
                $option = $optionTable->findOneBy(['Question' => $question]);
                $correctAnswer = $option ? $option->isCorrectAnswer() : null;

                // Normalize the response as boolean (if it's coming as a string)
                $isCorrect = (bool)$responseValue === (bool)$correctAnswer;
                $responsesToSave[] = [
                    'Response' => (bool)$responseValue,
                    'Detail' => 0,
                    'IsCorrect' => $isCorrect,
                ];
                break;

            case 'Register':
                // Split the response string into an array of words
                $responseArray = preg_split('/\W+/', strtoupper($responseValue), -1, PREG_SPLIT_NO_EMPTY);

                // Fetch multiple correct answers
                $optionTable = $this->getOptionTableRepository($questionType);
                $option = $optionTable->findOneBy(['Question' => $question]);
                $correctAnswers = $option ? array_map(fn($value) => strtoupper(str_replace(' ', '', $value)), $option->getCorrectAnswers()) : [];

                // Save each word in the response array separately
                foreach ($responseArray as $index => $word) {
                    $responsesToSave[] = [
                        'Response' => $word,
                        'Detail' => $index,
                        'IsCorrect' => in_array($word, $correctAnswers),
                    ];
                }
                break; 

               case 'Check-Box':
    // Fetch multiple correct answers
    $optionTable = $this->getOptionTableRepository($questionType);
    $option = $optionTable->findOneBy(['Question' => $question]);
    $correctAnswers = $option ? array_map(fn($value) => strtoupper(str_replace(' ', '', $value)), $option->getCorrectAnswers()) : [];

    // Split the response string into individual values
    $responseValues = array_map('trim', explode(',', $responseValue));

    // Initialize scores
    $scores = array_fill(0, count($responseValues), 0);

    // Iterate through response values and correct answers
    foreach ($responseValues as $index => $answer) {
        if (isset($correctAnswers[$index]) && $answer === $correctAnswers[$index]) {
            $scores[$index] = 1;
        }
    }

    // Save response data
    foreach ($responseValues as $index => $answer) {
        $responsesToSave[] = [
            'Response' => $answer,
            'Detail' => $index,
            'IsCorrect' => $scores[$index] === 1,
            'Score' => $scores[$index],
        ];
    }
    break;

      default:
            return new JsonResponse(['success' => false, 'message' => 'Unsupported question type.'], 400);
    }

    // Save responses in the database
    foreach ($responsesToSave as $responseData) {
        $response = $this->entityManager->getRepository(Responses::class)->findOneBy([
            'Student' => $student,
            'Question' => $question,
            'Exam' => $exam,
            'detail' => $responseData['Detail'],
        ]);

        if (!$response) {
            $response = new Responses();
            $response->setStudent($student);
            $response->setQuestion($question);
            $response->setExam($exam);
        }

        $response->setResponse($responseData['Response']);
        $response->setDetail($responseData['Detail']);
        $response->setIscorrect($responseData['IsCorrect']);
        $this->entityManager->persist($response);
    }

    $this->entityManager->flush();

    return new JsonResponse([
        'success' => true,
        'message' => 'Response saved successfully.',
    ]);
} catch (\Exception $e) {
    error_log("Exception in saveResponse: " . $e->getMessage());
    error_log($e->getTraceAsString());

    return new JsonResponse(['success' => false, 'message' => 'Failed to save response.'], 500);
}

}


    
    


    
    /**
     * Get the repository for the correct option table based on question type.
     */

    private function getOptionTableRepository(string $questionType)
    {
        switch ($questionType) {
            case 'Radio-Button':
                return $this->entityManager->getRepository(RadioOption::class);
            case 'Check-Box':
                return $this->entityManager->getRepository(CheckboxOption::class);
            case 'German':
                return $this->entityManager->getRepository(GermanOption::class);
            case 'Boolean':
                return $this->entityManager->getRepository(BooleanOption::class);
            case 'Images':
                return $this->entityManager->getRepository(ImagesOption::class);
            case 'Dropdown':
                return $this->entityManager->getRepository(DropdownOption::class);
            case 'Register':
                return $this->entityManager->getRepository(RegisterOption::class);
            default:
                throw new \InvalidArgumentException("Unsupportedd  question type: $questionType");
        }
    }
    
    




    // Route to get a student's saved response
    #[Route('/student/get-response', name: 'student_get_response', methods: ['GET'])]
    public function getResponse(Request $request): JsonResponse
    {
        // Start session if not already started
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['student'])) {
            return new JsonResponse(['success' => false, 'message' => 'Unauthorized access.'], 401);
        }

        $studentId = $_SESSION['student']['id']; // Retrieve student ID from session
        $questionId = $request->query->get('questionId');
        $examId = $request->query->get('examId');

        if (!$questionId || !$examId) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid data provided.'], 400);
        }

        $response = $this->entityManager->getRepository(Response::class)->findOneBy([
            'studentId' => $studentId,
            'questionId' => $questionId,
            'examId' => $examId,
        ]);

        $answer = $response ? $response->getAnswer() : null;

        return new JsonResponse(['success' => true, 'answer' => $answer]);
    }





#[Route('/student/examtheory/{examToken}', name: 'student_examtheory', methods: ['GET'])]
public function examTheory(string $examToken, ManagerRegistry $doctrine): Response
{
    // Start session if not already started
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['student'])) {
        return $this->redirectToRoute('student_login_form');
    }

    $student = $_SESSION['student'];

    // Decrypt the exam token
    $examId = $this->decryptExamToken($examToken); // Implement your decrypt logic

    // Fetch the exam details
    $examRepository = $doctrine->getRepository(Exam::class);
    $exam = $examRepository->find($examId);

    if (!$exam) {
        throw $this->createNotFoundException('Exam not found.');
    }

    // Ensure the exam matches the student's class and other validations
    if ($exam->getClassroom()->getId() !== $student['classroom']['id']) {
        return $this->redirectToRoute('student_login_form');
    }

    // Load theory-specific data and render the template
    return $this->render('student/examtheory.html.twig', [
        'exam' => $exam,
    ]);
}



#[Route('/student/examresult/{examToken}', name: 'student_examresult', methods: ['GET'])]
public function examResult(string $examToken, ManagerRegistry $doctrine): Response
{
    // Start session if not already started
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['student'])) {
        return $this->redirectToRoute('student_login_form');
    }

    $student = $_SESSION['student'];

    // Decrypt the exam token
    $examId = $this->decryptExamToken($examToken); // Implement your decrypt logic

    // Fetch the exam details
    $examRepository = $doctrine->getRepository(Exam::class);
    $exam = $examRepository->find($examId);

    if (!$exam) {
        throw $this->createNotFoundException('Exam not found.');
    }

    // Ensure the exam matches the student's class and other validations
    if ($exam->getClassroom()->getId() !== $student['classroom']['id']) {
        return $this->redirectToRoute('student_login_form');
    }

    // Load theory-specific data and render the template
    return $this->render('student/examresult.html.twig', [
        'exam' => $exam,
    ]);
}













}
