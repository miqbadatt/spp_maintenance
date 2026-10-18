<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\MidtransCallbackController;
use App\Models\Pembayaran;

$pembayaran = Pembayaran::first();
if (!$pembayaran) {
    echo "No pembayaran found to test.\n";
    exit;
}

$payload = [
    'order_id' => $pembayaran->order_id,
    'status_code' => '200',
    'gross_amount' => (string) $pembayaran->jumlah_bayar,
    'signature_key' => hash('sha512', $pembayaran->order_id . '200' . $pembayaran->jumlah_bayar . config('midtrans.server_key')),
    'transaction_status' => 'settlement',
    'fraud_status' => 'accept',
    'payment_type' => 'bank_transfer',
    'transaction_id' => 'dummy-txn-123',
    'settlement_time' => now()->toDateTimeString(),
];

$request = Request::create('/midtrans/callback', 'POST', $payload);
$controller = new MidtransCallbackController();

try {
    $response = $controller->handle($request);
    echo "Success! Response: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
