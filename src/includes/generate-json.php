<?php
// Include necessary files
include_once 'classes/file-utils.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'classes/db-connector.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'session-handler.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'session-exchange.php';

// Establish database connection
$conn = DatabaseConnection::connect();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Function to fetch election schedule
function fetchElectionSchedule($conn)
{
    $sql = "SELECT * FROM election_schedule WHERE schedule_id = 0"; // Assuming schedule_id 0 is your election schedule
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Function to check if current time is past election close time
function isElectionClosed($closeDateTime)
{
    $currentTime = new DateTime();
    $closeTime = new DateTime($closeDateTime);
    return $currentTime > $closeTime;
}

// Function to fetch data from a table
function fetchData($conn, $table)
{
    $data = array();
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Function to fetch candidate count
function fetchCandidateCount($conn)
{
    $sql = "SELECT COUNT(*) AS candidate_count FROM candidate";
    $result = $conn->query($sql);
    $candidateCount = $result->fetch_assoc()['candidate_count'];
    return $candidateCount;
}

// Function to fetch voter counts and percentages
function fetchVoterCounts($conn)
{
    // Fetch total count of voters
    $totalVotersQuery = "SELECT COUNT(*) AS total_count FROM voter";
    $totalVotersResult = $conn->query($totalVotersQuery);
    $totalVotersCount = $totalVotersResult->fetch_assoc()['total_count'];

    // Fetch count of voters with vote status as 'voted'
    $votedVotersQuery = "SELECT COUNT(*) AS voted_count FROM voter WHERE vote_status = 'Voted'";
    $votedVotersResult = $conn->query($votedVotersQuery);
    $votedVotersCount = $votedVotersResult->fetch_assoc()['voted_count'];

    // Fetch count of voters with vote status as 'abstained'
    $abstainedVotersQuery = "SELECT COUNT(*) AS abstained_count FROM voter WHERE vote_status = 'Abstained'";
    $abstainedVotersResult = $conn->query($abstainedVotersQuery);
    $abstainedVotersCount = $abstainedVotersResult->fetch_assoc()['abstained_count'];

    // Calculate percentages
    $totalPercentage = ($totalVotersCount > 0) ? (($votedVotersCount / $totalVotersCount) * 100) : 0;
    $votedPercentage = 100 - $totalPercentage;

    return [
        'totalVotersCount' => $totalVotersCount,
        'votedVotersCount' => $votedVotersCount,
        'abstainedVotersCount' => $abstainedVotersCount,
        'totalPercentage' => $totalPercentage,
        'votedPercentage' => $votedPercentage,
    ];
}

// Function to fetch abstained vote count from the vote table
function fetchAbstainedVoteCount($conn)
{
    $sql = "SELECT COUNT(*) AS abstained_vote_count FROM vote WHERE candidate_id IS NULL";
    $result = $conn->query($sql);
    $abstainedVoteCount = $result->fetch_assoc()['abstained_vote_count'];
    return $abstainedVoteCount;
}

// Function to fetch feedback with limit and offset
function fetchFeedbackWithLimit($conn, $start_from, $limit)
{
    $feedback_data = array();
    $sql = "SELECT feedback, DATE(timestamp) AS feedback_date FROM feedback LIMIT $start_from, $limit";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $feedback_data[] = $row;
        }
    }
    return $feedback_data;
}

// Function to fetch all feedback for rating calculations
function fetchAllFeedback($conn)
{
    $feedback_data = array();
    $sql = "SELECT * FROM feedback";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $feedback_data[] = $row;
        }
    }
    return $feedback_data;
}

// Function to update vote counts for candidates using a query
function updateCandidateVoteCounts($conn, &$candidates)
{
    $sql = "SELECT candidate_id, COUNT(*) AS vote_count FROM vote GROUP BY candidate_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $candidate_id = $row['candidate_id'];
            if (isset($candidates[$candidate_id])) {
                $candidates[$candidate_id]['vote_count'] = $row['vote_count'];
            }
        }
    }
}

// Function to fetch position titles and add to candidates data
function fetchPositionTitles(&$candidates, $positions)
{
    foreach ($candidates as &$candidate) {
        $position_id = $candidate['position_id'];
        // Find the position title based on position_id
        foreach ($positions as $position) {
            if ($position['position_id'] == $position_id) {
                $candidate['position_title'] = $position['title'];
                break;
            }
        }
        // Check if position_title is not set, assign a default value for debugging
        if (!isset($candidate['position_title'])) {
            $candidate['position_title'] = 'Unknown Position';
        }
    }
}

// Function to count feedback ratings and calculate percentages
function countFeedbackRatings($feedback)
{
    $rating_count = array();
    $total_feedback = count($feedback);

    // Count the ratings
    foreach ($feedback as $entry) {
        $rating = $entry['rating'];
        if (!isset($rating_count[$rating])) {
            $rating_count[$rating] = 0;
        }
        $rating_count[$rating]++;
    }

    // Calculate the percentages
    $rating_percentage = array();
    foreach ($rating_count as $rating => $count) {
        $rating_percentage[$rating] = round(($count / $total_feedback) * 100);
    }

    return array('count' => $rating_count, 'percentage' => $rating_percentage);
}

// Function to run fetching operations and store data if election is closed
function fetchAndStoreData()
{
    // Establish database connection
    $conn = DatabaseConnection::connect();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch election schedule
    $schedule = fetchElectionSchedule($conn);

    if ($schedule && isElectionClosed($schedule['close'])) {
        // List of tables to fetch data from
        $tables = ['candidate', 'attempt_logs', 'position', 'vote', 'voter'];

        // Fetch data from each table
        $database_data = array();
        foreach ($tables as $table) {
            $database_data[$table] = fetchData($conn, $table);
        }

        // Fetch candidate count
        $database_data['candidate_count'] = fetchCandidateCount($conn);

        // Fetch voter counts and percentages
        $database_data['voter_counts'] = fetchVoterCounts($conn);

        // Fetch abstained vote count
        $database_data['abstained_vote_count'] = fetchAbstainedVoteCount($conn);

        // Fetch feedback data with limit and offset
        $start_from = 0; // Starting point for feedback fetch
        $limit = 10; // Number of feedback entries to fetch
        $database_data['feedback'] = fetchFeedbackWithLimit($conn, $start_from, $limit);

        // Fetch all feedback data for rating calculations
        $all_feedback_data = fetchAllFeedback($conn);

        // Update vote counts in candidates data using the query
        updateCandidateVoteCounts($conn, $database_data['candidate']);

        // Fetch position titles
        fetchPositionTitles($database_data['candidate'], $database_data['position']);

        // Count feedback ratings and calculate percentages
        $rating_data = countFeedbackRatings($all_feedback_data);
        $database_data['rating_count'] = $rating_data['count'];
        $database_data['rating_percentage'] = $rating_data['percentage'];

        // Convert the database data to JSON format
        $json_data = json_encode($database_data, JSON_PRETTY_PRINT);

        // Specify the directory and file name
        $directory = __DIR__ . '/../includes/data';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $file = $directory . '/voters-turnout.json';

        // Save the JSON data to a file
        if (file_put_contents($file, $json_data)) {
            // Redirect to result-generation.php upon successful JSON creation
            header("Location: ../result-generation.php");
            exit();
        } else {
            echo "Error creating JSON file.";
        }
    } else {
        echo "Election is not closed yet or schedule not found.";
    }

    // Close connection
    $conn->close();
}

// Call the function to execute data fetching and storing
fetchAndStoreData();
