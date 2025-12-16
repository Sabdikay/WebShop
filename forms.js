window.addEventListener('load', function() {
    
 
    var regForm = document.getElementById('regForm');
    var loginForm = document.getElementById('loginForm');
    var customerForm = document.getElementById('customerForm');
    

    if (regForm) {
        var usernameInput = document.getElementById('username');
        var passwordInput = document.getElementById('password');
        var confirmInput = document.getElementById('confirmPassword');
        

        usernameInput.addEventListener('input', function() {
            checkUsername(usernameInput);
        });
        

        passwordInput.addEventListener('input', function() {
            checkPassword(passwordInput);
            if (confirmInput.value.length > 0) {
                checkConfirmPassword(passwordInput, confirmInput);
            }
        });
        

        confirmInput.addEventListener('input', function() {
            checkConfirmPassword(passwordInput, confirmInput);
        });
        

        regForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            var usernameOK = checkUsername(usernameInput);
            var passwordOK = checkPassword(passwordInput);
            var confirmOK = checkConfirmPassword(passwordInput, confirmInput);
            
            if (usernameOK && passwordOK && confirmOK) {
                alert('Registration successful!');
                window.location.href = 'login.html';
            } else {
                alert('Please fix the errors');
            }
        });
    }
    
  
    if (loginForm) {
        var usernameInput = document.getElementById('username');
        var passwordInput = document.getElementById('password');
        

        usernameInput.addEventListener('input', function() {
            checkUsername(usernameInput);
        });
        

        passwordInput.addEventListener('input', function() {
            checkPassword(passwordInput);
        });
        

        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            var usernameOK = checkUsername(usernameInput);
            var passwordOK = checkPassword(passwordInput);
            
            if (!usernameOK && !passwordOK) {
                event.preventDefault();
                alert('Invalid username and password');
            }
        });
    }

    if (customerForm) {
        var usernameInput = document.getElementById('username');
        var passwordInput = document.getElementById('password');
        

        checkUsername(usernameInput);
        checkPassword(passwordInput);
        

        usernameInput.addEventListener('input', function() {
            checkUsername(usernameInput);
        });
        

        passwordInput.addEventListener('input', function() {
            checkPassword(passwordInput);
        });
        

        customerForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            var usernameOK = checkUsername(usernameInput);
            var passwordOK = checkPassword(passwordInput);
            
            if (usernameOK && passwordOK) {
                alert('Profile updated successfully!');
            } else {
                alert('Please fix the errors');
            }
        });
    }
});



// Function to check username
function checkUsername(usernameInput) {
    var username = usernameInput.value;
    var errorSpan = document.getElementById('usernameError');
    
    // Check length 
    if (username.length < 5) {
        usernameInput.classList.add('invalid');
        usernameInput.classList.remove('valid');
        errorSpan.textContent = 'Username must have at least 5 characters';
        return false;
    }
    
    // Check for uppercase
    var hasUpperCase = false;
    for (var i = 0; i < username.length; i++) {
        if (username[i] >= 'A' && username[i] <= 'Z') {
            hasUpperCase = true;
            break;
        }
    }
    
    // Check for lowercase 
    var hasLowerCase = false;
    for (var i = 0; i < username.length; i++) {
        if (username[i] >= 'a' && username[i] <= 'z') {
            hasLowerCase = true;
            break;
        }
    }
    
    // If one is missing 
    if (!hasUpperCase || !hasLowerCase) {
        usernameInput.classList.add('invalid');
        usernameInput.classList.remove('valid');
        errorSpan.textContent = 'Username must have at least one uppercase and one lowercase letter';
        return false;
    }
    
    usernameInput.classList.add('valid');
    usernameInput.classList.remove('invalid');
    errorSpan.textContent = '';
    return true;
}


function checkPassword(passwordInput) {
    var password = passwordInput.value;
    var errorSpan = document.getElementById('passwordError');
    

    if (password.length < 10) {
        passwordInput.classList.add('invalid');
        passwordInput.classList.remove('valid');
        errorSpan.textContent = 'Password must have at least 10 characters';
        return false;
    }
    

    passwordInput.classList.add('valid');
    passwordInput.classList.remove('invalid');
    errorSpan.textContent = '';
    return true;
}


function checkConfirmPassword(passwordInput, confirmInput) {
    var password = passwordInput.value;
    var confirm = confirmInput.value;
    var errorSpan = document.getElementById('confirmError');
    

    if (password !== confirm) {
        confirmInput.classList.add('invalid');
        confirmInput.classList.remove('valid');
        errorSpan.textContent = 'Passwords do not match';
        return false;
    }
    

    if (confirm.length === 0) {
        confirmInput.classList.add('invalid');
        confirmInput.classList.remove('valid');
        errorSpan.textContent = 'Please confirm your password';
        return false;
    }
    

    confirmInput.classList.add('valid');
    confirmInput.classList.remove('invalid');
    errorSpan.textContent = '';
    return true;
}