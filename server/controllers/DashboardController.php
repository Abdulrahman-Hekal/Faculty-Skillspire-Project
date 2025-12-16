<?php

class DashboardController extends Controller
{
    private $courseModel;

    public function __construct()
    {
        // specific check for instructor role
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->courseModel = $this->requireModel('CoursesModel');
    }

    public function index()
    {
        $courses = $this->courseModel->getInstructorCourses($_SESSION['user_id']);
        $this->requireView('dashboard/dashboard', ['courses' => $courses]);
    }

    public function create()
    {
        $this->requireView('dashboard/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'instructor_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'category' => trim($_POST['category']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'thumbnail' => '',
                'title_err' => '',
                'category_err' => '',
                'price_err' => '',
                'description_err' => ''
            ];

            // Validate inputs
            if (empty($data['title'])) $data['title_err'] = 'Please enter title';
            if (empty($data['category'])) $data['category_err'] = 'Please select category';
            if (empty($data['price'])) $data['price_err'] = 'Please enter price';
            if (empty($data['description'])) $data['description_err'] = 'Please enter description';

            // Handle File Upload
            if (!empty($_FILES['thumbnail']['name'])) {
                $targetDir = "public/uploads/thumbnails/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = basename($_FILES["thumbnail"]["name"]);
                $targetFilePath = $targetDir . time() . "_" . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFilePath)) {
                        $data['thumbnail'] = BASE_URL . '/' . $targetFilePath;
                    } else {
                        $data['thumbnail_err'] = "File upload failed";
                    }
                } else {
                    $data['thumbnail_err'] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
                }
            }

            if (empty($data['title_err']) && empty($data['category_err']) && empty($data['price_err']) && empty($data['description_err']) && empty($data['thumbnail_err'])) {
                if ($this->courseModel->createCourse($data)) {
                    header('Location: ' . BASE_URL . '/dashboard');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->requireView('dashboard/create', $data);
            }
        } else {
            $this->create();
        }
    }

    public function edit($id)
    {
        $course = $this->courseModel->getCourseById($id);

        // Check owner
        if ($course['instructor_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $lessons = $this->requireModel('LessonsModel')->getLessonsByCourseId($id);

        $this->requireView('dashboard/edit', ['course' => $course, 'lessons' => $lessons]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $course = $this->courseModel->getCourseById($id);
            if ($course['instructor_id'] != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'category' => trim($_POST['category']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'thumbnail' => $course['thumbnail'],
                'is_published' => $course['is_published'],
                'title_err' => '',
                'category_err' => '',
                'price_err' => '',
                'description_err' => ''
            ];

            if (empty($data['title'])) $data['title_err'] = 'Please enter title';
            if (empty($data['category'])) $data['category_err'] = 'Please select category';
            if (empty($data['price'])) $data['price_err'] = 'Please enter price';
            if (empty($data['description'])) $data['description_err'] = 'Please enter description';

            // Handle File Upload if new file
            if (!empty($_FILES['thumbnail']['name'])) {
                $targetDir = "public/uploads/thumbnails/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = basename($_FILES["thumbnail"]["name"]);
                $targetFilePath = $targetDir . time() . "_" . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFilePath)) {
                        $data['thumbnail'] = BASE_URL . '/' . $targetFilePath;
                    }
                }
            }

            if (empty($data['title_err']) && empty($data['category_err']) && empty($data['price_err']) && empty($data['description_err'])) {
                if ($this->courseModel->updateCourse($id, $data)) {
                    header('Location: ' . BASE_URL . '/dashboard');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->requireView('dashboard/edit', ['course' => $data]);
            }
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $course = $this->courseModel->getCourseById($id);
            
            if ($course['instructor_id'] != $_SESSION['user_id']) {
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }

            if ($this->courseModel->deleteCourse($id)) {
                header('Location: ' . BASE_URL . '/dashboard');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function togglePublish($id)
    {
        $course = $this->courseModel->getCourseById($id);
        
        if ($course['instructor_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $data = $course;
        $data['is_published'] = $course['is_published'] ? 0 : 1;

        if ($this->courseModel->updateCourse($id, $data)) {
            header('Location: ' . BASE_URL . '/dashboard');
        } else {
            die('Something went wrong');
        }
    }
}