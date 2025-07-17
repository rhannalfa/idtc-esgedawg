<?php
require_once __DIR__ . '/BaseModel.php';

class ZakatMalDetail extends BaseModel
{
    protected $table = 'zakat_mal_details';

    public function createDetail($data)
    {
        $columns = ['zakat_transaction_id', 'kategori', 'total_harta', 'persen_zakat', 'keterangan'];
        $filtered = [];
        foreach ($columns as $col) {
            $filtered[$col] = $data[$col] ?? null;
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (" . implode(',', array_keys($filtered)) . ") VALUES (" . rtrim(str_repeat('?, ', count($filtered)), ', ') . ")");
            $stmt->execute(array_values($filtered));
            return true;
        } catch (PDOException $e) {
            error_log('Error inserting zakat mal detail: ' . $e->getMessage());
            return false;
        }
    }
}
?>
