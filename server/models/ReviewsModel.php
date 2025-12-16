<?php

class ReviewsModel extends Model
{
    public function createReview($data)
    {
        $query = "INSERT INTO reviews (user_id, course_id, rating, comment) VALUES (?, ?, ?, ?)";
        return $this->conn->execute_query($query, [
            $data['user_id'],
            $data['course_id'],
            $data['rating'],
            $data['comment']
        ]);
    }

    public function getReviewsByCourseId($courseId)
    {
        $query = "SELECT r.*, u.name as reviewer_name FROM reviews r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.course_id = ?
                  ORDER BY r.created_at DESC";
        return $this->conn->execute_query($query, [$courseId])->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAverageRating($courseId)
    {
         $query = "SELECT AVG(rating) as avg_rating FROM reviews WHERE course_id = ?";
         $result = $this->conn->execute_query($query, [$courseId])->fetch_assoc();
         return $result['avg_rating'] ?? 0;
    }

    public function hasReviewed($userId, $courseId)
    {
        $query = "SELECT id FROM reviews WHERE user_id = ? AND course_id = ?";
        return $this->conn->execute_query($query, [$userId, $courseId])->num_rows > 0;
    }
}
