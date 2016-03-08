@if(Session::has('successMessage'))
<div class="alert alert-success">
    <strong>{{ Session::get('successMessage')}}</strong>
</div>
@endif