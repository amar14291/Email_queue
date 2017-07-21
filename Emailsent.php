<?php 

include('application/libraries/Emailschedule.php');

class Emailsent{


       function __construct()
       {
          
         // Create connection
           $conn = new mysqli(SERVER, USERNAME, PASSWORD, DBNAME);
           // Check connection
           if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
           }

        
        $sql = "select * from ".DBNAME.".mail_queue where is_sent=0";
        $result = $conn->query($sql);
        $realName="";
       if ($result->num_rows > 0) {
       // output data of each row
        while($row = $result->fetch_assoc()) {
        $id=$row['id'];
        $mailto=$row['to_email'];
        $subject=$row['subject'];
        $htmlbody=$row['hash_content'];
        $ccEmail=$row['cc_email'];
        $attachment=$row['attachment_path'];
        
        if(empty($ccEmail))
            $ccEmail="";
      
        if(empty($attachment))
            $attachment="";
     

        $this->Email($mailto, $realName, $subject, $htmlbody, $attachment, $ccEmail);
      
        
        $sqlupdate="update ".DBNAME.".mail_queue set is_sent=1,send_datetime=now() where id=$id";
        $updateresult=$conn->query($sqlupdate);

    }
      } else {
             echo "0 results";
             }
       

       }

       
       function Email($emailTo, $realName, $subject, $htmlbody, $attachment, $ccEmail) 
       {
          
         
        date_default_timezone_set('America/Toronto');
        require_once ('class.phpmailer.php');
        require_once ('class.smtp.php');
        
        $mail = new PHPMailer();
        $mail -> IsSMTP();
        // telling the class to use SMTP
        $mail -> Host = "mail.yourdomain.com";
        $mail -> SMTPDebug = 0;
        $mail -> SMTPAuth = true;
        $mail -> Host = HOST;
        $mail -> Port = PORT;
        $mail -> Username = USERNAMEEMAIL;
        $mail -> Password = PASSWORDEMAIL;
        //$mail -> SetFrom('support@cuztomise.com', "CUZTOMISE");
        $mail -> AddReplyTo(MAIL, $realName);
        
       if($ccEmail)
        {   
            $ccEmail=explode(',', $ccEmail);
            foreach ($ccEmail as $key => $value) {
            $mail->AddCC($value, $value);
          }
        }
        
        $mail -> IsHTML(true);
        $mail -> Subject = $subject;
        $mail -> Body = $htmlbody;
        $address = $emailTo;
        $mail -> AddAddress($address, $realName);
        
        if($attachment)
        {   
             $attachment=explode(',', $attachment);

            foreach ($attachment as $key => $value) {
            print($value);
            print_r('<br/>');
            $mail ->AddAttachment($value);
            }
        }

        if (!$mail -> Send()) {
               // echo "Mailer Error: " . $mail -> ErrorInfo;
        } else {
            //echo "Message sent!"; 
        }
    }

}


$objemailsent = new Emailsent();