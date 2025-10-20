<?php

namespace App\Models;

use App\Core\Model;

class HealthReport extends Model
{
    protected $table = 'Health_Reports';
    protected $primaryKey = 'report_id';

    public function getRecentReports($limit = 20)
    {
        $query = "SELECT hr.*, p.*, u.first_name_ar, u.last_name_ar,
                         reporter.first_name_ar as reporter_name_ar, reporter.last_name_ar as reporter_last_ar
                  FROM {$this->table} hr
                  JOIN Pilgrims p ON hr.pilgrim_id = p.pilgrim_id
                  JOIN Users u ON p.user_id = u.user_id
                  JOIN Users reporter ON hr.reporter_user_id = reporter.user_id
                  ORDER BY hr.report_date DESC
                  LIMIT :limit";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByPilgrimId($pilgrimId)
    {
        $query = "SELECT hr.*, reporter.first_name_ar as reporter_name_ar, reporter.last_name_ar as reporter_last_ar
                  FROM {$this->table} hr
                  JOIN Users reporter ON hr.reporter_user_id = reporter.user_id
                  WHERE hr.pilgrim_id = :pilgrim_id
                  ORDER BY hr.report_date DESC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}