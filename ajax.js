// Email verification logic
function handleSubmit(event) {
    event.preventDefault(); // Prevent form from submitting the traditional way
    
    const email = document.getElementById("email").value;
    
    // Send the email to the backend to generate and send the verification code
    fetch('send_verification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === "success") {
            // Prompt the user to enter the verification code received in the email
            const userCode = prompt("A verification code has been sent to your email. Please enter the verification code:");

            // Send the code to the backend for verification
            fetch('verify_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email) + '&code=' + encodeURIComponent(userCode)
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === "success") {
                    alert("Email verified successfully!");
                    // Redirect to index.html after successful registration
                    window.location.href = 'index.html';
                } else {
                    alert("Verification failed! Please check the code and try again.");
                }
            })
            .catch(error => {
                alert("Error verifying the code. Please try again later.");
                console.error(error);
            });

        } else {
            alert("Failed to send verification email. Please try again.");
        }
    })
    .catch(error => {
        alert("Error sending email. Please try again later.");
        console.error(error);
    });
}
