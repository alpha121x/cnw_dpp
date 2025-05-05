<?php
require_once '../vendor/autoload.php';
require_once 'db_config.php';

use Dompdf\Dompdf;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Invalid ID');
}

// Updated dummy data with multiple measurement items
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
    die('MB entry not found.');
}

$data = $dummy_data[$id];

// Build measurement rows with Item No.
$measurementRows = '';
$totalQty = 0;
$counter = 1;

foreach ($data['measurement_values'] as $item) {
    $measurementRows .= "<tr>
        <td>{$counter}</td>
        <td>{$item['description']}</td>
        <td>{$item['quantity']}</td>
        <td>{$item['unit']}</td>
        <td>{$item['remarks']}</td>
    </tr>";
    $totalQty += $item['quantity'];
    $counter++;
}

// HTML content with professional layout
$html = "
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h2 { text-align: center; margin: 10px 0; font-size: 18px; }
    h3 { margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    .meta-table td { border: none; padding: 5px; }
    .header {
        text-align: center;
        margin-bottom: 10px;
    }
    .logo-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .logo {
        height: 60px;
    }
</style>

<div class='logo-container'>
    <img src='../assets/img/cnw_logo.png' class='logo' />
    <div class='header'>
        <h2>Government of Punjab</h2>
        <div><strong>Public Infrastructure Department</strong></div>
        <div><strong>Measurement Book Report</strong></div>
    </div>
    <img src='../assets/img/punjab.png' class='logo' />
</div>

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

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the PDF
$dompdf->stream("mb-entry-{$id}.pdf", ["Attachment" => true]);
exit;
?>