@extends('layouts.app')

@section('title', 'Daftar Lowongan')
@section('breadcrumb', 'Lowongan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Lowongan Kerja</h3>
    </div>
    <div class="card-body">
        <table id="jobs-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Posisi</th>
                    <th>Perusahaan</th>
                    <th>Lokasi</th>
                    <th>Gaji</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<style>
    .btn-primary {
        background-color: #007bff !important;
        color: white !important;
        padding: 4px 12px !important;
        border-radius: 4px !important;
        text-decoration: none !important;
        display: inline-block !important;
        font-weight: 600 !important;
    }
    .btn-primary:hover {
        background-color: #0069d9 !important;
        color: white !important;
    }
    table.dataTable td {
        vertical-align: middle !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(function() {
    $('#jobs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("jobs.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'company', name: 'company' },
            { data: 'location', name: 'location' },
            { data: 'salary', name: 'salary' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            processing: "Memuat...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>
@endpush