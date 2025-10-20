<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/MedicalTeam/RequestController.php';

$controller = new \App\Controllers\MedicalTeam\RequestController();
$controller->updateRequestStatus();