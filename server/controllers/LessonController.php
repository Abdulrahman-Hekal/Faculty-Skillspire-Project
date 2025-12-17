<?php

/**
 * LessonController
 * Handles lesson management (CRUD) for instructors and lesson viewing for students.
 */
class LessonController extends Controller
{
    private $courseModel;
    private $lessonModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->courseModel = $this->requireModel('CoursesModel');
        $this->lessonModel = $this->requireModel('LessonsModel');
    }

    /**
     * Display the lesson viewer for a student (or instructor).
     *
     * @param int $courseId
     * @param int|null $lessonId
     */
    public function index($courseId, $lessonId = null)
    {
        // 1. Check Enrollment or Ownership
        $enrollmentModel = $this->requireModel('EnrollmentsModel');
        $isEnrolled = $enrollmentModel->isEnrolled($_SESSION['user_id'], $courseId);
        
        $course = $this->courseModel->getCourseById($courseId);
        $isInstructor = ($course && $course['instructor_id'] == $_SESSION['user_id']);

        if (!$isEnrolled && !$isInstructor) {
             header('Location: ' . BASE_URL . '/course/index/' . $courseId);
             exit;
        }

        // 2. Fetch Data
        $lessons = $this->lessonModel->getLessonsByCourseId($courseId);

        if (empty($lessons)) {
            header('Location: ' . BASE_URL . '/course/index/' . $courseId);
            exit;
        }

        // 3. Determine Current Lesson
        $currentLesson = null;
        $currentIndex = 0;
        if ($lessonId) {
            foreach ($lessons as $index => $lesson) {
                if ($lesson['id'] == $lessonId) {
                    $currentLesson = $lesson;
                    $currentIndex = $index;
                    break;
                }
            }
        }
        
        // Default to first lesson if not found or not specified
        if (!$currentLesson) {
            $currentLesson = $lessons[0];
            $currentIndex = 0;
        }

        // 4. Update Progress (only for students)
        $totalLessons = count($lessons);
        $progressPercent = ($totalLessons > 0) ? round((($currentIndex + 1) / $totalLessons) * 100) : 0;
        
        if ($isEnrolled) {
            $enrollmentModel->updateProgress($_SESSION['user_id'], $courseId, $progressPercent);
        }

        $data = [
            'course' => $course,
            'lessons' => $lessons,
            'current_lesson' => $currentLesson,
            'progress_percent' => $progressPercent
        ];

        $this->requireView('lesson/lesson', $data);
    }

    /**
     * Show form to create a new lesson.
     *
     * @param int $courseId
     */
    public function create($courseId)
    {
        $course = $this->ensureInstructorForCourse($courseId);
        $this->requireView('dashboard/lessons/create', ['course' => $course]);
    }

    /**
     * Store a new lesson.
     *
     * @param int $courseId
     */
    public function store($courseId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $course = $this->ensureInstructorForCourse($courseId);

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'course_id' => $courseId,
                'title' => trim($_POST['title']),
                'video_url' => trim($_POST['video_url']),
                'content' => trim($_POST['content']),
                'order' => trim($_POST['order']),
                'title_err' => '',
                'video_url_err' => '',
                'order_err' => ''
            ];

            // Handle Video Upload
            $this->handleVideoUpload($data);

            if (empty($data['title'])) $data['title_err'] = 'Please enter title';
            if (empty($data['video_url'])) $data['video_url_err'] = 'Please provide a video URL or upload a file';
            if (empty($data['order'])) $data['order_err'] = 'Please enter order number';

            if (empty($data['title_err']) && empty($data['video_url_err']) && empty($data['order_err'])) {
                if ($this->lessonModel->createLesson($data)) {
                    header('Location: ' . BASE_URL . '/dashboard/edit/' . $courseId);
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->requireView('dashboard/lessons/create', ['course' => $course, 'data' => $data]);
            }
        }
    }

    /**
     * Show form to edit a lesson.
     *
     * @param int $lessonId
     */
    public function edit($lessonId)
    {
        $lesson = $this->lessonModel->getLessonById($lessonId);
        if (!$lesson) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $course = $this->ensureInstructorForCourse($lesson['course_id']);
        $this->requireView('dashboard/lessons/edit', ['lesson' => $lesson, 'course' => $course]);
    }

    /**
     * Update an existing lesson.
     *
     * @param int $lessonId
     */
    public function update($lessonId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lesson = $this->lessonModel->getLessonById($lessonId);
            if (!$lesson) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }
            $course = $this->ensureInstructorForCourse($lesson['course_id']);

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'title' => trim($_POST['title']),
                'video_url' => trim($_POST['video_url']),
                'content' => trim($_POST['content']),
                'order' => trim($_POST['order']),
                'lesson' => $lesson,
                'title_err' => '',
                'video_url_err' => '',
                'order_err' => ''
            ];

            // Handle Video Upload
            $this->handleVideoUpload($data);
            
            // Retain old URL if not changed
            if (empty($data['video_url'])) {
                $data['video_url'] = $lesson['video_url'];
            }

            if (empty($data['title'])) $data['title_err'] = 'Please enter title';
            if (empty($data['video_url'])) $data['video_url_err'] = 'Please enter video URL';
            if (empty($data['order'])) $data['order_err'] = 'Please enter order number';

            if (empty($data['title_err']) && empty($data['video_url_err']) && empty($data['order_err'])) {
                if ($this->lessonModel->updateLesson($lessonId, $data)) {
                    header('Location: ' . BASE_URL . '/dashboard/edit/' . $course['id']);
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->requireView('dashboard/lessons/edit', ['lesson' => $lesson, 'course' => $course, 'data' => $data]);
            }
        }
    }

    /**
     * Delete a lesson.
     *
     * @param int $lessonId
     */
    public function delete($lessonId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lesson = $this->lessonModel->getLessonById($lessonId);
            if (!$lesson) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }
            $course = $this->ensureInstructorForCourse($lesson['course_id']);

            if ($this->lessonModel->deleteLesson($lessonId)) {
                header('Location: ' . BASE_URL . '/dashboard/edit/' . $course['id']);
            } else {
                die('Something went wrong');
            }
        }
    }

    // --- Helper Methods ---

    /**
     * Ensure the current user is the instructor of the course.
     * Returns course data if valid, otherwise redirects.
     */
    private function ensureInstructorForCourse($courseId)
    {
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        return $course;
    }

    /**
     * Handle video file upload logic.
     * Modifies the $data array by reference.
     */
    private function handleVideoUpload(&$data)
    {
        if (!empty($_FILES['video_file']['name'])) {
            $targetDir = "public/uploads/videos/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = basename($_FILES["video_file"]["name"]);
            $targetFilePath = $targetDir . time() . "_" . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            
            $allowTypes = array('mp4', 'webm', 'ogg');
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES["video_file"]["tmp_name"], $targetFilePath)) {
                    $data['video_url'] = BASE_URL . '/' . $targetFilePath;
                } else {
                    $data['video_url_err'] = "File upload failed";
                }
            } else {
                $data['video_url_err'] = "Only MP4, WebM & OGG files are allowed.";
            }
        }
    }
}