<?php
require('database.php');

function setDate($success, $row, $db)
{
  $current_date = date('Y-m-d');
  $query = "UPDATE mail_queue SET date_sent = ?, sending_result = ? WHERE id_queue = ?";
  $query_run = $db->conn->prepare($query);
  $query_run->bind_param('sis', $current_date, $success, $row['id_queue']);
  $query_run->execute();
}

$db = new Database();
$query = "SELECT * FROM mail_queue WHERE date_sent is NULL or sending_result = 0 LIMIT 100";
$data = $db->getData($query);

$htmlContent = ' 
    <html> 
    <head> 
        <title>Welcome to CodexWorld</title> 
    </head> 
    <body> 
        <h1>Vaša služba onedlho vyprší</h1> 
        <table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%;"> 
            <tr> 
                <th>Name:</th><td>CodexWorld</td> 
            </tr> 
            <tr style="background-color: #e0e0e0;"> 
                <th>Email:</th><td>contact@codexworld.com</td> 
            </tr> 
            <tr> 
                <th>Website:</th><td><a href="http://www.codexworld.com">www.codexworld.com</a></td> 
            </tr> 
        </table> 
    </body> 
    </html>';

if ($data) {
  foreach ($data as $row) {
    $to = $row['mail_to'];
    $subject = $row['mail_subject'];
    // $message = $row['mail_text'];
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: notifikacie@ukf.sk' . "\r\n" .
      'Reply-To: notifikacie@ukf.sk' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

    // Send the email
    if (mail($to, $subject, $htmlContent, $headers)) {
      setDate(1, $row, $db);
    } else {
      setDate(0, $row, $db);
    }
  }
}
