@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger">
        <strong>Pateiktuose duomenyse yra klaidų. Peržiūrėkite pranešimus ir bandykite dar kartą.</strong>

        <br><br>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(Session::has('errorMessage'))
<div class="alert alert-danger">
    <strong>{{ Session::get('errorMessage')}}</strong>
</div>
@endif