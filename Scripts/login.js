function toggleSignup(){
    document.getElementById("login-toggle").style.backgroundColor="#fff";
     document.getElementById("login-toggle").style.color="#222";
     document.getElementById("signup-toggle").style.backgroundColor="#57b846";
     document.getElementById("signup-toggle").style.color="#fff";
     document.getElementById("login-form").style.display="none";
     document.getElementById("signup-form").style.display="block";
 }
 
 function toggleLogin(){
     document.getElementById("login-toggle").style.backgroundColor="#57B846";
     document.getElementById("login-toggle").style.color="#fff";
     document.getElementById("signup-toggle").style.backgroundColor="#fff";
     document.getElementById("signup-toggle").style.color="#222";
     document.getElementById("signup-form").style.display="none";
     document.getElementById("login-form").style.display="block";
 }
 
 
 /* login on sign up logic */
 document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("#login-form form");
    const signupForm = document.querySelector("#signup-form form");

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const username = loginForm.querySelector("input[type='text']").value;
        const password = loginForm.querySelector("input[type='password']").value;
        
        const storedUser = localStorage.getItem(username);
        
        if (storedUser) {
            const userData = JSON.parse(storedUser);
            if (userData.password === password) {
                alert("Login successful!");
                window.location.href = "home.php"; // Redirect to home page
            } else {
                alert("Incorrect password. Please try again.");
            }
        } else {
            alert("User not found. Please sign up first.");
        }
    });

    signupForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const email = signupForm.querySelector("input[type='email']").value;
        const username = signupForm.querySelector("input[type='text']").value;
        const password = signupForm.querySelector("input[type='password']").value;
        
        if (localStorage.getItem(username)) {
            alert("Username already exists. Please choose another one.");
        } else {
            const userData = { email, password };
            localStorage.setItem(username, JSON.stringify(userData));
            alert("Account created successfully! You can now log in.");
            toggleLogin(); // Switch to login form after sign-up
        }
    });
});

function toggleLogin() {
    document.getElementById("login-form").style.display = "block";
    document.getElementById("signup-form").style.display = "none";
}

function toggleSignup() {
    document.getElementById("signup-form").style.display = "block";
    document.getElementById("login-form").style.display = "none";
}


document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#login-form form").addEventListener("submit", function (e) {
        e.preventDefault();
        sendData("login");
    });

    document.querySelector("#signup-form form").addEventListener("submit", function (e) {
        e.preventDefault();
        sendData("signup");
    });

    function sendData(action) {
        let formData = new FormData();
        formData.append("action", action);
        formData.append("email", document.querySelector(`#${action}-form input[type='email']`).value);
        formData.append("password", document.querySelector(`#${action}-form input[type='password']`).value);
        
        if (action === "signup") {
            formData.append("username", document.querySelector(`#signup-form input[type='text']`).value);
        }

        fetch("login_signup.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success" && action === "login") {
                window.location.href = "dashboard.php"; // Redirect after login
            }
        })
        .catch(error => console.error("Error:", error));
    }
});
