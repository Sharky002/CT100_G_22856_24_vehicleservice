<?php
// Service Price Mapping including individualized requirement (Tyre Balancing = 2000)
$servicePrices = [
    "Engine Oil Change" => 3000,
    "Brake Inspection" => 2000,
    "Wheel Alignment" => 3500,
    "Computer Diagnosis" => 2500,
    "Full Service" => 8000,
    "Tyre Balancing" => 2000
];

$errors = [];
$customerName = "";
$phoneNumber = "";
$email = "";
$regNumber = "";
$vehicleType = "";
$vehicleModel = "";
$manufactureYear = "";
$serviceDate = "";
$selectedServices = [];
$calculatedTotal = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customerName = trim($_POST['customerName'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $regNumber = trim($_POST['regNumber'] ?? '');
    $vehicleType = trim($_POST['vehicleType'] ?? '');
    $vehicleModel = trim($_POST['vehicleModel'] ?? '');
    $manufactureYear = trim($_POST['manufactureYear'] ?? '');
    $serviceDate = trim($_POST['serviceDate'] ?? '');
    $selectedServices = $_POST['services'] ?? [];

    if (empty($customerName)) {
        $errors[] = "Customer name is required.";
    }
    if (empty($phoneNumber) || !preg_match('/^\d{10}$/', $phoneNumber)) {
        $errors[] = "Valid 10-digit phone number is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if (empty($regNumber)) {
        $errors[] = "Vehicle registration number is required.";
    }
    if (empty($vehicleType)) {
        $errors[] = "Vehicle type is required.";
    }
    if (empty($manufactureYear) || !is_numeric($manufactureYear) || $manufactureYear < 1950 || $manufactureYear > date('Y')) {
        $errors[] = "A reasonable year of manufacture is required.";
    }
    if (empty($serviceDate)) {
        $errors[] = "Preferred service date is required.";
    }
    if (empty($selectedServices)) {
        $errors[] = "At least one service must be selected.";
    }

    if (empty($errors)) {
        foreach ($selectedServices as $service) {
            if (isset($servicePrices[$service])) {
                $calculatedTotal += $servicePrices[$service];
            }
        }
    }
} else {
    header("Location: service.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Booking Confirmation - AutoCare Garage</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div class="container nav-container">
            <h1>AutoCare Garage</h1>
            <nav>
                <a href="index.html">Home</a>
                <a href="service.html">Book Service</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="confirmation-card">
            <?php if (!empty($errors)): ?>
                <h2>Submission Error</h2>
                <div style="color: #ef4444; margin-bottom: 1.5rem;">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="service.html" class="btn-primary">Return to Form</a>
            <?php else: ?>
                <h2>SERVICE REQUEST RECEIVED</h2>
                <div class="confirmation-details">
                    <p><strong>Customer:</strong> <?php echo htmlspecialchars($customerName); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($phoneNumber); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Vehicle:</strong> <?php echo htmlspecialchars($regNumber); ?>
                        (<?php echo htmlspecialchars($vehicleModel ?: 'Standard'); ?>)</p>
                    <p><strong>Vehicle Type:</strong> <?php echo htmlspecialchars($vehicleType); ?></p>
                    <p><strong>Manufacture Year:</strong> <?php echo htmlspecialchars($manufactureYear); ?></p>
                    <p><strong>Service Date:</strong> <?php echo htmlspecialchars($serviceDate); ?></p>
                    <p><strong>Selected Services:</strong></p>
                    <ul style="margin: 0.5rem 0 1.5rem 1.5rem; color: #374151;">
                        <?php
                        $hasFullService = in_array("Full Service", $selectedServices);
                        foreach ($selectedServices as $s):
                            $price = $servicePrices[$s] ?? 0;
                            if ($hasFullService && $s === "Computer Diagnosis") {
                                $price = 0;
                            }
                            ?>
                            <li><?php echo htmlspecialchars($s); ?> (KSh <?php echo number_format($price); ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="total-display">
                        ESTIMATED SERVICE COST: KSh <?php echo number_format($calculatedTotal); ?>
                    </div>
                </div>
                <div style="margin-top: 2rem; text-align: center;">
                    <a href="index.html" class="btn-primary">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 AutoCare Garage. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>