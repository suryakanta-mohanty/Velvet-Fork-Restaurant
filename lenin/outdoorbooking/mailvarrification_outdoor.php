<?php
session_start();  // <-- Must be FIRST LINE before any HTML or output
if (isset($_SESSION['otp'])) {
    $stored_otp = $_SESSION['otp'];
} else {
    echo "Session OTP not set. Something went wrong.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email OTP Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #8B0000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .verification-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 40%;
            text-align: center;
        }
        h2 {
            color: #8B4513;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 14px;
        }
        .timer {
            font-weight: bold;
            color: red;
        }
        .captcha-box {
            margin: 10px 0;
        }
        .submit-btn {
            background: #A93226;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
            display: none;
        }
        .submit-btn:hover {
            background: #922B21;
        }
        .error {
            color: red;
            font-weight: bold;
            display: none;
        }
    </style>
</head>
<body>
    <form method="POST" action="mailvarrification_outdoor.php" class="verification-container">
        <h2>Email Verification</h2>
        <p>Verification code has been sent to: <strong id="user-email"><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong></p>
        <p>Enter the code below within <span class="timer" id="countdown">10:00</span></p>
        
        <div class="form-group">
            <input type="text" id="otp" name="otp" placeholder="Enter OTP" maxlength="6">
        </div>
        
        <div class="captcha-box">
            <input type="checkbox" id="captcha"> I am not a robot
        </div>
        
        <p class="error" id="error-message">Verification Failed! Please try again.</p>
        
        <button class="submit-btn" value="Submit" id="verify-btn" onclick="submitVerification()">Submit</button>
    </form>

    <script>
        let timer = 600;
        let countdownEl = document.getElementById('countdown');
        let otpInput = document.getElementById('otp');
        let captchaCheckbox = document.getElementById('captcha');
        let submitBtn = document.getElementById('verify-btn');
        let errorMessage = document.getElementById('error-message');

        function startTimer() {
            let interval = setInterval(() => {
                let minutes = Math.floor(timer / 60);
                let seconds = timer % 60;
                countdownEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                if (timer === 0) {
                    clearInterval(interval);
                    countdownEl.textContent = "Time Expired";
                }
                timer--;
            }, 1000);
        }
        startTimer();

        function checkVerification() {
            if (otpInput.value.length === 6 && captchaCheckbox.checked) {
                submitBtn.style.display = 'block';
            } else {
                submitBtn.style.display = 'none';
            }
        }

        otpInput.addEventListener('input', checkVerification);
        captchaCheckbox.addEventListener('change', checkVerification);
    </script>

    <?php
    $entered_otp = isset($_POST['otp']) ? $_POST['otp'] : '';
    $session_otp = isset($_SESSION['otp']) ? $_SESSION['otp'] : '';

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($entered_otp == $session_otp) {
            
            // Proceed to store booking details into DB here
            echo "<script>window.location.href='process_outdoor_booking.php';</script>";
            exit;
            
        } else {
            echo "
                  <script>
                     alert('❌ OTP verification failed! Please try again.');
                     window.location.href = window.location.href;
                  </script>";

        }
    }
    ?>
    
    <?php
    error_reporting(E_ERROR | E_PARSE); // Only show critical errors
    

    ?>
</body>
</html>
