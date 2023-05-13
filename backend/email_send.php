<?php
require('database.php');

function setDate($success, $row, $db) {
  $current_date = date('Y-m-d');
  $query = "UPDATE mail_queue SET date_sent = ?, sending_result = ? WHERE id_queue = ?";
  $query_run = $db->conn->prepare($query);
  $query_run->bind_param('sis', $current_date, $success, $row['id_queue']);
  $query_run->execute();
}

$db = new Database();
$query = "SELECT * FROM mail_queue WHERE date_sent is NULL or sending_result = 0 LIMIT 100";
$data = $db->getData($query);
if ($data) {
    foreach ($data as $row) {
      $to = $row['mail_to'];
      $subject = $row['mail_subject'];
      $message = $row['mail_text'];
      $headers = 'From: notifikacie@vegasolutions.eu' . "\r\n" .
      'Reply-To: yourname@yourdomain.com' . "\r\n" .
      'Content-Type: text/plain; charset=UTF-8' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

  // Send the email
    if (mail($to, $subject, $message, $headers)) {
      setDate(1, $row, $db);
    } else {
      setDate(0, $row, $db);
    }
  }
}
 ?>
