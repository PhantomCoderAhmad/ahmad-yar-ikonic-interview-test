<form id="user_profile_form">
    <input type="hidden" value="{{$user->id}}" name="id">
    <div class="mb-3 mt-3 row">
        <div class="col-md-12">
            <label  class="form-label" for="username">Username:</label>
            <input type="text" class="form-control" id="username" value="{{$user->name}}" placeholder="Enter name" name="username">
            <span class="text-danger validation_error"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label  class="form-label" for="email">Email:</label>
            <input type="email" class="form-control" id="email" value="{{$user->email}}" placeholder="Enter email" name="email">
            <span class="text-danger validation_error"></span>

        </div>
    </div>

    <div class="modal-footer" style="padding-right: 0px !important;">
        <input type="submit" id="submit" class="btn btn-primary float-end updateSaveBtn" value="Save changes">
    </div>
</form>