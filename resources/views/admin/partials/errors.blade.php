@if (isset($errors) and count($errors) > 0)
<div class="alert alert-danger" role="alert">
  Whoops! There were some problems with your input:<br><br>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif