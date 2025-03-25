@extends('layouts.app')
@section('title', __( 'user.users' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'user.users' )
        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang( 'user.manage_users' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'user.all_users' )])
        @can('user.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full" href="{{action([\App\Http\Controllers\ManageUserController::class, 'create'])}}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>                        @lang( 'messages.add' )
                    </a>
                 </div>
            @endslot
        @endcan
        @can('user.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="users_table">
                    <thead>
                        <tr>
                            <th>@lang( 'business.username' )</th>
                            <th>@lang( 'user.name' )</th>
                            <th>@lang( 'user.role' )</th>
                            <th>@lang( 'business.email' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade user_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@stop
@section('javascript')
{{-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript">
    //Roles table
    $(document).ready( function(){
        function saveColumnVisibility(tableId, storageKey) {
            $('#' + tableId).on('column-visibility.dt', function (e, settings, column, state) {
                let colvisState = JSON.parse(localStorage.getItem(storageKey)) || {};
                colvisState[column] = state;
                localStorage.setItem(storageKey, JSON.stringify(colvisState));
            });
        }
        
        function loadColumnVisibility(tableId, storageKey) {
            let colvisState = JSON.parse(localStorage.getItem(storageKey));
            if (colvisState) {
                $.each(colvisState, function (index, state) {
                    $('#' + tableId).DataTable().column(index).visible(state);
                });
            }
        }

        function convertFontToBase64(url, callback) {
            fetch(url)
                .then(response => response.blob())
                .then(blob => {
                    let reader = new FileReader();
                    reader.onloadend = function () {
                        let base64data = reader.result;
                        console.log(base64data); // Cek hasil Base64 di Console
                        callback(base64data);
                    };
                    reader.readAsDataURL(blob);
                })
                .catch(error => console.error("Gagal memuat font: " + error));
        }


        convertFontToBase64("{{ asset('images/text.png') }}", function(base64) {
            console.log(base64); 
        });

        var export_button = {{ auth()->user()->can('view_export_buttons') ? 'true' : 'false' }};
        
        var users_table = $('#users_table').DataTable({
            buttons: export_button ? pdfButtons('Users Report') : [],
            processing: true,
            serverSide: true,
            fixedHeader:false,
            ajax: '/users',
            columnDefs: [ {
                "targets": [4],
                "orderable": false,
                "searchable": false
            } ],
            "columns":[
                {"data":"username"},
                {"data":"full_name"},
                {"data":"role"},
                {"data":"email"},
                {"data":"action"}
            ]
        });

        saveColumnVisibility('users_table', 'colvisState_users');
        loadColumnVisibility('users_table', 'colvisState_users');
        
                $(document).on('click', 'button.delete_user_button', function(){
            swal({
              title: LANG.sure,
              text: LANG.confirm_delete_user,
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                toastr.success(result.msg);
                                users_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
             });
        });
        
    });
    
    
</script>
@endsection
