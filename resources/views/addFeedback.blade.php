@extends('layouts.app')
@section('css_section')
<style>
  .error {
    color: red;
    font-size: 14px;
}
    @media screen and (max-width:540px){
      .centerOnMobile {
        text-align:center
      }
    }
    
</style>
@endsection
@section('content')
    @include('layouts.header')
    <div class="container mt-5">
        
        <div class="container alert alert-success d-none success_message mt-4"></div>
        <div class="container alert alert-danger d-none danger_message mt-4"></div>
    
        <h4 class="modal-title text-center">Add New FeedBack</h4>
        <form id="add_feedback_form">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label  class="form-label" for="title">Feedback Title:</label>
                    <input type="text" class="form-control" id="title" value="" placeholder="Enter feedback title" name="title">
                    <span class="text-danger validation_error"></span>
                </div>

                <div class="col-md-12 mt-2">
                    <label for="category" class="form-label">Assigned to:</label>
                    <select class="form-select" id="category" name="category">
                    <option value="" selected disabled>Please select</option>
                    <option value="new">Want you add new Category cllck here</option>
                        @foreach($categories as $category)    
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                    <div class="col-md-12 mt-2 add_new_category" style="display: none;">
                    <label  class="form-label" for="title">Add your Category:</label>
                    <input type="text" id="new_category" name="new_category" class="form-control">
                    </div>
                    
                </div>
                <div class="col-md-12 mt-2">
                    <label for="description" class="form-label ">Description:</label>
                    <textarea id="description" class="w-100" name="description" rows="4" cols="50" style="border-color: #ced4da;">
                    </textarea><br>
                    <span class="text-danger validation_error"></span>
                </div>
            </div>
            <input type="submit" id="saveBtn" class="btn btn-primary float-end " value="Save changes">
        </form>
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
        var myTxtArea = document.getElementById("description");
        myTxtArea.value = myTxtArea.value.replace(/^\s*|\s*$/g,"");
        
        $('#category').on('change', function () {
            if ($(this).val() == 'new') {
                $('.add_new_category').show();
            } else {
                $('.add_new_category').hide();
            }
        });

        $("#add_feedback_form").validate({
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
                category: {
                    required: true,
                    onkeyup: false
                },
                description: {
                    required: true,
                    onkeyup: false
                },
                new_category: {
                    required: true,
                    onkeyup: false
                },

            },		
            submitHandler: function (form) {
            var formData = $("#add_feedback_form").serialize();
            console.log("serailized form is :",formData);
            $('#saveBtn').val('Loading...');
            var url = '{{ route("post-add-feedback") }}';
                $.ajax({
                    url: url,
                    type:"POST",
                    dataType: 'json',
                    data:{
                        data: formData
                    },
                    success: function (response) {
                        $('.success_message').html(response.message);
                        $('.add_new_category').hide();
                        $('.success_message').removeClass('d-none');
                        $('#saveBtn').val('Save Changes');
                        $('#add_feedback_form').trigger("reset");
                        $('#add_feedback_modal').modal('hide');
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
    });
</script>

@endsection