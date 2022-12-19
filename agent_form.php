<?php

require 'config.php';

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$postdata = file_get_contents('php://input');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($postdata) && !empty($postdata)) {
    $request = json_decode($postdata);

    $recaptcha = $request->token;

    // Put secret key here, which we get
    // from google console
    $secret_key = '6LeTGIUjAAAAALEpz5BTkYuQG1F0bU7UCmAiKDSI';

    // Hitting request to the URL, Google will
    // respond with success or error scenario
    $url =
        'https://www.google.com/recaptcha/api/siteverify?secret=' .
        $secret_key .
        '&response=' .
        $recaptcha;

    // Making request to verify captcha
    $response = file_get_contents($url);

    // Response return by google is in
    // JSON format, so we have to parse
    // that json
    $response = json_decode($response);

    // Checking, if response is true or not
    if ($response->success == true) {
        // echo '<script>alert("Google reCAPTACHA verified")</script>';

        $request = json_decode($postdata);

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $nationality = $request->selected_nationality;
        $designation = $request->designation;

        $countries = $request->countries;
        // echo $countries;
        $country = $request->selected_country;

        $city = $request->selected_city;
        $email = $request->email;
        $phone = $request->mobile;
        $address = $request->address;
        

        $countries1 = $countries;

        $nationalities1 = $countries;


        foreach($countries1 as $key)
        {
            if($key->id==$country)
            {

                $country_name = $key->name;
                break;

            }
        }

        foreach($nationalities1 as $key)
        {
            if($key->id==$nationality)
            {

                $nationality_name = $key->name;
                break;

            }
        }

        

        

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = 'jeroldjeis06@gmail.com'; //SMTP username
            $mail->Password = 'evhskrktlltqaxle'; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('jeroldjeis06@gmail.com', 'Server');
            $mail->addAddress('jeroldjeis06@gmail.com', 'Management'); //Add a recipient

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = 'Agent From Details';

            $message = '<html><body>';
            $message .=
                "<table align='center' width='100%' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff;  border: 5px groove; font-family:Verdana, Geneva, sans-serif;'>";
            $message .= "<thead>
      <tr height='100'>  
      <th colspan='4' style='background-color:#333132; font-family:Verdana, Geneva, sans-serif; color:#fff; font-size:25px; border: 5px groove;'>
      <img src='https://abiteoffrance.com/marquis-galleria/static/media/Marquis_Galleria_Logo.png' alt='logo' title='logo' style='height:auto; width:50%; max-width:50%;' />      
      </th> 
      </tr>
                </thead>";
            $message .=
                "<tbody>

          <th colspan='4' style='background-color:#fff; font-family:Verdana, Geneva, sans-serif; color:#b2934c; font-size:25px; border: 5px groove;'>      
            Agent Form
            </th>      
      
          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>Name</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $firstname .
                ' ' .
                $lastname .
                " </td>         
          </tr>

          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>Nationality</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $nationality_name .
                "</td>         
          </tr>

          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>Designation</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $designation .
                "</td>         
          </tr>

          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>Country</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $country_name .
                "</td>         
          </tr>

          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>City</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $city .
                "</td>         
          </tr>

          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>Email</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $email .
                "</td>         
          </tr>

          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>Phone</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $phone .
                "</td>         
          </tr>
          
          <tr align='left' height='50'>
          <th style='font-size:15px; border: 1px groove; padding-left: 15px;'>Address</th>
          <td style='font-size:15px; border: 1px groove; padding-left: 15px;'>" .
                $address .
                "</td>         
          </tr>         
          </tbody>";
            $message .= '</table>';
            $message .= '</body></html>';
            $mail->Body = $message;
            $mail->AltBody =
                'This is the body in plain text for non-HTML mail clients';
            // $mail->send();

            // $sql = "INSERT INTO appointment_details(first_name, last_name, gender, dob, phone, email, cancer_type, diagnosed, comment) VALUES ('$first_name','$last_name','$gender','$dob','$phone','$email','$cancer_type','$diagnosed','$comment')";
            $sql = "INSERT INTO agent_form (first_name, last_name, nationality, designation, country, city, email, phone, address) VALUES ('$firstname','$lastname','$nationality_name','$designation','$country_name','$city','$email','$phone','$address')";
            if (mysqli_query($db, $sql) && $mail->send()) {
                http_response_code(201);
            } else {
                http_response_code(422);
            }

            //     if (mysqli_query($conn, $sql) && $mail->send()) {
            //         echo "<script>
            // alert('Your appointment request is sent successfully. We will contact you soon.');
            // window.location.href = 'contactus.php';
            // </script>";
            //     } else {
            //         echo "<script>
            // alert('Your appointment is not booked please Try again.');
            // window.location.href = 'contactus.php';
            // </script>";
            //     }
        } catch (Exception $e) {
            echo "<script> 
        alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');       
        </script>";
        }
    } else {
        echo "<script> 
        alert('Error in Google reCAPTACHA');       
        </script>";
    }
}
