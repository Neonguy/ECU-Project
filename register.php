<?php
// This code checks if the $_POST variable (which contains form data submitted using the POST method)
// contains a key of 'submit' (the name of the submit button), and if so it prints the form data. 
if ($_POST)
{
  echo '<h3>Form submitted successfully!</strong></h3>';

  // The <pre> tags ensure that the layout/whitespace is preserved, for easy reading.
  // See https://www.php.net/manual/en/function.print-r.php for details about print_r().
  echo '<pre>';
  print_r($_POST);
  echo '</pre>';
}

// If the form has not been submitted, the PHP doesn't do/display anything...
?>