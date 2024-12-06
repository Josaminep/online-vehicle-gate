<?php 
session_start();
include('../config.php');

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Get the selected report type from POST
$report_type = isset($_POST['report_type']) ? $_POST['report_type'] : '';
$date_condition = "";
$title = "";

switch ($report_type) {
    case 'daily':
        $date_condition = "DATE(date_time) = CURDATE()";
        $title = "Daily Vehicle Log Report";
        break;
    case 'weekly':
        $date_condition = "WEEK(date_time) = WEEK(CURDATE())";
        $title = "Weekly Vehicle Log Report";
        break;
    case 'monthly':
        $date_condition = "MONTH(date_time) = MONTH(CURDATE())";
        $title = "Monthly Vehicle Log Report";
        break;
    default:
        $date_condition = "";
        break;
}

if ($date_condition) {
    $report_sql = "SELECT * FROM vehicle_logs WHERE $date_condition";
    $report_result = mysqli_query($conn, $report_sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        h1 {
            text-align: center;
            color: #2A3663;
        }

        footer {
            text-align: center;
            margin-top: 40px;
        }

        /* Print-specific styles */
        @media print {
            button {
                display: none; /* Hide buttons while printing */
            }

            footer {
                page-break-before: always; /* Ensure footer is on the next page */
            }
        }
    </style>
    <!-- Include jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Include jsPDF AutoTable plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
</head>

<body>

    <h1><?php echo $title; ?></h1>
    <table id="vehicleLogsTable">
        <thead>
            <tr>
                <th>Plate Number</th>
                <th>Entry/Exit</th>
                <th>Date and Time</th>
                <th>Gate Number</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($report = mysqli_fetch_assoc($report_result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($report['plate_number']); ?></td>
                    <td><?php echo htmlspecialchars($report['entry_exit']); ?></td>
                    <td><?php echo htmlspecialchars($report['date_time']); ?></td>
                    <td><?php echo htmlspecialchars($report['gate_number']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Print Button -->
    <button onclick="window.print()">Print Report</button>

    <!-- Save as PDF Button -->
    <button id="downloadPdf">Download as PDF</button>

    <footer>
        <p>&copy; 2024 Online Vehicle Gate System. All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById('downloadPdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add title
            doc.text("<?php echo $title; ?>", 20, 20);

            // Add the table to the PDF from the HTML table
            doc.autoTable({ 
                html: '#vehicleLogsTable', 
                startY: 30, // Adjust to avoid overlap with title
            });

            // Save the generated PDF with the report title
            doc.save('<?php echo $title; ?>.pdf');
        });
    </script>

</body>
</html>
