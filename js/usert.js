function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling;
    
    if (field.type === "password") {
        field.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        field.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

function checkPasswordStrength(password) {
    const strengthText = document.getElementById('password-strength');
    
    if (!password) {
        strengthText.textContent = '';
        strengthText.className = 'password-strength';
        return;
    }

    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    let strengthLevel = '';
    if (strength <= 2) {
        strengthLevel = 'Lemah';
        strengthText.className = 'password-strength weak';
    } else if (strength <= 4) {
        strengthLevel = 'Sedang';
        strengthText.className = 'password-strength medium';
    } else {
        strengthLevel = 'Kuat';
        strengthText.className = 'password-strength strong';
    }
    
    strengthText.textContent = `Kekuatan password: ${strengthLevel}`;
}