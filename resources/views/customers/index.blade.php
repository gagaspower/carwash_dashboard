@extends('welcome')
@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <div class="d-flex mb-4 justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Pelanggan</h5>

                    <a href="{{url('/customer/add')}}" class="btn btn-info m-1">Tambah Data</a>
                </div>

                <table id="customerTable" data-toggle="table" data-url="/customer/fetch-customer" data-pagination="true"
                    data-side-pagination="server" data-search="true" data-sort-name="id" data-sort-order="asc"
                    class="table">
                    <thead>
                        <tr>
                            <th data-field="customer_name" data-sortable="true">Nama Pelanggan</th>
                             <th data-field="customer_phone" data-sortable="false">No. Tlp/HP</th>
                            <th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Aksi
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function operateFormatter(value, row, index) {
        return [
        '<button type="button" class="btn btn-secondary btn-sm m-1 btn-edit">',
            '<i class="ti ti-pencil-minus"></i>',
            '</button> ',
        '<button type="button" class="btn btn-danger btn-sm m-1 btn-delete">',
            '<i class="ti ti-trash"></i>',
            '</button>'
        ].join('')
    }


    window.operateEvents = {
        'click .btn-edit': function (e, value, row, index) {
            toToEdit(row);
        },

        'click .btn-delete': function (e, value, row, index) {
            Swal.fire({
            title: "Anda yakin?",
            text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Hapus!"
            }).then((result) => {
            if (result.isConfirmed) {
                deleteData(row);
            }
            });
        }
    }


    function toToEdit(row){
          window.location.href = "{{ url('/customer/edit/') }}" + "/" + row.id;
    }


    function deleteData(row){
        $.ajax({
            type: "DELETE",
            url: "{{url('/customer/delete')}}/" + row.id,
            data: {
                _token: "{{ csrf_token() }}",
            },
            beforeSend : function() {
                Swal.fire({
                    title:'Menghapus data',
                    text: 'Mohon Tunggu...',
                    allowOutsideClick: false,
                    didOpen: function() {
                        Swal.showLoading()
                    }
                })
            },
            success: function (data) {
                Swal.fire({
                    title: "Sukses!",
                    text: "Data pelanggan berhasil dihapus.",
                    icon: "success"
                });
                 $('#customerTable').bootstrapTable('refresh');
            },
            error: function(data){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        });
    }

</script>

@endsection