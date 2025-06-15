<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            background-color: #f9f9f9;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #e5e7eb;
            color: #111827;
        }
        tfoot td {
            font-weight: 600;
            background-color: #f3f4f6;
        }

        .blue-text {
            color: #2563eb;
            font-size: 14px;
        }
        .green-text {
            color: #059669;
            font-size: 14px;
        }
        .red-text {
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2 style="color: #000000; font-weight: 700;">Laporan Penjualan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Modal</th>
                <th>Total</th>
                <th>Pemilik</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $laporan->kode_barang }}</td>
                <td>{{ $laporan->nama_barang }}</td>
                <td>{{ $laporan->jumlah }}</td>
                <td>{{ number_format($laporan->modal, 0, ',', '.') }}</td>
                <td>{{ number_format($laporan->total, 0, ',', '.') }}</td>
                <td>{{ $laporan->kasir }}</td>
                <td>{{ \Carbon\Carbon::parse($laporan->transaction_date)->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right">Total Terjual</td>
                <td class="blue-text">{{ number_format($totalTerjual, 0, ',', '.') }}</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Transaksi</td>
                <td class="green-text">{{ number_format($totalTransaksi, 0, ',', '.') }}</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Keuntungan</td>
                <td class="{{ $totalKeuntungan < 0 ? 'red-text' : 'blue-text' }}">
                    {{ number_format($totalKeuntungan, 0, ',', '.') }}
                </td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
