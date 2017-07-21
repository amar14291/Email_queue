<?php 

/// mail setting
define('HOST', 'smtp.sendgrid.net');   // sets GMAIL as the SMTP server if gmail then smtp.gmail.com
define('PORT', '587'); // PORT OF SMTP SERVER
define('USERNAMEEMAIL', 'xyz'); // USERNAME OF Email
define('PASSWORDEMAIL', '123456'); // PASSWORD OF EMAIL
define('MAIL', 'support@xyz.com'); // SET reply to mail


/// database setting
define('SERVER', 'localhost'); //database server
define('USERNAME', 'root');    // database username
define('PASSWORD', 'hedge');   // database password
define('DBNAME', 'dbname');   // database name


class Emailschedule{
       
       
     public function sendemailstore($subject,$to_email,$content,$dbname,$ccemails,$attchment)
     {  
           // Create connection
    	   $conn = new mysqli(SERVER, USERNAME, PASSWORD, $dbname);
           // Check connection
		   if ($conn->connect_error) {
		   die("Connection failed: " . $conn->connect_error);
		   }

          $sql = "INSERT INTO $dbname.mail_queue (curr_timestamp,to_email,is_sent,hash_content,subject,cc_email,attachment_path)
		VALUES (now(), '$to_email','0','$content','$subject','$ccemails','$attchment')";

		//return $sql;

		if ($conn->query($sql) === TRUE) {
		    echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();


     }







}

?>
