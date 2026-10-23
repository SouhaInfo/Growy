document.addEventListener("DOMContentLoaded", function () {   // wait for the html file to load first 
    const form = document.querySelector(".comment-form");     // form tag 
    const nameInput = form.querySelector('#name');            // get the name input field
    const commentInput = form.querySelector('#comment');      // get the comment input field 
    const nameMessage = document.querySelector("#nameMessage");     // message d'error 
    const commentMessage = document.querySelector("#commentMessage");  // message d'error 

    form.addEventListener("submit", function (event) {    // wiat for the user's behavior to submit the form 
        let isValid = true;

        // update the innerhtml of the name and comment mesaages to be fresh 
        nameMessage.textContent = "";
        commentMessage.textContent = "";

        
        const nameValue = nameInput.value.trim(); // take the value of the name entry in the input field = value of input field 
        if (nameValue === "") {  // empty name 
            isValid = false;     // not valid 
            nameInput.style.borderColor = "red"; // implement a styling to the input field 
            nameMessage.textContent = "*Name is required.";   // update the inner html of the error message 
            nameMessage.style.color = "red";  // style the previous new added content to my tag  
        } else {
            nameInput.style.borderColor = "green"; // if everything was valid the field will be styled in green 
        }

        // to validate the comment 
        const commentValue = commentInput.value.trim(); // store the value = content inside the input tag of the comment 
        if (commentValue === "") {   // empty comment 
            isValid = false;
            commentInput.style.borderColor = "red"; // style the input tag with red 
            commentMessage.textContent = "*Comment is required.";   // add an innerhtml content inside my error message 
            commentMessage.style.color = "red";  // style the error messagw ith red 
        } else {
            commentInput.style.borderColor = "green";  // everything is good style the input tag with green 
        }

        
        if (!isValid) {    // prevent submission if isValid takes the value false in at least one of them 
                            // if in at least one of them the value oif isValid was set to false then it is impossible to submit the form  
            event.preventDefault(); 
        }
    });
});
