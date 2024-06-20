<?php 
class Application {
    private $db;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }
    public function getFirstName($voter_id) {
        $connection = $this->db->connect();

        // Prepare the query with a placeholder for voter_id
        $query = "SELECT first_name FROM voter WHERE voter_id = ?";
        
        // Prepare the statement
        $statement = $connection->prepare($query);

        // Bind the parameter
        $statement->bind_param("i", $voter_id); // Assuming voter_id is an integer

        // Execute the query
        $statement->execute();

        // Get the result
        $result = $statement->get_result();

        // Fetch the first name
        $first_name = "Voter"; // Default value
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $first_name = $row['first_name'];
        }

        // Close the statement
        $statement->close();

        return $first_name;
    }
    public function getCandidateCount() {
        $connection = $this->db->connect();

        // Fetch count of candidates from the candidate table
        $candidateCountQuery = "SELECT COUNT(*) AS candidate_count FROM candidate";
        $result = $connection->query($candidateCountQuery);
        $candidateCount = $result->fetch_assoc()['candidate_count'];

        return $candidateCount;
    }
    public function getPositions() {
        $connection = $this->db->connect();

        $positionsQuery = "SELECT DISTINCT title FROM position";
        $result = $connection->query($positionsQuery);
        $positions = array();

        // Process the fetched rows into an array of positions
        while ($row = $result->fetch_assoc()) {
            $positions[] = $row['title'];
        }

        return $positions;
    }

    public function getFirstPosition() {
        $connection = $this->db->connect();

        $firstPositionQuery = "SELECT DISTINCT title FROM position LIMIT 1";
        $result = $connection->query($firstPositionQuery);
        $firstPosition = $result->num_rows > 0 ? $result->fetch_assoc()['title'] : "No positions available";
        return $firstPosition;
    }
    public function getVoterCounts() {
        $connection = $this->db->connect();
    
        // Fetch total count of voters
        $totalVotersQuery = "SELECT COUNT(*) AS total_count FROM voter WHERE role = 'student_voter'";
        $totalVotersResult = $connection->query($totalVotersQuery);
        $totalVotersCount = $totalVotersResult->fetch_assoc()['total_count'];
    
        // Fetch count of voters with voteStatus as 'voted'
        $votedVotersQuery = "SELECT COUNT(*) AS voted_count FROM voter WHERE vote_status = 'Voted'";
        $votedVotersResult = $connection->query($votedVotersQuery);
        $votedVotersCount = $votedVotersResult->fetch_assoc()['voted_count'];
        
        $abstainedVotersQuery = "SELECT COUNT(*) AS abstained_count FROM voter WHERE vote_status = 'Abstained'";
        $abstainedVotersResult = $connection->query($abstainedVotersQuery);
        $abstainedVotersCount = $abstainedVotersResult->fetch_assoc()['abstained_count'];
    
        // Calculate percentages
        $totalPercentage = ($totalVotersCount > 0) ? (($votedVotersCount / $totalVotersCount) * 100) : 0;
        $votedPercentage = 100 - $totalPercentage;
    
        // Return the counts and percentages as an array
        return [
            'totalVotersCount' => $totalVotersCount,
            'votedVotersCount' => $votedVotersCount,
            'abstainedVotersCount' => $abstainedVotersCount,
            'totalPercentage' => $totalPercentage,
            'votedPercentage' => $votedPercentage,
        ];
    }
    public function getYearLevelCounts() {
        $connection = $this->db->connect();

        $yearLevelQuery = "SELECT year_level, COUNT(*) AS count FROM voter GROUP BY year_level";
        $result = $connection->query($yearLevelQuery);
        $yearLevelCounts = array();

        // Process the fetched rows and assign counts to corresponding variables
        while ($row = $result->fetch_assoc()) {
            $yearLevel = intval($row['year_level']); // Convert year level to integer
            switch ($yearLevel) {
                case 1:
                    $yearLevelCounts['firstYearCount'] = $row['count'];
                    break;
                case 2:
                    $yearLevelCounts['secondYearCount'] = $row['count'];
                    break;
                case 3:
                    $yearLevelCounts['thirdYearCount'] = $row['count'];
                    break;
                case 4:
                    $yearLevelCounts['fourthYearCount'] = $row['count'];
                    break;
                default:
                    // Handle any other cases if needed
                    break;
            }
            
        }

        return $yearLevelCounts;
    }
}
?>