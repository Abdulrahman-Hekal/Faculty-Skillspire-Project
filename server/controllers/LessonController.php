<?php

class LessonController extends Controller
{
    private $courseModel;
    private $lessonModel;

    public function __construct()
    {
        // Removed instructor check from constructor to allow student access to index method
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->courseModel = $this->requireModel('CoursesModel');
        $this->lessonModel = $this->requireModel('LessonsModel');
    }

    public function index($courseId, $lessonId = null)
    {
        // 1. Check Enrollment
        $enrollmentModel = $this->requireModel('EnrollmentsModel');
        if (!$enrollmentModel->isEnrolled($_SESSION['user_id'], $courseId)) {
             // Allow instructor to view their own course lessons? For now, strict check.
             // Or check if user is instructor of this course.
             $course = $this->courseModel->getCourseById($courseId);
             if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/course/index/' . $courseId);
                exit;
             }
        }

        // 2. Fetch Data
        $course = $this->courseModel->getCourseById($courseId);
        $lessons = $this->lessonModel->getLessonsByCourseId($courseId);

        if (empty($lessons)) {
            // No lessons yet
            header('Location: ' . BASE_URL . '/course/index/' . $courseId);
            exit;
        }

        // 3. Determine Current Lesson
        $currentLesson = null;
        if ($lessonId) {
            foreach ($lessons as $lesson) {
                if ($lesson['id'] == $lessonId) {
                    $currentLesson = $lesson;
                    break;
                }
            }
        }
        
        // Default to first lesson if not found or not specified
        if (!$currentLesson) {
            $currentLesson = $lessons[0];
        }

        $data = [
            'course' => $course,
            'lessons' => $lessons,
            'current_lesson' => $currentLesson
        ];

        $this->requireView('lesson/lesson', $data);
    }

    public function create($courseId)
    {
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $this->requireView('dashboard/lessons/create', ['course' => $course]);
    }

    public function store($courseId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $course = $this->courseModel->getCourseById($courseId);
            if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'course_id' => $courseId,
                'title' => trim($_POST['title']),
                'video_url' => trim($_POST['video_url']), // URL input fallback
                'content' => trim($_POST['content']),
                'order' => trim($_POST['order']),
                'title_err' => '',
                'video_url_err' => '',
                'order_err' => ''
            ];

            // File Upload Logic
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

    public function edit($lessonId)
    {
        $lesson = $this->lessonModel->getLessonById($lessonId);
        $course = $this->courseModel->getCourseById($lesson['course_id']);

        if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $this->requireView('dashboard/lessons/edit', ['lesson' => $lesson, 'course' => $course]);
    }

    public function update($lessonId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lesson = $this->lessonModel->getLessonById($lessonId);
            $course = $this->courseModel->getCourseById($lesson['course_id']);

            if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }

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

             // File Upload Logic
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
            } else {
                // If no file uploaded, keep old one if URL field is empty or user wants to keep it
                // Logic: If URL field has value, use it. If not and no file, keep old value?
                // Actually the form populates input with old value. So if they cleared it, it's empty.
                if (empty($data['video_url'])) {
                     $data['video_url'] = $lesson['video_url'];
                }
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

    public function delete($lessonId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lesson = $this->lessonModel->getLessonById($lessonId);
            $course = $this->courseModel->getCourseById($lesson['course_id']);

            if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }

            if ($this->lessonModel->deleteLesson($lessonId)) {
                header('Location: ' . BASE_URL . '/dashboard/edit/' . $course['id']);
            } else {
                die('Something went wrong');
            }
        }
    }
}