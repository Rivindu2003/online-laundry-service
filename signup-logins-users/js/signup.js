const passwordInput = document.querySelector("#password");
const eyeIcon = document.querySelector(".pass-field i");
const requirementList = document.querySelectorAll("#password-conditions li");

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

eyeIcon.addEventListener("click", () => {
    // Toggle the password input type between "password" and "text"
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";

    // Update the eye icon class based on the password input type
    eyeIcon.className = `fa-solid fa-eye${passwordInput.type === "password" ? "" : "-slash"}`;
});
