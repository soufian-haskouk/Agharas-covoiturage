https://ton-site.com/update_status.php?key=12345secure
http://localhost/myProjet/admin/update_status.php?key=
https://console.cron-job.org/jobs
UPDATE trajet
SET status = 'completed'
WHERE status = 'active'
  AND CONCAT(date_trajet, ' ', heure_depart) < NOW();

  // --------------------------------------------------------


