<?php
require 'vendor/autoload.php';
include "config/db.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf();

/* DATA */
$total_students = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM students"))['total'];
$total_companies = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM companies"))['total'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications WHERE status='Pending'"))['total'];
$accepted = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications WHERE status='Accepted'"))['total'];
$rejected = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications WHERE status='Rejected'"))['total'];

$html = "
<h2>InternTrack Pro - Report</h2>
<hr>

<h3>Summary</h3>
<ul>
<li>Total Students: $total_students</li>
<li>Total Companies: $total_companies</li>
<li>Total Applications: $total_applications</li>
</ul>

<h3>Application Status</h3>
<ul>
<li>Pending: $pending</li>
<li>Accepted: $accepted</li>
<li>Rejected: $rejected</li>
</ul>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("interntrack_report.pdf", ["Attachment" => true]);