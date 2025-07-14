<?php
// app/controllers/MidtransWebhookController.php

// Memuat model-model yang dibutuhkan
require_once __DIR__ . '/../models/MidtransTransaction.php';
require_once __DIR__ . '/../models/QurbanTransaction.php';
require_once __DIR__ . '/../models/ZakatTransaction.php';
require_once __DIR__ . '/../models/HajjSaving.php';
require_once __DIR__ . '/../models/StatusLog.php';
// Memuat library Midtrans SDK
require_once __DIR__ . '/../../vendor/autoload.php';
// Memuat helper untuk logging (opsional, bisa pakai error_log)
require_once __DIR__ . '/../../includes/helper.php'; // Untuk dd() jika mau debug

class MidtransWebhookController
{
    private $midtransTransactionModel;
    private $qurbanTransactionModel;
    private $zakatTransactionModel;
    private $hajjSavingModel;
    private $statusLogModel;
    private $midtransConfig;

    public function __construct()
    {
        $this->midtransTransactionModel = new MidtransTransaction();
        $this->qurbanTransactionModel = new QurbanTransaction();
        $this->zakatTransactionModel = new ZakatTransaction();
        $this->hajjSavingModel = new HajjSaving();
        $this->statusLogModel = new StatusLog();
        $this->midtransConfig = require __DIR__ . '/../../config/midtrans.php';

        // Konfigurasi Midtrans SDK
        \Midtrans\Config::$serverKey = $this->midtransConfig['server_key'];
        \Midtrans\Config::$isProduction = $this->midtransConfig['is_production'];
        \Midtrans\Config::$isSanitized = $this->midtransConfig['is_sanitized'];
        \Midtrans\Config::$is3ds = $this->midtransConfig['is_3ds'];
    }

    /**
     * Menangani callback (webhook) dari Midtrans.
     * Fitur: Webhook endpoint (callback), Autoupdate status pembayaran.
     */
    public function handle()
    {
        // Pastikan ini adalah permintaan POST dari Midtrans
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['message' => 'Method Not Allowed']);
            exit();
        }

        $notification = new \Midtrans\Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;
        $paymentType = $notification->payment_type;
        $grossAmount = $notification->gross_amount; // Jumlah pembayaran
        $transactionTime = $notification->transaction_time;

        // Cari transaksi di database berdasarkan Midtrans Order ID
        $midtransTransaction = $this->midtransTransactionModel->findByMidtransOrderId($orderId);

        if (!$midtransTransaction) {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'Transaction not found in our database.']);
            exit();
        }

        // Update status transaksi Midtrans di database
        $this->midtransTransactionModel->update(
            $midtransTransaction['id'],
            [
                'status' => $transactionStatus,
                'payment_type' => $paymentType,
                'transaction_time' => $transactionTime,
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );

        // Log status perubahan
        $this->statusLogModel->logStatus(
            $midtransTransaction['user_id'],
            $midtransTransaction['ibadah_type'],
            $transactionStatus,
            $midtransTransaction['id']
        );

        // Logika untuk mengupdate status transaksi ibadah terkait
        $ibadahType = $midtransTransaction['ibadah_type'];
        $relatedId = explode('-', $orderId)[1]; // Ambil ID transaksi ibadah dari order_id

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                if ($fraudStatus == 'accept') {
                    $newStatus = 'paid';
                    if ($ibadahType === 'qurban') {
                        $this->qurbanTransactionModel->update($relatedId, ['status' => $newStatus, 'paid_amount' => $grossAmount, 'updated_at' => date('Y-m-d H:i:s')]);
                        // Jika cicilan, update paid_amount saja
                        // Jika lunas, update status ke 'paid'
                    } elseif ($ibadahType === 'zakat') {
                        $this->zakatTransactionModel->update($relatedId, ['status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')]);
                    } elseif ($ibadahType === 'hajj_saving_deposit') {
                        // Tambahkan jumlah ke tabungan haji
                        $this->hajjSavingModel->addAmount($relatedId, $grossAmount);
                        // Cek apakah target dana sudah tercapai
                        // $saving = $this->hajjSavingModel->find($relatedId);
                        // if ($saving['current_amount'] >= $saving['target_amount']) {
                        //     $this->hajjSavingModel->update($relatedId, ['status' => 'completed', 'updated_at' => date('Y-m-d H:i:s')]);
                        // }
                    }
                }
                break;
            case 'pending':
                // Status pending, tidak ada update lebih lanjut ke transaksi ibadah
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $newStatus = 'failed';
                if ($ibadahType === 'qurban') {
                    $this->qurbanTransactionModel->update($relatedId, ['status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')]);
                } elseif ($ibadahType === 'zakat') {
                    $this->zakatTransactionModel->update($relatedId, ['status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')]);
                } elseif ($ibadahType === 'hajj_saving_deposit') {
                    // Tidak ada perubahan pada current_amount
                }
                break;
        }

        http_response_code(200); // Beri tahu Midtrans bahwa notifikasi diterima
        echo json_encode(['message' => 'Notification received and processed.']);
        exit();
    }
}