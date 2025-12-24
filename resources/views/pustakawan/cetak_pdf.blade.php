<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; } /* Font diperkecil sedikit agar muat */
        h2, h3 { text-align: center; margin: 0; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; vertical-align: middle; }
        th { background-color: #e0e0e0; font-weight: bold; text-align: center; }
        .tgl-cetak { float: right; font-style: italic; margin-top: 5px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-red { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PERPUSTAKAAN SMP MUHAMMADIYAH 2 KARTASURA</h2>
        <h3>Laporan Peminjaman & Denda</h3>
        <p style="text-align: center;">Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</p>
    </div>

    <div class="tgl-cetak">Dicetak pada: {{ date('d-m-Y H:i') }}</div>
    <div style="clear: both;"></div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 10%">NIS</th>
                <th style="width: 18%">Nama Siswa</th>
                <th style="width: 8%">Kelas</th>
                <th style="width: 22%">Judul Buku</th>
                <th style="width: 10%">Tgl Pinjam</th>
                <th style="width: 10%">Tgl Kembali</th>
                <th style="width: 8%">Status</th>
                <th style="width: 10%">Denda</th> {{-- KOLOM BARU --}}
            </tr>
        </thead>
        <tbody>
            @php $totalDenda = 0; @endphp
            @foreach($laporan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->siswa->nis }}</td>
                <td>{{ $item->siswa->user->name }}</td>
                <td class="text-center">{{ $item->siswa->kelas }}</td>
                <td>{{ $item->buku->judul }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/y') }}</td>
                <td class="text-center">
                    {{ $item->tgl_pengembalian_aktual ? \Carbon\Carbon::parse($item->tgl_pengembalian_aktual)->format('d/m/y') : '-' }}
                </td>
                <td class="text-center">{{ ucfirst($item->status) }}</td>
                
                {{-- KOLOM DENDA --}}
                <td class="text-right">
                    @if($item->denda)
                        @php $totalDenda += $item->denda->jumlah_denda; @endphp
                        <span class="text-red">Rp {{ number_format($item->denda->jumlah_denda, 0, ',', '.') }}</span>
                        <br>
                        <small style="font-size: 8px; color: #555;">
                            ({{ $item->denda->status_pembayaran == 'lunas' ? 'Lunas' : 'Belum' }})
                        </small>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
            
            {{-- BARIS TOTAL --}}
            <tr>
                <td colspan="8" class="text-right" style="font-weight: bold; background-color: #f9f9f9;">Total Pemasukan Denda Bulan Ini:</td>
                <td class="text-right" style="font-weight: bold; background-color: #f9f9f9;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <div style="float: right; text-align: center; width: 200px;">
        <p>Kartasura, {{ date('d F Y') }}</p>
        <p>Pustakawan,</p>
        <br><br><br>
        <p><b>{{ Auth::user()->name }}</b></p>
    </div>

</body>
</html>