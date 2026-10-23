document.getElementById('print-button').addEventListener('click', function() {
   var printContent = document.getElementById('print-area');
   var printWindow = window.open('', '', 'height=500,width=800');

   printWindow.document.write('<html><head><title>Print Article</title>');
   printWindow.document.write('<link rel="stylesheet" href="../Assets/CSS/Article.css">');  
   printWindow.document.write('</head><body>');
   printWindow.document.write(printContent.innerHTML);  // This grabs the content inside the article tag
   printWindow.document.write('</body></html>');

   printWindow.document.close(); // Close the document to finish writing
   printWindow.print();  // Open the print dialog
});



