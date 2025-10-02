@extends('layouts/contentNavbarLayout')

@section('title', 'Task List')

@section('content')
<div class="container mt-4 text-center">
  <h2 class="mb-4">Task List</h2>

  {{-- Form Tambah Task --}}
  <form id="addTaskForm" class="mb-4 d-flex justify-content-center align-items-center flex-wrap">
    @csrf
    <div class="row g-2 mb-2 justify-content-center text-center">
      <div class="col-md-3">
        <input type="text" name="title" class="form-control text-center" placeholder="Judul Task..." required>
      </div>
      <div class="col-md-2">
        <select name="priority" class="form-control text-center">
          <option value="low">Low Priority</option>
          <option value="medium" selected>Medium Priority</option>
          <option value="high">High Priority</option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="date" name="due_date" class="form-control text-center" placeholder="Deadline">
      </div>
      <div class="col-md-4">
        <input type="text" name="description" class="form-control text-center" placeholder="Deskripsi tambahan (opsional)">
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-primary w-100" id="addBtn">
          <span id="btnText">Tambah</span>
          <span id="btnLoader" class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
      </div>
    </div>
  </form>

  {{-- Tabel Task --}}
  <div class="card px-4 py-3">
    <h5 class="card-header px-3 text-center">Daftar Task</h5>
    <div class="table-responsive text-nowrap px-3">
      <table id="taskTable" class="table table-hover text-center">
        <thead>
          <tr>
            <th class="text-center">No</th>
            <th class="text-center">Status</th>
            <th class="text-center">Judul</th>
            <th class="text-center">Prioritas</th>
            <th class="text-center">Deadline</th>
            <th class="text-center">Deskripsi</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('page-script2')
<script>
$(document).ready(function () {
    // DataTable
    let table = $('#taskTable').DataTable({
        ajax: '/tasks',
        processing: true,
        serverSide: false,
        order: [[1, 'asc']], // pending muncul atas
        columns: [
            { data: null, render: (data, type, row, meta) => meta.row + 1 },
            {
                data: 'status',
                className: 'text-center',
                render: function(data, type, row){
                    let checked = data === 'done' ? 'checked' : '';
                    return `<input type="checkbox" class="statusChk" data-id="${row.id}" ${checked}>`;
                }
            },
            { data: 'title', className: 'text-center', render: function(data){ return `<strong>${data}</strong>`; } },
            { data: 'priority', className: 'text-center' },
            { data: 'due_date', className: 'text-center' },
            { data: 'description', className: 'text-center' },
            {
                data: 'id',
                className: 'text-center',
                render: function(data, type, row){
                    return `
                    <div class="dropdown text-center">
                      <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item editBtn" href="#" data-id="${data}" data-title="${row.title}" data-desc="${row.description}" data-priority="${row.priority}" data-due="${row.due_date}">
                          <i class="bx bx-edit-alt"></i> Edit
                        </a>
                        <a class="dropdown-item deleteBtn" href="#" data-id="${data}">
                          <i class="bx bx-trash"></i> Hapus
                        </a>
                      </div>
                    </div>
                    `;
                }
            }
        ],
        rowCallback: function(row, data){
            if(data.status === 'pending') $(row).addClass('table-warning');
            else if(data.status === 'in_progress') $(row).addClass('table-info');
            else if(data.status === 'done') $(row).addClass('table-success');
        }
    });

    // Tambah Task
    $('#addTaskForm').on('submit', function(e){
        e.preventDefault();
        $('#btnText').addClass('d-none');
        $('#btnLoader').removeClass('d-none');

        $.post('/tasks', $(this).serialize(), function(){
            Swal.fire('Berhasil!', 'Task berhasil ditambahkan', 'success');
            $('#addTaskForm')[0].reset();
            table.ajax.reload();
        }).always(function(){
            $('#btnText').removeClass('d-none');
            $('#btnLoader').addClass('d-none');
        });
    });

    // Update status
    $(document).on('change', '.statusChk', function(){
        let rowData = table.row($(this).closest('tr')).data(); // ambil semua data row
        let id = rowData.id;
        rowData.status = $(this).is(':checked') ? 'done' : 'pending';

        $.ajax({
            url: '/tasks/' + id,
            type: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                title: rowData.title,
                priority: rowData.priority,
                due_date: rowData.due_date,
                description: rowData.description,
                status: rowData.status
            },
            success: function(){ table.ajax.reload(null,false); }
        });
    });

    // Delete
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');
        Swal.fire({
            title: 'Yakin hapus?',
            icon: 'warning',
            showCancelButton:true,
            confirmButtonText:'Ya, hapus!'
        }).then(result=>{
            if(result.isConfirmed){
                $.ajax({
                    url:'/tasks/'+id,
                    type:'DELETE',
                    data:{_token:$('meta[name="csrf-token"]').attr('content')},
                    success:()=>table.ajax.reload()
                });
            }
        });
    });

    // Edit Task
    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');
        Swal.fire({
            title: 'Edit Task',
            html: `
                <input id="editTitle" class="swal2-input text-center" placeholder="Judul Task" value="${$(this).data('title')}">
                <input id="editPriority" class="swal2-input text-center" placeholder="Prioritas" value="${$(this).data('priority')}">
                <input id="editDue" type="date" class="swal2-input text-center" placeholder="Deadline" value="${$(this).data('due')}">
                <textarea id="editDesc" class="swal2-textarea text-center" placeholder="Deskripsi">${$(this).data('desc')}</textarea>
            `,
            showCancelButton:true,
            confirmButtonText:'Update'
        }).then(result=>{
            if(result.isConfirmed){
                $.ajax({
                    url:'/tasks/'+id,
                    type:'PUT',
                    data:{
                        _token:$('meta[name="csrf-token"]').attr('content'),
                        title:$('#editTitle').val(),
                        priority:$('#editPriority').val(),
                        due_date:$('#editDue').val(),
                        description:$('#editDesc').val()
                    },
                    success:()=>table.ajax.reload()
                });
            }
        });
    });

});
</script>
@endsection
