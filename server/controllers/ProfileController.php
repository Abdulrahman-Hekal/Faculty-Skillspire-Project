<?php

/**
 * ProfileController
 * Handles user profile display and updates.
 */
class ProfileController extends Controller
{
    private $userModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->userModel = $this->requireModel('UsersModel');
    }

    /**
     * Display profile page.
     */
    public function index()
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        $stats = [
            'total_enrolled' => 0,
            'total_completed' => 0
        ];

        if ($user['role'] == 'student') {
            $enrollmentsModel = $this->requireModel('EnrollmentsModel');
            $stats = $enrollmentsModel->getEnrollmentStats($_SESSION['user_id']);
        }

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'stats' => $stats,
            'name_err' => '',
            'email_err' => '',
            'current_password_err' => '',
            'password_err' => '',
            'confirm_password_err' => ''
        ];

        $this->requireView('profile/profile', $data);
    }

    /**
     * Update profile information.
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $currentUser = $this->userModel->getUserById($_SESSION['user_id']);

            $data = [
                'title' => 'My Profile',
                'user' => $currentUser,
                'name' => trim($_POST['name']),
                'email' => $currentUser['email'],
                'current_password' => trim($_POST['current_password']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'current_password_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate Password Changes
            if (!empty($data['password'])) {
                // Must provide current password
                if (empty($data['current_password'])) {
                    $data['current_password_err'] = 'Please enter current password to change it';
                } elseif (!password_verify($data['current_password'], $data['user']['password'])) {
                    $data['current_password_err'] = 'Incorrect password';
                }

                if (strlen($data['password']) < 6) {
                    $data['password_err'] = 'Password must be at least 6 characters';
                }
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['current_password_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // Prepare update data
                $updateData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : ''
                ];

                if ($this->userModel->updateUser($_SESSION['user_id'], $updateData)) {
                    // Update Session logic
                    $_SESSION['user_name'] = $data['name'];
                    // Email shouldn't change
                    
                    header('Location: ' . BASE_URL . '/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->requireView('profile/profile', $data);
            }
        } else {
            $this->index();
        }
    }
}
