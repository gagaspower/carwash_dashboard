@extends('welcome')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h5 class="card-title fw-semibold mb-4">Edit Hak Akses</h5>
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" method="post" action="#" enctype="multipart/form-data" id="form">
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Role</label>
                        <input type="text" class="form-control" id="role" name="role" value="{{$currentRole->name}}"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Akses Modul</label>
                        <br />
                        <div class="accordion" id="accordionExample">
                            @foreach ($permissionParents as $parent )
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $parent->id }}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $parent->id }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $parent->id }}">
                                        {{ $parent->parent_name }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $parent->id }}"
                                    class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $parent->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="list-group">
                                            @if (count($parent->permissions) > 0)
                                            @foreach ($parent->permissions as $permission )
                                            <label class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox"
                                                    name="permission[{{$permission->id}}]" value="{{ $permission->id }}"
                                                    {{ in_array($permission->id, $selectedPermission) ? 'checked' :
                                                ''}}>
                                                {{ $permission->name }}
                                            </label>
                                            @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="role_update">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $('#role_update').click(function(){
        const currentId = "{{ $currentRole->id }}";
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');

        const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (!isChecked) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Setidaknya pilih satu akses modul!",
            });
            return "";
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{url('/role/update')}}/" + currentId,
            data: new FormData($("#form")[0]),
            dataType:'json',
            async:false,
            type:'post',
            processData: false,
            contentType: false,
            success: function (data) {
                Swal.fire({
                    title: "Sukses!",
                    text: "Hak akses berhasil disimpan.",
                    icon: "success"
                });
            },
            error: function(data){

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        })

    });
</script>
@endsection