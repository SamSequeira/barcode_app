document.addEventListener('DOMContentLoaded', () => {
    const scannerInput = document.getElementById('scanner-input');
    const barcodeDisplay = document.getElementById('barcode');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message'); // New error message element
    const loginType = document.getElementById('login-type').value; // Get the login type
    let scanStage = {}; // Track scan stages for each barcode (entry or exit)

    // Debounce to handle rapid barcode scanning
    let debounceTimeout;
    const debounceTime = 300;

    scannerInput.addEventListener('input', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const barcode = scannerInput.value.trim();
            if (barcode) {
                handleBarcodeScan(barcode);
                scannerInput.value = ''; // Clear the input field after scanning
            }
        }, debounceTime);
    });

    function handleBarcodeScan(barcode) {
        barcodeDisplay.textContent = barcode;

        // Send scan data without specifying entry or exit
        sendScanData(barcode);
    }

    function sendScanData(barcode) {
        // Send the barcode and login type to the server
        fetch('process_scan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ barcode, loginType }),
        })
        .then(response => response.json())
        .then(data => {
            // Clear previous messages
            successMessage.textContent = '';
            errorMessage.textContent = '';

            if (data.success) {
                successMessage.textContent = `Details recorded successfully`; // Generic success message
                successMessage.style.display = 'block'; // Show success message
                errorMessage.style.display = 'none'; // Hide error message if previously shown
                speak(`Details recorded successfully`);
            } else {
                errorMessage.textContent = `Error: ${data.message}`; // Update to use the error message div
                errorMessage.style.display = 'block'; // Show error message in red
                successMessage.style.display = 'none'; // Hide success message if previously shown
                speak(`Error: ${data.message}`);
            }            
        })
        .catch(error => {
            // Clear previous messages
            successMessage.textContent = '';
            errorMessage.textContent = 'An error occurred'; // Use the error message div
            speak('An error occurred');
        });
    }

    function speak(text) {
        const speech = new SpeechSynthesisUtterance(text);
        window.speechSynthesis.speak(speech);
    }
});

