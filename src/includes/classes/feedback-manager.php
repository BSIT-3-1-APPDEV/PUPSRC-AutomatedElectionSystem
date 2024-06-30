<?php
// feedback_data.php

class FeedbackManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getFeedbackData($sort, $order, $offset, $records_per_page)
    {
        $query = "SELECT * FROM feedback ORDER BY $sort $order LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $offset, $records_per_page);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }

    public function getTotalRecords()
    {
        $query = "SELECT COUNT(*) as total FROM feedback";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_records = $result->fetch_assoc()['total'];
        $stmt->close();

        return $total_records;
    }
}
