const passwordInput = document.querySelector("#password");
const eyeIcon = document.querySelector(".pass-field i");
const requirementList = document.querySelectorAll("#password-conditions li");
const signupForm = document.querySelector("form"); 
const phoneField = document.querySelector("input[name='phone']");
const usernameField = document.querySelector("input[name='username']");

const requirements = [
    { regex: /.{6,}/, index: 0 },  
    { regex: /.{1,15}/, index: 1 }, 
    { regex: /[0-9]/, index: 2 },   
    { regex: /[a-z]/, index: 3 },   
    { regex: /[!@#$%^&*(),.?":{}|<>]/, index: 4 }, 
    { regex: /[A-Z]/, index: 5 },   
];


passwordInput.addEventListener("keyup", (e) => {
    requirements.forEach(item => {
        const isValid = item.regex.test(e.target.value);
        const requirementItem = requirementList[item.index];

        
        if (isValid) {
            requirementItem.classList.add("valid");
            requirementItem.firstElementChild.className = "fa-solid fa-check";
        } else {
            requirementItem.classList.remove("valid");
            requirementItem.firstElementChild.className = "fa-solid fa-circle";
        }
    });
});


eyeIcon.addEventListener("click", () => {
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    eyeIcon.className = `fa-solid fa-eye${passwordInput.type === "password" ? "" : "-slash"}`;
});


signupForm.addEventListener("submit", (e) => {
    let allValid = true; 

    
    requirements.forEach(item => {
        const isValid = item.regex.test(passwordInput.value);
        if (!isValid) {
            allValid = false; 
        }
    });

    
    const username = usernameField.value; 
    if (username.length < 4 || /^[._]/.test(username)) {
        allValid = false;
        alert("Username must be at least 4 characters long and should not start with '.' or '_'.");
    }

    
    const phone = phoneField.value; 
    if (!/^\d{10}$/.test(phone)) {
        allValid = false;
        alert("Phone number must be exactly 10 digits long.");
    }

    
    if (!allValid) { 
        e.preventDefault(); 
        alert("Please ensure all form fields are valid.");
    }
});
