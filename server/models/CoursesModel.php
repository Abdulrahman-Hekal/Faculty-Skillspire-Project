<?php

/**
 * CoursesModel
 * Handles course management, retrieval, and filtering.
 */
class CoursesModel extends Model
{
    /**
     * Get published courses with optional filtering and pagination.
     * 
     * @param int $page
     * @param int $limit
     * @param array $filters
     * @return array
     */
    public function getPublicCourses($page = 1, $limit = 10, $filters = [])
    {
        $query = "SELECT c.*, u.name as instructor_name, 
                  COALESCE(AVG(r.rating), 0) as avg_rating,
                  COUNT(r.id) as reviews_count
                  FROM courses c 
                  JOIN users u ON c.instructor_id = u.id 
                  LEFT JOIN reviews r ON c.id = r.course_id 
                  WHERE c.is_published = 1";
        $params = [];

        // WHERE Filters
        if (!empty($filters['category'])) {
            $query .= " AND c.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (c.title LIKE ? OR c.description LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query .= " AND c.price >= ?";
            $params[] = $filters['min_price'];
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query .= " AND c.price <= ?";
            $params[] = $filters['max_price'];
        }

        // GROUP BY
        $query .= " GROUP BY c.id";

        // HAVING Filters (Rating)
        if (isset($filters['min_rating']) && $filters['min_rating'] !== '') {
            $query .= " HAVING avg_rating >= ?";
            $params[] = $filters['min_rating'];
        }

        // ORDER BY
        $sort_by = $filters['sort_by'] ?? 'created_at';
        $sort_dir = strtoupper($filters['sort_dir'] ?? 'DESC');
        $allowedSorts = ['price', 'created_at', 'title', 'avg_rating'];
        $allowedDirs = ['ASC', 'DESC'];

        if (!in_array($sort_by, $allowedSorts)) $sort_by = 'created_at';
        if (!in_array($sort_dir, $allowedDirs)) $sort_dir = 'DESC';

        $query .= " ORDER BY {$sort_by} {$sort_dir}";

        // LIMIT / OFFSET
        $offset = ($page - 1) * $limit;
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->conn->execute_query($query, $params)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get the total count of published courses for pagination.
     * 
     * @param array $filters
     * @return int
     */
    public function getTotalPublicCourses($filters = [])
    {
        $query = "SELECT c.id, COALESCE(AVG(r.rating), 0) as avg_rating 
                  FROM courses c 
                  LEFT JOIN reviews r ON c.id = r.course_id 
                  WHERE c.is_published = 1";
        $params = [];

        if (!empty($filters['category'])) {
            $query .= " AND c.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (c.title LIKE ? OR c.description LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query .= " AND c.price >= ?";
            $params[] = $filters['min_price'];
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query .= " AND c.price <= ?";
            $params[] = $filters['max_price'];
        }

        $query .= " GROUP BY c.id";

        if (isset($filters['min_rating']) && $filters['min_rating'] !== '') {
            $query .= " HAVING avg_rating >= ?";
            $params[] = $filters['min_rating'];
        }

        // Wrap in count using a subquery to count the groups
        $finalQuery = "SELECT COUNT(*) as total FROM ({$query}) as subquery";

        return $this->conn->execute_query($finalQuery, $params)->fetch_assoc()['total'];
    }

    /**
     * Get all courses for a specific instructor.
     * 
     * @param int $instructorId
     * @return array
     */
    public function getInstructorCourses($instructorId)
    {
        return $this->conn->execute_query("SELECT * FROM courses WHERE instructor_id = ?", [$instructorId])->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get detailed information for a single course.
     * Includes instructor name, student count, average rating, and review count.
     * 
     * @param int $courseId
     * @return array|null
     */
    public function getCourseDetails($courseId)
    {
        $query = "SELECT c.*, u.name as instructor_name, 
                  (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as students_count,
                  COALESCE(AVG(r.rating), 0) as avg_rating,
                  COUNT(r.id) as reviews_count
                  FROM courses c 
                  JOIN users u ON c.instructor_id = u.id 
                  LEFT JOIN reviews r ON c.id = r.course_id 
                  WHERE c.id = ? 
                  GROUP BY c.id";
        return $this->conn->execute_query($query, [$courseId])->fetch_assoc();
    }

    /**
     * Get basic course information by ID.
     * 
     * @param int $courseId
     * @return array|null
     */
    public function getCourseById($courseId)
    {
        return $this->conn->execute_query("SELECT * FROM courses WHERE id = ?", [$courseId])->fetch_assoc();
    }

    /**
     * Create a new course.
     * 
     * @param array $data
     * @return int Insert ID
     */
    public function createCourse($data)
    {
        $query = "INSERT INTO courses (instructor_id, title, category, description, price, thumbnail, is_published) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->conn->execute_query($query, [
            $data['instructor_id'],
            $data['title'],
            $data['category'],
            $data['description'],
            $data['price'],
            $data['thumbnail'],
            $data['is_published'] ?? 0
        ]);
        return $this->conn->insert_id;
    }

    /**
     * Update an existing course.
     * 
     * @param int $courseId
     * @param array $data
     * @return bool|mysqli_result
     */
    public function updateCourse($courseId, $data)
    {
        $query = "UPDATE courses SET title = ?, category = ?, description = ?, price = ?, thumbnail = ?, is_published = ? WHERE id = ?";
        return $this->conn->execute_query($query, [
            $data['title'],
            $data['category'],
            $data['description'],
            $data['price'],
            $data['thumbnail'],
            $data['is_published'],
            $courseId
        ]);
    }

    /**
     * Delete a course.
     * 
     * @param int $courseId
     * @return bool|mysqli_result
     */
    public function deleteCourse($courseId)
    {
        return $this->conn->execute_query("DELETE FROM courses WHERE id = ?", [$courseId]);
    }
}