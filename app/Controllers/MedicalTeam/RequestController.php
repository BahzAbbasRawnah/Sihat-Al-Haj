<?php

namespace App\Controllers\MedicalTeam;

class RequestController {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn; // Use the existing database connection
    }

    public function updateRequestStatus() {
        header('Content-Type: application/json');
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Invalid input data']);
            return;
        }

        $requestId = $input['request_id'] ?? null;
        $teamId = $input['team_id'] ?? null;
        $status = $input['status'] ?? null;

        if (!$requestId || !$status) {
            echo json_encode(['success' => false, 'message' => 'Request ID and status are required']);
            return;
        }

        try {
            // Start transaction
            $this->db->beginTransaction();

            // Update request status and assigned team
            $sql = "UPDATE Medical_Requests 
                    SET status = :status,
                        assigned_team_id = :team_id,
                        resolved_at = CASE WHEN :status = 'resolved' THEN NOW() ELSE resolved_at END
                    WHERE request_id = :request_id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':status' => $status,
                ':team_id' => $teamId,
                ':request_id' => $requestId
            ]);

            // If assigning a team, update team status to on_mission
            if ($teamId && $status === 'in_progress') {
                $sql = "UPDATE Medical_Teams 
                        SET status = 'on_mission'
                        WHERE team_id = :team_id";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':team_id' => $teamId]);

                // Create an emergency response record
                $sql = "INSERT INTO Emergency_Responses 
                        (request_id, medical_team_id, response_details_ar, response_details_en, response_time)
                        VALUES (:request_id, :team_id, :details_ar, :details_en, NOW())";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':request_id' => $requestId,
                    ':team_id' => $teamId,
                    ':details_ar' => 'تم تعيين الفريق الطبي للطلب',
                    ':details_en' => 'Medical team assigned to request'
                ]);
            }

            $this->db->commit();
            echo json_encode(['success' => true]);

        } catch (\PDOException $e) {
            $this->db->rollBack();
            error_log("Medical Request Update Error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Database error occurred'
            ]);
        }
    }
}