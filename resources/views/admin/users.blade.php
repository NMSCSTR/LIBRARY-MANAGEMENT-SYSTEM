<!DOCTYPE html>
<?php
require 'admin/connect.php';
$XENDIT_API_KEY = "xnd_production_oXm5yXdwGgNDR5CKucONZQnQklZzPwlXdMhvwzCttod3weebbQ1VgWJNSZvz";

// Load footer info
$info = $conn->query("SELECT * FROM owners_info WHERE info_id=1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize input
    foreach ($_POST as $key => $value) {
        $_POST[$key] = mysqli_real_escape_string($conn, $value);
    }

    $firstname        = $_POST['firstname'];
    $lastname         = $_POST['lastname'];
    $contactno        = $_POST['contactno'];
    $email            = $_POST['email'];
    $address          = $_POST['address'];
    $reservation_type = $_POST['reservation_type'];
    $room_id          = $_POST['room_id'] ?: null;
    $cottage_id       = $_POST['cottage_id'] ?: null;
    $check_in_date    = $_POST['check_in_date'];
    $check_out_date   = $_POST['check_out_date'];
    $payment_method   = $_POST['payment_method'];

    $transaction_ref = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));

    // Calculate total amount
    $total_amount = 0;
    $check_in  = new DateTime($check_in_date);
    $check_out = new DateTime($check_out_date);

    $number_of_days = $check_in->diff($check_out)->days;
    if ($number_of_days == 0) $number_of_days = 1;

    // Room fee
    if (($reservation_type === 'room_only' || $reservation_type === 'both') && $room_id) {
        $q = $conn->query("SELECT room_price FROM room WHERE room_id='$room_id'");
        if ($q->num_rows > 0) {
            $total_amount += floatval($q->fetch_assoc()['room_price']) * $number_of_days;
        }
    }

    // Cottage fee (1-day fixed)
    if (($reservation_type === 'cottage_only' || $reservation_type === 'both') && $cottage_id) {
        $q = $conn->query("SELECT cottage_price FROM cottage WHERE cottage_id='$cottage_id'");
        if ($q->num_rows > 0) {
            $total_amount += floatval($q->fetch_assoc()['cottage_price']);
        }
    }

    // Check conflicts
    $room_conflict = false;
    $cot_conflict = false;

    if ($room_id) {
        $room_conflict = $conn->query("
            SELECT 1 FROM reservation
            WHERE room_id='$room_id'
            AND status='confirmed'
            AND (check_in_date <= '$check_out_date' AND check_out_date >= '$check_in_date')
        ");
    }

    if ($cottage_id) {
        $cot_conflict = $conn->query("
            SELECT 1 FROM reservation
            WHERE cottage_id='$cottage_id'
            AND status='confirmed'
            AND (check_in_date <= '$check_out_date' AND check_out_date >= '$check_in_date')
        ");
    }

    if (($room_conflict && $room_conflict->num_rows > 0) ||
        ($cot_conflict && $cot_conflict->num_rows > 0)) {
        die("Sorry, the selected date is already booked and confirmed.");
    }

    // Insert guest
    $conn->query("INSERT INTO guest (firstname, lastname, address, contactno, email)
                  VALUES ('$firstname', '$lastname', '$address', '$contactno', '$email')");
    $guest_id = $conn->insert_id;

    // Insert reservation
    $conn->query("
        INSERT INTO reservation (
            guest_id, transaction_reference,
            room_id, cottage_id,
            check_in_date, check_out_date,
            total_amount
        ) VALUES (
            '$guest_id', '$transaction_ref',
            " . ($room_id ? "'$room_id'" : "NULL") . ",
            " . ($cottage_id ? "'$cottage_id'" : "NULL") . ",
            '$check_in_date', '$check_out_date',
            '$total_amount'
        )
    ");
    $reservation_id = $conn->insert_id;

    // Handle Xendit Payment
    if ($payment_method === 'xendit') {

        $base_url    = "https://mabanag-spring-resort.site";
        $success_url = $base_url . "/success.php?res_id=$reservation_id&transaction_ref=$transaction_ref";
        $failure_url = $base_url . "/fail.php";
        $external_id = 'invoice_' . uniqid();

        $payload = [
            "external_id"          => $external_id,
            "payer_email"          => $email,
            "amount"               => intval($total_amount),
            "description"          => "Reservation payment for $firstname $lastname",
            "success_redirect_url" => $success_url . "&external_id=" . $external_id,
            "failure_redirect_url" => $failure_url,
            "customer" => [
                "given_names"   => $firstname,
                "surname"       => $lastname,
                "email"         => $email,
                "mobile_number" => $contactno
            ]
        ];

        $ch = curl_init("https://api.xendit.co/v2/invoices");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => $XENDIT_API_KEY . ":",
            CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload)
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $invoice = json_decode($response, true);

        if (!isset($invoice['invoice_url'])) {
            die("Xendit Error: " . $response);
        }

        // Save invoice
        $conn->query("
            INSERT INTO xendit_invoices (
                invoice_id, reservation_id, external_id, status,
                amount, customer_email, description, invoice_url, expiry_date, created_at
            ) VALUES (
                '{$invoice['id']}', $reservation_id, '{$invoice['external_id']}',
                '{$invoice['status']}', '{$invoice['amount']}',
                '{$invoice['payer_email']}', '{$invoice['description']}',
                '{$invoice['invoice_url']}', '{$invoice['expiry_date']}',
                '{$invoice['created']}'
            )
        ");

        header("Location: " . $invoice['invoice_url']);
        exit;
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Reservation - Mabanag Spring Resort</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/reservation.css">
</head>

<body>
    <!-- Navigation -->
   <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <i class="fas fa-leaf me-2"></i>Mabanag Spring Resort
    </a>
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="aboutus.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="cottages.php">Cottages</a></li>
        <li class="nav-item"><a class="nav-link" href="notice.php">Important Notice</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <span class="nature-badge">Book Your Nature Escape</span>
                    <h1 class="hero-title">Make Your Reservation</h1>
                    <p class="hero-subtitle">Experience the beauty and tranquility of Mabanag Spring Resort - your
                        perfect nature getaway awaits</p>
                </div>
            </div>
        </div>
    </div>

<!-- Reservation Form Section -->
<div class="reservation-section leaf-pattern">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="reservation-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-calendar-check me-2"></i>Reservation Form
                        </h2>
                        <p class="card-subtitle">Fill out the form below to book your nature escape</p>
                    </div>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="reservation-form p-4 border rounded shadow-sm bg-white" enctype="multipart/form-data">
    <!-- Personal Information -->
    <div class="form-section mb-4">
        <h4 class="section-title mb-3 text-primary">
            <i class="fas fa-user me-2"></i>Personal Information
        </h4>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="firstname" class="form-label">First Name *</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" required>
            </div>
            <div class="col-md-6">
                <label for="lastname" class="form-label">Last Name *</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter last name" required>
            </div>
            <div class="col-md-6">
                <label for="contactno" class="form-label">Contact Number *</label>
                <input type="tel" class="form-control" id="contactno" name="contactno" placeholder="e.g. 09123456789" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
            </div>
            <div class="col-12">
                <label for="address" class="form-label">Address *</label>
                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter full address" required></textarea>
            </div>
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="form-section mb-4">
        <h4 class="section-title mb-3 text-primary">
            <i class="fas fa-bed me-2"></i>Reservation Details
        </h4>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="reservation_type" class="form-label">Reservation Type *</label>
                <select class="form-select" id="reservation_type" name="reservation_type" required>
                    <option value="">-- Select Type --</option>
                    <option value="room_only">Room Only</option>
                    <option value="cottage_only">Cottage Only</option>
                    <option value="both">Room + Cottage</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="room_id" class="form-label" id="room_label" style="display: none;">Select Room *</label>
                <label for="cottage_id" class="form-label" id="cottage_label" style="display: none;">Select Cottage *</label>

                <select class="form-select" id="room_id" name="room_id" style="display: none;">
                    <option value="">Select Room</option>
                    <?php
                    $room_query = $conn->query("SELECT * FROM room WHERE room_availability = 'available'");
                    while ($room = $room_query->fetch_array()):
                    ?>
                        <option value="<?= $room['room_id'] ?>" data-price="<?= $room['room_price'] ?>">
                            Room <?= $room['room_number'] ?> - <?= $room['room_type'] ?> (₱<?= number_format($room['room_price'],2) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>

                <select class="form-select mt-2" id="cottage_id" name="cottage_id" style="display: none;">
                    <option value="">Select Cottage</option>
                    <?php
                    $cottage_query = $conn->query("SELECT * FROM cottage WHERE cottage_availability = 'available'");
                    while ($cottage = $cottage_query->fetch_array()):
                    ?>
                        <option value="<?= $cottage['cottage_id'] ?>" data-price="<?= $cottage['cottage_price'] ?>">
                            <?= $cottage['cottage_type'] ?> (₱<?= number_format($cottage['cottage_price'],2) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="check_in_date" class="form-label">Check-in Date *</label>
                <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
            </div>
            <div class="col-md-6">
                <label for="check_out_date" class="form-label">Check-out Date *</label>
                <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
            </div>
            <div class="col-12">
                <div class="stay-duration alert alert-info mt-2 d-none" id="stay_duration">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Duration: <strong id="duration_days">0</strong> day(s)
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="form-section mb-4">
        <h4 class="section-title mb-3 text-primary">
            <i class="fas fa-credit-card me-2"></i>Payment Information
        </h4>
        <div class="row g-3">
            <div class="col-12">
                <label for="payment_method" class="form-label">Payment Method *</label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="">Select Payment Method</option>
                    <option value="xendit">Pay Now (Online Payment)</option>
                </select>
            </div>
        </div>
        <div class="total-amount mt-3 p-2 border rounded bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold">Total Amount:</span>
                <span id="total_amount" class="fw-bold text-primary">₱0.00</span>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-grow-1">
            <i class="fas fa-paper-plane me-2"></i>Submit Reservation
        </button>
        <button type="reset" class="btn btn-outline-secondary flex-grow-1">
            <i class="fas fa-undo me-2"></i>Reset Form
        </button>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-leaf me-2"></i>Mabanag Spring Resort</h5>
                    <p>Experience the perfect blend of nature and comfort at our beautiful resort nestled in pristine
                        natural surroundings.</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="aboutus.php">About Us</a></li>
                        <li><a href="guest_reservation.php">Reservation</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-phone me-2"></i><?php echo $info['phone_number'] ?? '+63 123 456 7890'; ?></p>
                    <p><i
                            class="fas fa-envelope me-2"></i><?php echo $info['email_address'] ?? 'info@mabanagresort.com'; ?>
                    </p>
                    <p><i
                            class="fas fa-map-marker-alt me-2"></i><?php echo $info['address'] ?? 'Mabanag, Juban, Sorsogon'; ?>
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy;                          <?php echo date('Y'); ?> Mabanag Spring Resort. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <!--<script src="js/guest_reservation_script.js"></script>-->
    <script>
document.addEventListener("DOMContentLoaded", () => {

    /* ================================
       ELEMENT REFERENCES
    ================================ */
    const form = document.querySelector("form");
    const reservationType = document.getElementById("reservation_type");
    const roomSelect = document.getElementById("room_id");
    const cottageSelect = document.getElementById("cottage_id");
    const roomLabel = document.getElementById("room_label");
    const cottageLabel = document.getElementById("cottage_label");

    const checkIn = document.getElementById("check_in_date");
    const checkOut = document.getElementById("check_out_date");

    const totalAmount = document.getElementById("total_amount");
    const stayDurationBox = document.getElementById("stay_duration");
    const durationDays = document.getElementById("duration_days");

    /* ================================
       SET MINIMUM DATE (Today)
    ================================ */
    const today = new Date().toISOString().split("T")[0];
    checkIn.min = today;
    checkOut.min = today;

    /* ================================
       SHOW/HIDE FIELDS BASED ON TYPE
    ================================ */
    reservationType.addEventListener("change", () => {

        // Reset all visibility first
        roomSelect.style.display = "none";
        roomLabel.style.display = "none";
        roomSelect.required = false;

        cottageSelect.style.display = "none";
        cottageLabel.style.display = "none";
        cottageSelect.required = false;

        checkOut.parentElement.style.display = "block";
        checkOut.required = true;

        // Reset values
        roomSelect.value = "";
        cottageSelect.value = "";
        checkOut.value = "";

        const type = reservationType.value;

        if (type === "room_only") {
            roomSelect.style.display = "block";
            roomLabel.style.display = "block";
            roomSelect.required = true;
        }

        else if (type === "cottage_only") {
            cottageSelect.style.display = "block";
            cottageLabel.style.display = "block";
            cottageSelect.required = true;

            // Cottage-only: no check-out date required
            checkOut.parentElement.style.display = "none";
            checkOut.required = false;
        }

        else if (type === "both") {
            roomSelect.style.display = "block";
            roomLabel.style.display = "block";
            roomSelect.required = true;

            cottageSelect.style.display = "block";
            cottageLabel.style.display = "block";
            cottageSelect.required = true;

            checkOut.required = true;
        }

        updateTotalAmount();
    });

    /* ================================
       CALCULATE TOTAL AMOUNT
    ================================ */
    function updateTotalAmount() {
        let total = 0;
        let roomRate = 0;
        let cottageRate = 0;
        let days = 1;

        if ((reservationType.value === "room_only" || reservationType.value === "both") && roomSelect.value) {
            roomRate = parseFloat(roomSelect.selectedOptions[0].dataset.price);
        }

        if ((reservationType.value === "cottage_only" || reservationType.value === "both") && cottageSelect.value) {
            cottageRate = parseFloat(cottageSelect.selectedOptions[0].dataset.price);
        }

        // Duration for room or room+cottage
        if (reservationType.value !== "cottage_only" && checkIn.value && checkOut.value) {
            const start = new Date(checkIn.value);
            const end = new Date(checkOut.value);
            const diff = end - start;

            if (diff > 0) {
                days = diff / (1000 * 60 * 60 * 24);
                stayDurationBox.classList.remove("d-none");
                durationDays.textContent = days;
            } else {
                stayDurationBox.classList.add("d-none");
            }
        } else {
            stayDurationBox.classList.add("d-none");
        }

        total = roomRate * days + cottageRate;

        totalAmount.innerHTML = `₱${total.toFixed(2)}`;
    }

    /* UPDATE TOTAL WHEN ANY RELATED FIELD CHANGES */
    roomSelect.addEventListener("change", updateTotalAmount);
    cottageSelect.addEventListener("change", updateTotalAmount);
    checkIn.addEventListener("change", updateTotalAmount);
    checkOut.addEventListener("change", updateTotalAmount);

    /* ================================
       FORM VALIDATION
    ================================ */
    form.addEventListener("submit", (e) => {

        let valid = true;

        // Validate required fields
        form.querySelectorAll("[required]").forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add("is-invalid");
            } else {
                field.classList.remove("is-invalid");
            }
        });

        // Validate dates for room-only or both
        if (reservationType.value !== "cottage_only") {
            if (checkIn.value && checkOut.value) {
                const start = new Date(checkIn.value);
                const end = new Date(checkOut.value);

                if (end <= start) {
                    valid = false;
                    checkOut.classList.add("is-invalid");
                    showAlert("Check-out date must be after check-in date.", "error");
                }
            }
        }

        if (!valid) {
            e.preventDefault();
            showAlert("Please complete all required fields correctly.", "error");
            return;
        }

        // Loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
        submitBtn.disabled = true;
    });

    /* ================================
       ALERT FUNCTION
    ================================ */
    function showAlert(message, type = "error") {
        const alertDiv = document.createElement("div");
        alertDiv.className = `alert alert-${type === "error" ? "danger" : "success"} alert-dismissible fade show mt-3`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === "error" ? "exclamation-circle" : "check-circle"} me-2"></i>
            ${message}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        `;
        form.prepend(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

});
</script>

</body>

</html>
