@extends('layouts.app')
@section('css_section')
<style>
.error {
    color: red;
    font-size: 14px;
}
.form-label{
    font-weight: 800;
}
.dataTables_wrapper .dataTables_paginate .paginate_button{
    padding: 0em !important;
}
.page-link {
    padding: 0.3em 1em 0.3em 1em;
    border-radius: 8px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover{
    border: none;
    background: none;
}
</style>
@endsection

@section('content')


<div class="container-xxl position-relative bg-white d-flex p-0">


        <!-- Content Start -->
        <div class="content">
            @include('layouts.admin.navigation')
            @include('layouts.admin.dashboardsidebar')
            <div class="container-fluid pt-5 px-5">
                <div class="row p-0 mb-4">
                    <div class="col-md-12">
                        <h4 class="">Feedback's</h4>
                    </div>
                </div>
                <div class="container g-4  bg-light rounded p-5">
                    <div class="alert alert-success d-none success_message"></div>
                    <div class="alert alert-danger d-none danger_message"></div>
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Desription</th>
                                <th>Posted by</th>
                                <th>Category</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                
            </div>

            <!-- edit/update -->
            <div class="modal fade" id="update_feedback_modal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="updatemodelHeading"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body update_feedback_modal_body">
                            
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Content End -->
</div>
@endsection
@section('js_section')
<script type='text/javascript'>
    $(function () {
        
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });
        $('.categories-nav-link').addClass("active"); 
   
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "",
            columns: [
                {data: 'title', name: 'title'},
                {data: 'description', name: 'description'},
                {data: 'username', name: 'username'},
                {data: 'category_name', name: 'category_name'},
                
                {data: 'action', name: 'action', orderable: false, searchable: false, sWidth:'20%'},
            ]
        });
    
        $("#add_sub_category_form").validate({
            onkeyup: function(element) {
                var element_id = $(element).attr('id');
                if (this.settings.rules[element_id].onkeyup !== false) {
                    $.validator.defaults.onkeyup.apply(this, arguments);
                }
            },
            rules: {
                cat_name: {
                    required: true,
                    onkeyup: false
                },
                parent_id: {
                    required: true,
                    onkeyup: false
                },

            },		
            submitHandler: function (form) {
            var formData = $("#add_sub_category_form").serialize();
            $('#saveBtn').val('Loading...');

                $.ajax({
                    url: "",
                    type:"POST",
                    dataType: 'json',
                    data:{
                        data: formData
                    },
                    success: function (response) {
                        $('.success_message').html(response.message);
                        $('.success_message').removeClass('d-none');
                        $('#saveBtn').val('Save Changes');
                        $('#add_sub_category_form').trigger("reset");
                        table.rows().invalidate().draw();
                        $('#add_sub_category_model').modal('hide');
                        setTimeout(function() {
                            $('.success_message').addClass('d-none');
                        }, 2000);
                    },
                    error: function (response) {
                        $('.danger_message').html("Sorry! Something is Wrong.");
                        $('.danger_message').removeClass('d-none');
                        $('#saveBtn').val('Save Changes');
                        setTimeout(function() { 
                            $('.danger_message').addClass('d-none');
                        }, 2000);
                    }


                });
            }
        });
        // $('body').on('click', '.editFeedback', function () {
        //     $('#update_feedback_modal').modal('show');
        //     var feedback_id = $(this).data('id');
        //     $.ajax({
        //         type: "post",
        //         url: "{{ url('edit-feedback') }}"+'/'+feedback_id,
        //         success: function (data) {
        //             $('.update_feedback_modal_body').html(data);
        //         },
        //         error: function (data) {
        //             setTimeout(function() { 
        //                 $('.danger_message').addClass('d-none');
        //             }, 2000);
        //         }
        //     });
        // });
        $('body').on('click', '.editFeedback', function () {
            var feedback_id = $(this).data('id');
            location.href= "{{ url('edit-feedback') }}"+'/'+feedback_id;
        });
        $('body').on('click', '.updateSaveBtn', function () {
            $("#update_feedback_form").validate({
                onkeyup: function(element) {
                    var element_id = $(element).attr('id');
                    if (this.settings.rules[element_id].onkeyup !== false) {
                        $.validator.defaults.onkeyup.apply(this, arguments);
                    }
                },
                rules: {
                    title: {
                        required: true,
                        onkeyup: false
                    },
                    description: {
                        required: true,
                        onkeyup: false
                    },
                    category: {
                        required: true,
                        onkeyup: false
                    },
                    new_category: {
                        required: true,
                        onkeyup: false
                    },
                },		
                submitHandler: function (form) {
                var formData = $("#update_feedback_form").serialize();
                $('.updateSaveBtn').val('Loading...');

                    $.ajax({
                        url: "{{route('update-user')}}",
                        type:"POST",
                        dataType: 'json',
                        data:{
                            data: formData
                        },
                        success: function (response) {
                            $('.success_message').html(response.message);
                            $('.success_message').removeClass('d-none');
                            $('.updateSaveBtn').val('Save Changes');
                            $('#update_feedback_form').trigger("reset");
                            table.rows().invalidate().draw();
                            $('#update_feedback_modal').modal('hide');
                            setTimeout(function() {
                                $('.success_message').addClass('d-none');
                            }, 4000);
                        },
                        error: function (response) {
                            $('.danger_message').html("Sorry! Something is Wrong.");
                            $('.danger_message').removeClass('d-none');
                            $('.updateSaveBtn').val('Save Changes');
                            setTimeout(function() { 
                                $('.danger_message').addClass('d-none');
                            }, 2000);
                        }


                    });
                }
            });
        });
        


    $('body').on('click', '.deleteFeedback', function () {
        var feedback_id = $(this).data("id");
        swal.fire({
            title: "Delete?",
            icon: 'question',
            text: "Please ensure and then confirm!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (e) {

            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: "{{ url('delete-feedback') }}"+'/'+feedback_id,
                    success: function (response) {
                        $('.success_message').html(response.message);
                            $('.success_message').removeClass('d-none');
                            setTimeout(function() {
                                $('.success_message').addClass('d-none');
                            }, 4000);
                        table.draw();
                    },
                    error: function (data) {
                        $('.danger_message').html("Sorry! Something is Wrong.");
                        $('.danger_message').removeClass('d-none');   
                        setTimeout(function() { 
                            $('.danger_message').addClass('d-none');
                        }, 2000);
                    }
                });

            } else {
                e.dismiss;
            }

        }, function (dismiss) {
            return false;
        })

        

        // confirm("Are You sure want to delete !");
        
        
    });

});

</script>

@endsection