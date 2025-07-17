<?php
require_once __DIR__ . '/BaseModel.php';

class ZakatFitrahDetail extends BaseModel
{
    protected $table = 'zakat_fitrah_details';

    public function createDetail($data)
    {
        $columns = ['zakat_transaction_id', 'kepala_keluarga', 'jumlah_anggota', 'metode', 'total_zakat'];
        $filtered = [];
        foreach ($columns as $col) {
            $filtered[$col] = $data[$col] ?? null;
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (" . implode(',', array_keys($filtered)) . ") VALUES (" . rtrim(str_repeat('?, ', count($filtered)), ', ') . ")");
            $stmt->execute(array_values($filtered));
            return true;
        } catch (PDOException $e) {
            error_log('Error inserting zakat fitrah detail: ' . $e->getMessage());
            return false;
        }
    }
}
?>
