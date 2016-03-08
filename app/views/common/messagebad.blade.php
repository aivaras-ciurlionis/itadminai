@if($errors->any())
<div class="alert alert-danger">
        <strong>{{$errors->first()}}</strong>
   </div>
@endif