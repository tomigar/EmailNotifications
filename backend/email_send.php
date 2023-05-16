<?php
require('database.php');

function setDate($success, $row, $db)
{
  $current_date = date('Y-m-d');
  $query = "UPDATE email_queue SET date_sent = ?, sending_result = ? WHERE id_queue = ?";
  $query_run = $db->conn->prepare($query);
  $query_run->bind_param('sis', $current_date, $success, $row['id_queue']);
  $query_run->execute();
}

$db = new Database();
$query = "SELECT * FROM email_queue 
inner join services 
on email_queue.id_service = services.id_service
WHERE date_sent is NULL or sending_result = 0 LIMIT 100";
$data = $db->getData($query);




if ($data) {
  foreach ($data as $row) {
    $to = $row['customer_email'];
    $subject = $row['mail_subject'];
    $htmlContent = ' 
    <html> 
    <head> 
        <title>Welcome to CodexWorld</title> 
    </head> 
    <body> 
        <h1>Vaša služba onedlho vyprší</h1> 
        <h2>Prajete si ju predĺžiť?</h2>
          <a href="http://localhost/UKF/SJ/EmailNotifications/email_answer_positive.php?service=' . $row['id_service'] . '">Áno</a>
          <a href="http://localhost/UKF/SJ/EmailNotifications/email_answer_negative.php?service=' . $row['id_service'] . '">Nie</a>
    </body> 
    </html>';
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
