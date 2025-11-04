<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket Antrian - {{ $antrian->loket->code ?? '' }}{{ $antrian->nomor_antrian }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #f5f5f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .ticket-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 700px;
            width: 100%;
            border: 3px solid #10b981;
        }
        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px dashed #e5e7eb;
        }
        .header .logo {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #1e40af;
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            color: #64748b;
            font-size: 16px;
            font-weight: 600;
        }
        .nomor-antrian-section {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 50px 40px;
            border-radius: 15px;
            margin: 30px 0;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
            position: relative;
            overflow: hidden;
        }
        .nomor-antrian-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: movePattern 20s linear infinite;
        }
        @keyframes movePattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 30px); }
        }
        .nomor-antrian-section .label {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }
        .nomor-antrian-section .nomor {
            font-size: 96px;
            font-weight: 900;
            letter-spacing: 12px;
            font-family: 'Courier New', monospace;
            text-shadow: 0 0 20px rgba(255,255,255,0.5);
            position: relative;
            z-index: 1;
        }
        .info-section {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px dashed #e5e7eb;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            font-size: 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-item .label {
            color: #64748b;
            font-weight: 600;
            text-align: left;
        }
        .info-item .value {
            color: #1e293b;
            font-weight: 700;
            text-align: right;
            font-size: 18px;
        }
        .status-badge {
            display: inline-block;
            background: #fbbf24;
            color: #92400e;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 16px;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px dashed #e5e7eb;
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.8;
        }
        .footer p {
            margin-bottom: 8px;
        }
        .barcode-area {
            margin-top: 25px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
            border: 2px dashed #d1d5db;
        }
        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 4px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .ticket-container {
                box-shadow: none;
                border: 2px solid #10b981;
                padding: 30px;
                page-break-inside: avoid;
            }
            .nomor-antrian-section::before {
                animation: none;
            }
            @page {
                margin: 10mm;
            }
        }
        .no-print {
            display: none;
        }
        @media screen {
            .print-button {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #10b981;
                color: white;
                padding: 15px 30px;
                border-radius: 50px;
                font-weight: 700;
                font-size: 16px;
                border: none;
                cursor: pointer;
                box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
                transition: all 0.3s;
            }
            .print-button:hover {
                background: #059669;
                transform: translateY(-2px);
                box-shadow: 0 15px 40px rgba(16, 185, 129, 0.4);
            }
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <div class="logo">🏥</div>
            <h1>RS Sehat Selalu</h1>
            <p>Sistem Antrian Digital</p>
        </div>

        <div class="nomor-antrian-section">
            <div class="label">NOMOR ANTRIAN ANDA</div>
            <div class="nomor">{{ $antrian->loket->code ?? '' }}{{ $antrian->nomor_antrian }}</div>
        </div>

        <div class="info-section">
            <div class="info-item">
                <span class="label">Layanan:</span>
                <span class="value">{{ $antrian->loket->nama_loket ?? 'Loket' }}</span>
            </div>
            <div class="info-item">
                <span class="label">Tanggal:</span>
                <span class="value">{{ $antrian->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span class="label">Waktu:</span>
                <span class="value">{{ $antrian->created_at->format('H:i:s') }}</span>
            </div>
            <div class="info-item">
                <span class="label">ID Antrian:</span>
                <span class="value">#{{ str_pad($antrian->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <div class="status-badge">Status: {{ strtoupper($antrian->status) }}</div>

        <div class="barcode-area">
            <div class="barcode-text">{{ $antrian->loket->code ?? '' }}{{ $antrian->nomor_antrian }}</div>
        </div>

        <div class="footer">
            <p><strong>Harap simpan tiket ini dengan baik</strong></p>
            <p>Tunggu sampai nomor antrian Anda dipanggil</p>
            <p style="margin-top: 15px; font-size: 12px; color: #64748b;">
                Terima kasih atas kunjungan Anda di RS Sehat Selalu
            </p>
        </div>
    </div>

    <button onclick="window.print()" class="print-button no-print">
        🖨️ Cetak Tiket
    </button>

    <script>
        // Auto print when page loads (optional)
        window.onload = function() {
            // Uncomment line below to auto-print
            // window.print();
        };
    </script>
</body>
</html>

