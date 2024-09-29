const passwordInput = document.querySelector("#password");
const eyeIcon = document.querySelector(".pass-field i");
const requirementList = document.querySelectorAll("#password-conditions li");
const signupForm = document.querySelector("form"); // Get the form element

// An array of password requirements with corresponding regular expressions and index of the requirement list item
const requirements = [
    { regex: /.{6,}/, index: 0 }, // Minimum of 6 characters
    { regex: /.{1,15}/, index: 1 }, // Maximum of 15 characters
    { regex: /[0-9]/, index: 2 }, // At least one number
    { regex: /[a-z]/, index: 3 }, // At least one lowercase letter
    { regex: /[!@#$%^&*(),.?":{}|<>]/, index: 4 }, // At least one special character
    { regex: /[A-Z]/, index: 5 }, // At least one uppercase letter
];

passwordInput.addEventListener("keyup", (e) => {
    requirements.forEach(item => {
        // Check if the password matches the requirement regex
        const isValid = item.regex.test(e.target.value);
        const requirementItem = requirementList[item.index];

        // Updating class and icon of requirement item if requirement matched or not
        if (isValid) {
            requirementItem.classList.add("valid");
            requirementItem.firstElementChild.className = "fa-solid fa-check";
        } else {
            requirementItem.classList.remove("valid");
            requirementItem.firstElementChild.className = "fa-solid fa-circle";
        }
    });
});

// Toggle password visibility
eyeIcon.addEventListener("click", () => {
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    eyeIcon.className = `fa-solid fa-eye${passwordInput.type === "password" ? "" : "-slash"}`;
});

// Form submission validation
signupForm.addEventListener("submit", (e) => {
    let allValid = true; // Variable to track overall validity
    requirements.forEach(item => {
        const isValid = item.regex.test(passwordInput.value);
        if (!isValid) {
            allValid = false; // If any requirement is not met, set allValid to false
        }
    });

    if (!allValid) {
        e.preventDefault(); // Prevent form submission
        alert("Please ensure all password conditions are met."); // Alert message
    }
});
