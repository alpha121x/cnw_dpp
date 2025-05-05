<?php
require_once '../vendor/autoload.php';
require_once 'db_config.php';

ini_set('display_errors', 0); // Don't display to user
ini_set('log_errors', 1); // Log errors
ini_set('error_log', __DIR__ . '/error.log'); // Custom log file

error_reporting(E_ALL); // Report all errors


use Dompdf\Dompdf;

// Validate ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Invalid Measurement Book ID.');
}

error_log("Generating PDF for Measurement Book ID: $id");


// Simulated data source (replace this with DB queries as needed)
$dummy_data = [
    1 => [
        'name' => 'Road Construction MB-001',
        'agency' => 'Public Works Dept',
        'authority' => 'Eng. John Doe',
        'date_of_comm' => '2025-01-15',
        'date_of_comp' => '2025-04-10',
        'date_of_measurement' => '2025-03-20',
        'measurement_values' => [
            ['description' => 'Earthwork Excavation', 'quantity' => 120.5, 'unit' => 'm³', 'remarks' => 'Completed'],
            ['description' => 'PCC 1:4:8', 'quantity' => 75, 'unit' => 'm²', 'remarks' => 'In progress'],
            ['description' => 'Brick Masonry', 'quantity' => 45, 'unit' => 'm³', 'remarks' => 'Pending'],
        ]
    ],
    2 => [
        'name' => 'Bridge Repair MB-002',
        'agency' => 'Infrastructure Agency',
        'authority' => 'Eng. Jane Smith',
        'date_of_comm' => '2025-02-01',
        'date_of_comp' => null,
        'date_of_measurement' => '2025-03-25',
        'measurement_values' => [
            ['description' => 'Steel Plates Replacement', 'quantity' => 2000, 'unit' => 'kg', 'remarks' => 'Installed']
        ]
    ],
    3 => [
        'name' => 'Pipeline Installation MB-003',
        'agency' => 'Water Board',
        'authority' => 'Eng. Alex Brown',
        'date_of_comm' => '2025-03-01',
        'date_of_comp' => '2025-04-15',
        'date_of_measurement' => '2025-04-01',
        'measurement_values' => [
            ['description' => 'PVC Pipe Sections', 'quantity' => 50, 'unit' => 'units', 'remarks' => 'Delivered']
        ]
    ]
];

if (!isset($dummy_data[$id])) {
    die('Measurement Book entry not found.');
}

$data = $dummy_data[$id];

// Generate table rows for measurement items
$measurementRows = '';
$totalQty = 0;
foreach ($data['measurement_values'] as $index => $item) {
    $measurementRows .= "<tr>
        <td>" . ($index + 1) . "</td>
        <td>{$item['description']}</td>
        <td>{$item['quantity']}</td>
        <td>{$item['unit']}</td>
        <td>{$item['remarks']}</td>
    </tr>";
    $totalQty += $item['quantity'];
}

// Resolve image paths properly
$logoPath = realpath(__DIR__ . "/../assets/img/cnw_logo.png");
$punjabPath = realpath(__DIR__ . "/../assets/img/punjab.png");

// Check if paths exist
if (!$logoPath || !file_exists($logoPath)) {
    error_log("Logo image not found at: " . $logoPath);
    $logoPath = ''; // Fallback or handle error
}
if (!$punjabPath || !file_exists($punjabPath)) {
    error_log("Punjab image not found at: " . $punjabPath);
    $punjabPath = ''; // Fallback or handle error
}

// Update HTML with resolved paths
$html = "
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h2 { margin: 0; font-size: 18px; }
    h3 { margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    .meta-table td { border: none; padding: 5px; }
    .header-table { width: 100%; margin-bottom: 15px; }
    .header-table td { vertical-align: middle; text-align: center; }
    .logo { width: 70px; height: auto; }
</style>

<table class='header-table'>
    <tr>
        <td style='text-align: left; width: 25%;'>
            " . ($logoPath ? "<img src='$logoPath' class='logo' />" : '') . "
        </td>
        <td style='text-align: center; width: 50%;'>
            <h2><strong>C&W Digitized Payment System</strong></h2>
            <div><strong>Measurement Book Report</strong></div>
        </td>
        <td style='text-align: right; width: 25%;'>
            " . ($punjabPath ? "<img src='$punjabPath' class='logo' />" : '') . "
        </td>
    </tr>
</table>

<hr />

<table class='meta-table'>
  <tr><td><strong>Name of Work:</strong></td><td>{$data['name']}</td></tr>
  <tr><td><strong>Executing Agency:</strong></td><td>{$data['agency']}</td></tr>
  <tr><td><strong>Authorized Engineer:</strong></td><td>{$data['authority']}</td></tr>
  <tr><td><strong>Date of Commencement:</strong></td><td>{$data['date_of_comm']}</td></tr>
  <tr><td><strong>Date of Completion:</strong></td><td>" . ($data['date_of_comp'] ?? 'Not Completed') . "</td></tr>
  <tr><td><strong>Date of Measurement:</strong></td><td>{$data['date_of_measurement']}</td></tr>
</table>

<h3>Measurement Details</h3>
<table>
  <thead>
    <tr>
      <th>Item No.</th>
      <th>Description</th>
      <th>Quantity</th>
      <th>Unit</th>
      <th>Remarks</th>
    </tr>
  </thead>
  <tbody>
    {$measurementRows}
    <tr>
      <td colspan='2' style='text-align: right'><strong>Total Quantity</strong></td>
      <td colspan='3'><strong>{$totalQty}</strong></td>
    </tr>
  </tbody>
</table>
";

// Generate and stream PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("mb-entry-{$id}.pdf", ["Attachment" => true]);
exit;
