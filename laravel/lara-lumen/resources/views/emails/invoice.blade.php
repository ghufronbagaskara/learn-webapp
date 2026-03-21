{{-- resources/views/emails/invoice.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pesanan</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h2 style="margin-bottom: 0;">LumenEcommerce</h2>
    <p style="margin-top: 4px; color: #6b7280;">Invoice Pesanan</p>

    <hr>

    <h3>Informasi Order</h3>
    <p><strong>ID Order:</strong> #{{ $order->id }}</p>
    <p><strong>Tanggal:</strong> {{ $order->created_at }}</p>
    <p><strong>Status:</strong> {{ strtoupper($order->status) }}</p>

    <h3>Informasi Customer</h3>
    <p><strong>Nama:</strong> {{ $order->user->name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>

    <h3>Detail Item</h3>
    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; border-color: #d1d5db;">
        <thead style="background: #f3f4f6;">
            <tr>
                <th align="left">Nama Produk</th>
                <th align="right">Qty</th>
                <th align="right">Harga Satuan</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td align="right">{{ $item->quantity }}</td>
                    <td align="right">Rp {{ number_format((float) $item->price_per_unit, 2, ',', '.') }}</td>
                    <td align="right">Rp {{ number_format((float) $item->price_per_unit * $item->quantity, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 16px;"><strong>Total Harga:</strong> Rp {{ number_format((float) $order->total_price, 2, ',', '.') }}</p>
    <p><strong>Kode Referensi Pembayaran:</strong> {{ optional($order->payment)->payment_ref ?? '-' }}</p>

    <hr>
    <p>Terima kasih telah berbelanja di LumenEcommerce.</p>
</body>
</html>
