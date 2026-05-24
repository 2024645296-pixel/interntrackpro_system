<?php
include "config/db.php";

$query = "
SELECT interviews.id,
interviews.interview_date,
interviews.interview_time,
interviews.location,
interviews.status,
interviews.notes,
students.full_name,
companies.company_name
FROM interviews
JOIN applications ON interviews.application_id = applications.id
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
";

$result = mysqli_query($conn, $query);

$events = [];

while($row = mysqli_fetch_assoc($result)){

    $color = "#facc15";

    if($row['status'] == "Done"){
        $color = "#22c55e";
    } elseif($row['status'] == "Missed"){
        $color = "#ef4444";
    }

    $events[] = [
        "id" => $row['id'],
        "title" => $row['full_name']." - ".$row['company_name'],
        "start" => $row['interview_date']."T".$row['interview_time'],
        "backgroundColor" => $color,
        "borderColor" => $color,

        // extra data for modal
        "extendedProps" => [
            "location" => $row['location'],
            "status" => $row['status'],
            "notes" => $row['notes'],
            "student" => $row['full_name'],
            "company" => $row['company_name']
        ]
    ];
}

echo json_encode($events);
?>