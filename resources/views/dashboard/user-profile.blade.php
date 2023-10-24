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
                    <div class="col-md-6">
                        <h4 class="">Admin Profile Details</h4>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#reset_password">
                            Reset Password
                        </button>
                    </div>
                </div>
                


                <div class="container g-4  bg-light rounded p-5">
                    <div class="alert alert-success d-none success_message"></div>
                    <div class="alert alert-danger d-none danger_message"></div>
                        <form id="user_profile_form">
                            <input type="hidden" value="{{$user_detail->id}}" name="id">
                            <div class="mb-3 mt-3 row">
                                <div class="col-md-6">
                                    <label  class="form-label" for="username">Username:</label>
                                    <input type="text" class="form-control" id="username" value="{{$user_detail->name}}" placeholder="Enter name" name="username">
                                    <span class="text-danger validation_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label  class="form-label" for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" value="{{$user_detail->email}}" placeholder="Enter email" name="email">
                                    <span class="text-danger validation_error"></span>

                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" id="submit" class="btn btn-primary float-end details_save_btn" value="Save changes">
                                </div>
                            </div>
                        </form>
                </div>
            </div>
            <!-- reset password -->
            <div class="modal fade" id="reset_password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div class="alert alert-success d-none password_reset_success"></div>
                    <div class="alert alert-danger d-none password_reset_danger"></div>
                        <form id="reset_password_form">
                        <input type="hidden" value="{{$user_detail->id}}" name="id">

                                <label  class="form-label" for="password">Password:</label>
                                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                                <span class="text-danger validation_error"></span>
                                </br>
                                <label  class="form-label" for="password_confirmation">Confirm Password:</label>
                                <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm password" name="password_confirmation">
                                <span class="text-danger validation_error"></span>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary reset_password_btn" value="Save changes">
                    </div>
                    </form>
                    </div>
                </div>
            </div>
            <!-- reset password -->

        </div>
        <!-- Content End -->
</div>

@endsection
@section('js_section')
<script type='text/javascript'>
    $(document).ready(function(){
        $('.profile-nav-link').addClass("active");
    });
    $("#user_profile_form").validate({
    onkeyup: function(element) {
        var element_id = $(element).attr('id');
        if (this.settings.rules[element_id].onkeyup !== false) {
            $.validator.defaults.onkeyup.apply(this, arguments);
        }
    },
    rules: {
        username: {
            required: true,
            onkeyup: false
        },
        email: {
            required: true,
            onkeyup: false
        },
   
    },		
	submitHandler: function (form) {
       var formData = $("#user_profile_form").serialize();
       let username = $("#username").val();
       $('.details_save_btn').val('Loading...');

        $.ajax({
            url: "{{route('update-admin-profile')}}",
            type:"POST",
            dataType: 'json',
            data:{
                _token: "{{ csrf_token() }}" ,
                data: formData
            },
             success: function (response) {
                $('.success_message').html(response.message);
                $('.success_message').removeClass('d-none');
                $('.admin_name').html(username);
                $('.details_save_btn').val('Save Changes');
                setTimeout(function() { 
                    $('.success_message').addClass('d-none');
                }, 4000);
            },
            error: function (response) {
                $('.danger_message').html("Sorry! Something is Wrong.");
                $('.danger_message').removeClass('d-none');
                $('.details_save_btn').val('Save Changes');
                setTimeout(function() { 
                    $('.danger_message').addClass('d-none');
                }, 4000);
            }


        });
	}
});
$("#reset_password_form").validate({
    onkeyup: function(element) {
        var element_id = $(element).attr('id');
        if (this.settings.rules[element_id].onkeyup !== false) {
            $.validator.defaults.onkeyup.apply(this, arguments);
        }
    },
    rules: {
        password: {
          required:true,
          minlength: 8
    },
      password_confirmation: {
          required:true,
          minlength: 8,
          equalTo: "#password"
    },
    },
    messages: {
        password_confirmation:{
            minlength:`Confirmation Password is required. (minimum Length: 8)`,
            equalTo:` Confirmation password didnt match !  `,
        } ,
    },
			
	submitHandler: function (form) {
       var formData = $("#reset_password_form").serialize();
       $('.reset_password_btn').val('Loading...');
        $.ajax({
            url: "{{route('update-admin-profile')}}",
            type:"POST",
            dataType: 'json',
            data:{
                _token: "{{ csrf_token() }}" ,
                data: formData
            },
             success: function (response) {
                $('.password_reset_success').html(response.message);
                $('.password_reset_success').removeClass('d-none');
                $("#reset_password_form").trigger("reset");
                $('.reset_password_btn').val('Save Changes');
                setTimeout(function() { 
                    $('.password_reset_success').addClass('d-none');
                }, 4000);
            },
            error: function (response) {
                $('.password_reset_danger').html("Sorry! Something is Wrong.");
                $('.password_reset_danger').removeClass('d-none');
                $('.reset_password_btn').val('Save Changes');
                setTimeout(function() { 
                    $('.password_reset_danger').addClass('d-none');
                }, 4000);
            }


        });
	}
});

</script>

@endsection