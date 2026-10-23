document.addEventListener("DOMContentLoaded", () => {   // just to make sure that the DOM was fully uploaded 
    const commentBox = document.getElementById("comment");  // select the comment box 
    const charCount = document.getElementById("charCount"); // select the char counter 

    commentBox.addEventListener("input", () => {     // input: every time there is something being changed in the comment box the event is triggered 
        const currentLength = commentBox.value.length;  // cureent number of characters in the comment box 
        charCount.textContent = `${currentLength}/1000`; // updates the text at the character counter form 0/1000 to the new value 
    });                                                  // $ for value preservation like in Linux 
});
