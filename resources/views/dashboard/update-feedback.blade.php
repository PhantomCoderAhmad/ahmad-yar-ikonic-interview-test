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
        
        @if (\Session::has('success'))
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" style="float: right;" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {!! session()->get('success') !!}
            </div>
        @endif
        @if (\Session::has('error'))
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" style="float: right;" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {!! session()->get('error') !!}
            </div>
        @endif
    
        <h4 class="modal-title text-center">Edit FeedBack</h4>
            <form id="update_feedback_form" action="{{route('post-update-feedback')}}" method="post">
                @csrf
                <input type="hidden" value="{{$feedback->id}}" name="id">
                <div class="col-md-12 mt-2">
                    <label  class="form-label" for="title">Feedback Title:</label>
                    <input type="text" class="form-control" id="title" value="{{$feedback->title}}" placeholder="Enter feedback title" name="title">
                    <span class="text-danger validation_error"></span>
                </div>

                <div class="col-md-12 mt-2">
                    <label for="category" class="form-label">Assigned to:</label>
                    <select class="form-select" id="category" name="category">
                    <option value="" selected disabled>Please select</option>
                    <option value="new">Want you add new Category cllck here</option>
                        @foreach($categories as $category)    
                            <option value="{{$category->id}}" {{ ($category->id == $feedback->category_id) ? 'selected' : '' }}>{{$category->name}}</option>
                        @endforeach
                    </select>
                    <div class="col-md-12 mt-2 add_new_category" style="display: none;">
                    <label  class="form-label" for="title">Add your Category:</label>
                    <input type="text" id="add_new_category" name="new_category" class="form-control">
                    </div>
                    
                </div>
                <div class="col-md-12 mt-2">
                    <label for="description" class="form-label ">Description:</label>
                    <textarea id="description" class="w-100" name="description" rows="4" cols="50" style="border-color: #ced4da;">{{$feedback->description}}
                    </textarea><br>
                    <span class="text-danger validation_error"></span>
                </div>
                <div class="col-md-12 mt-2">
                    <input type="checkbox" id="is_commentable" name="is_commentable" value="1" @if ($feedback->is_commentable == 1) checked="checked" @endif >
                    <label for="is_commentable" class="form-label ">Allow Comment:</label>
                </div>

                    <input type="submit" id="submit" class="btn btn-primary float-end updateSaveBtn" value="Save changes">
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
        });
    });
</script>

@endsection