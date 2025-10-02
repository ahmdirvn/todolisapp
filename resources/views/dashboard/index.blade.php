@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Todo List Analytics')

@section('content')
<div class="row">
  <!-- Total Todo Dibuat -->
  <div class="col-lg-3 col-md-6 col-12 mb-6">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <p class="mb-1">Total Dibuat</p>
          <h4 class="mb-0">120</h4>
        </div>
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-primary">
            <i class="bx bx-list-check bx-lg text-primary"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Todo Selesai -->
  <div class="col-lg-3 col-md-6 col-12 mb-6">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <p class="mb-1">Selesai</p>
          <h4 class="mb-0">85</h4>
        </div>
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-success">
            <i class="bx bx-check-circle bx-lg text-success"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Todo Belum Selesai -->
  <div class="col-lg-3 col-md-6 col-12 mb-6">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <p class="mb-1">Belum Selesai</p>
          <h4 class="mb-0">25</h4>
        </div>
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-warning">
            <i class="bx bx-time-five bx-lg text-warning"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Todo Terlewat Deadline -->
  <div class="col-lg-3 col-md-6 col-12 mb-6">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <p class="mb-1">Terlewat Deadline</p>
          <h4 class="mb-0">10</h4>
        </div>
        <div class="avatar">
          <span class="avatar-initial rounded bg-label-danger">
            <i class="bx bx-error-circle bx-lg text-danger"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
