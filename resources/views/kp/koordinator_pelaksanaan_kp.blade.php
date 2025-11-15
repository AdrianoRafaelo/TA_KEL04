@extends('layouts.app')

@section('title', 'Pelaksanaan Kerja Praktik')

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Breadcrumb & Title -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Page</a></li>
                    <li class="breadcrumb-item"><a href="#">Kerja Praktik</a></li>
                    <li class="breadcrumb-item active">Pelaksanaan</li>
                </ol>
            </nav>
            <h4 class="mb-0 fw-bold">Pelaksanaan Kerja Praktik</h4>
        </div>
    </div>

    <!-- TABEL -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold">Pelaksanaan Kerja Praktik</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3" style="width: 50px;">No.</th>
                                    <th class="px-4 py-3">Mahasiswa</th>
                                    <th class="px-4 py-3">Perusahaan KP</th>
                                    <th class="px-4 py-3 text-center">Minggu</th>
                                    <th class="px-4 py-3 text-center">Log Activity</th>
                                    <th class="px-4 py-3 text-center">Bimbingan</th>
                                    <th class="px-4 py-3">Dosen Pembimbing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelaksanaan_kp as $index => $kp)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3 text-muted small">{{ $index + 1 }}.</td>
                                    <td class="px-4 py-3">
                                        <div class="fw-medium">{{ $kp->mahasiswa }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-muted small">
                                        {{ $kp->perusahaan }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small">
                                            W{{ $kp->minggu }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#logModal{{ $kp->id }}">
                                            <i class="fas fa-clipboard-list me-1"></i> Log Activity
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1">
                                            {{ $kp->bimbingan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 small text-muted">
                                        {{ $kp->dosen }}
                                    </td>
                                </tr>

                                <!-- MODAL LOG ACTIVITY -->
                                <div class="modal fade" id="logModal{{ $kp->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Log Activity - {{ $kp->mahasiswa }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Optional: Tambahkan sorting sederhana (client-side)
    document.querySelectorAll('th').forEach(th => {
        if (th.querySelector('.fa-sort')) {
            th.style.cursor = 'pointer';
            th.addEventListener('click', () => {
                const table = th.closest('table');
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const index = Array.from(th.parentElement.children).indexOf(th);
                const isAsc = th.classList.toggle('asc');

                rows.sort((a, b) => {
                    const aText = a.children[index].innerText;
                    const bText = b.children[index].innerText;
                    return isAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                });

                rows.forEach(row => table.querySelector('tbody').appendChild(row));
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        color: #5a5c69;
    }

    .table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-sm {
        font-size: 0.765rem;
        padding: 0.25rem 0.75rem;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .bg-primary {
        background-color: #2E2A78 !important;
    }

    /* Hover Row */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Sorting Icon */
    .fa-sort {
        font-size: 0.7rem;
        opacity: 0.6;
    }

    th.asc .fa-sort:before {
        content: "\f0de";
        opacity: 1;
    }

    th:not(.asc) .fa-sort:before {
        content: "\f0dd";
        opacity: 1;
    }

    @media (max-width: 992px) {
        .table th, .table td {
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }
    }
</style>
@endsection