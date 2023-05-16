<?php
require('database.php');

$db = new Database();
$query = "SELECT * FROM services";
$data = $db->getData($query);
$current_date = date('Y-m-d');
if ($data) {
  foreach ($data as $row) {
    $date_week = DateTime::createFromFormat('Y-m-d', $row["service_expiration"]);
    $date_month = DateTime::createFromFormat('Y-m-d', $row["service_expiration"]);
    $date_expiration = DateTime::createFromFormat('Y-m-d', $row["service_expiration"]);
    $date_expiration_add =  DateTime::createFromFormat('Y-m-d', $row["service_expiration"])->modify('+1 year');
    //skrati datum expiracie o 7 a 30 dni
    $date_week->modify('-7 day');
    $date_month->modify('-1 month');
    //formatovanie
    $formatted_date_week = $date_week->format('Y-m-d');
    $formatted_date_month = $date_month->format('Y-m-d');
    $formatted_date_expiration = $date_expiration->format('Y-m-d');
    $formatted_date_expiration_add = $date_expiration_add->format('Y-m-d');
    //prebehne kontrola ze ci dnesny datum a expiracia -30d alebo expiracia -7d sa rovnaju
    if ($current_date == $formatted_date_week || $current_date == $formatted_date_month) {
      $data = [
        'id_service' => $row['id_service'],
        'mail_subject' => 'Predĺženie ' . $row['service_name'],
        'mail_text' => 'Nejaký text',
      ];
      $query = "INSERT INTO email_queue (id_service, mail_subject, mail_text) VALUES ('{$data['id_service']}', '{$data['mail_subject']}', '{$data['mail_text']}')";
      $query_run = $db->conn->prepare($query);
      $query_run->execute();
    }

    //ak je aktualny datum rovnaky ako ten ked sluzba expiruje -> datum expiracie sa predlzi o rok a zrusi sa customer_answer
    if ($formatted_date_expiration == $current_date) {
      $query = "UPDATE services SET service_expiration = ?, customer_answer = null WHERE id_service = ?";
      $query_run = $db->conn->prepare($query);
      $query_run->bind_param('ss', $formatted_date_expiration_add, $row['id_service']);
      $query_run->execute();
    }
  }
}
