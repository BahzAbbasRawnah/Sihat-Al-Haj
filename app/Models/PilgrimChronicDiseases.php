<?php
class PilgrimChronicDiseases
{
    protected $db;
    protected $table = 'pilgrim_chronic_diseases';

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Get chronic diseases for a specific pilgrim
    public function getByPilgrimId($pilgrimId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE pilgrim_id = :pilgrim_id");
        $stmt->bindParam(':pilgrim_id', $pilgrimId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
