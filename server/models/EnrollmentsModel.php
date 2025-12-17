<?php

/**
 * EnrollmentsModel
 * Handles student enrollments and progress tracking.
 */
class EnrollmentsModel extends Model
{
    /**
     * Enroll a user in a course.
     * 
     * @param int $userId
     * @param int $courseId
     * @return bool|mysqli_result
     */
    public function enrollUser($userId, $courseId)
    {
        $exists = $this->isEnrolled($userId, $courseId);
        if ($exists) return false;

        $query = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
        return $this->conn->execute_query($query, [$userId, $courseId]);
    }

    /**
     * Check if a user is enrolled in a course.
     * 
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public function isEnrolled($userId, $courseId)
    {
        $query = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
        $result = $this->conn->execute_query($query, [$userId, $courseId]);
        return $result->num_rows > 0;
    }

    /**
     * Get all courses a user is enrolled in, including progress.
     * 
     * @param int $userId
     * @return array
     */
    public function getEnrolledCourses($userId)
    {
        $query = "SELECT c.*, e.enrolled_at, e.progress FROM courses c 
                  JOIN enrollments e ON c.id = e.course_id 
                  WHERE e.user_id = ?";
        return $this->conn->execute_query($query, [$userId])->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Update course progress for a user.
     * Keeps the highest progress value.
     * 
     * @param int $userId
     * @param int $courseId
     * @param int $progress
     * @return bool|mysqli_result
     */
    public function updateProgress($userId, $courseId, $progress)
    {
        $query = "UPDATE enrollments SET progress = GREATEST(COALESCE(progress, 0), ?) WHERE user_id = ? AND course_id = ?";
        return $this->conn->execute_query($query, [$progress, $userId, $courseId]);
    }

    /**
     * Get statistics for a user's enrollments.
     * 
     * @param int $userId
     * @return array
     */
    public function getEnrollmentStats($userId)
    {
        $query = "SELECT 
                    COUNT(*) as total_enrolled,
                    SUM(CASE WHEN progress >= 100 THEN 1 ELSE 0 END) as total_completed
                  FROM enrollments 
                  WHERE user_id = ?";
        $result = $this->conn->execute_query($query, [$userId])->fetch_assoc();
        
        return [
            'total_enrolled' => $result['total_enrolled'] ?? 0,
            'total_completed' => $result['total_completed'] ?? 0
        ];
    }
}
