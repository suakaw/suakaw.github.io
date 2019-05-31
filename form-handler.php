<?php

	if(isset($_POST['uemail'])) {
		// includes phpmailer class used for sending notification and auto-reply emails
		require_once('class.phpmailer.php');
		
		// Store form values in variables
		$name = $_POST['uname'];
		$email = $_POST['uemail'];
		$message = $_POST['umessage'];
		
		// Set to true to enable sending notification email to a specified email id
		$sendNotificationEmail = true;
		
		// Notification email type, specify either plain or html
		$notification_email_type = 'plain';
		
		// Set to true to enable auto-reply emails
		$sendAutoReplyEmail = false;
		
		// Auto-reply email type, specify either plain or html
		$auto_reply_email_type = 'plain';
		
		// Set to true to save data to a MySQL database 
		$saveToDatabase = false;
		
		
		try {
			// Send notification email
			if ($sendNotificationEmail) {
				
				// Creates notification mailer object
				$notification_mailer = new PHPMailer();
				
				// Set from email id
				// Should be email id on the domain where this script is going to be hosted otherwise emails might not go through due to security concerns
				$notification_mailer->SetFrom("youremail@yourdomain.com", "Theme Bakers");
				
				// Set reply-to email id
				//$notification_mailer->AddReplyTo("youremail@yourdomain.com", "Website Name or Something Else");
				
				// Set the email id to which notification email will be sent
				// You can send email to more than one email ids by duplicating the line below
				$notification_mailer->AddAddress("youremail@yourdomain.com");
				
				// Set subject of notification email
				$notification_mailer->Subject = "Biopic HTML Template Contact Form Submission";
				
				if ($notification_email_type == 'html') {
					$notification_email_body = "Hi,<br>A user <b>$name</b> has contacted with the following particulars:<br>Name: $name<br>Email: $email<br>Message: $message";
					$notification_mailer->MsgHTML($notification_email_body);
				}
				else {
					$notification_email_body = "Hi,\nA user has contacted with following particulars:\nName: $name\nEmail: $email\nMessage: $message";
					$notification_mailer->Body = $notification_email_body;
				}
				
				$notification_email_status = $notification_mailer->Send();
			}
			
			// Send auto-reply email back to user
			if ($sendAutoReplyEmail) {
				
				// Creates auto-reply mailer object
				$auto_reply_mailer = new PHPMailer();
				
				// Set from email id
				// Should be email id on the domain where this script is going to be hosted otherwise emails might not go through due to security concerns
				$auto_reply_mailer->SetFrom("youremail@yourdomain.com", "Website Name or Something Else");
				
				// Set reply-to email id
				//$auto_reply_mailer->AddReplyTo("noreply@themebakers.com", "Website Name or Something Else");
				
				// Email will be sent to the email id entered by the user
				$auto_reply_mailer->AddAddress("$email");
				
				// Set subject of auto-reply email
				$auto_reply_mailer->Subject = "Thanks for contacting us";
				
				if ($auto_reply_email_type == 'html') {
					$auto_reply_email_body = "Hi $name,<br><h1>Thank you for contacting us</h1><p>We will get in touch with you asap.</p>";
					$auto_reply_mailer->MsgHTML($auto_reply_email_body);
				}
				else {
					$auto_reply_email_body = "Hi $name,\nThank you for contacting us\nWe will get in touch with you asap.";
					$auto_reply_mailer->Body = $auto_reply_email_body;
				}
				
				$auto_reply_email_status = $auto_reply_mailer->Send();
			}
			
			// Save to a MySQL database table
			if ($saveToDatabase) {
				// Connect to MySQL, make sure to set your own database values
				mysql_connect('database-host', 'database-username', 'database-password');
				
				// Select the database
				mysql_select_db('database-name');
				
				
				// Build the query, change contacts_table_name with the table name of your database
				$query = "INSERT INTO contacts_table_name SET ";
				$query .= "`name` = '" . mysql_real_escape_string($name) . "',";
				$query .= "`email` = '" . mysql_real_escape_string($email) . "',";
				$query .= "`message` = '" . mysql_real_escape_string($message) . "';";
				
				// Execute the query
				mysql_query($query);
				
				// Close the connection
				mysql_close();
			}
			
			if ($notification_email_status) {
				echo json_encode(array("status"=>"success"));
			}
			else {
				echo json_encode(array("status"=>"error"));
			}
		}
		catch (Exception $e) {
			// An exception has occurred, show error message
			echo json_encode(array("status"=>"error"));
		}
	}
	else {
		echo json_encode(array("status"=>"error"));
	}
?>