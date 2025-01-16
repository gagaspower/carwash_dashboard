@extends('welcome')
@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <div class="d-flex mb-4 justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Hak Akses</h5>

               </div>

                <table id="vehicleTable" data-toggle="table" data-url="/role/get-data" data-pagination="true"
                    data-side-pagination="server" data-sort-name="id" data-sort-order="asc"
                    class="table">
                    <thead>
                        <tr>
                            <th data-field="name" data-sortable="true">Nama</th>
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
        '<button type="button" class="btn btn-secondary btn-md m-1 btn-edit">',
            '<i class="ti ti-settings-check"></i> Atur',
            '</button> '
        ].join('')
    }


    window.operateEvents = {
        'click .btn-edit': function (e, value, row, index) {
            toToEdit(row);
        }
    }


    function toToEdit(row){
          window.location.href = "{{ url('/role/edit/') }}" + "/" + row.id;
    }




</script>

@endsection