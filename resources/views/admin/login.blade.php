@section('title')
Login
@endsection

<!DOCTYPE html>
<html lang="en">

@include('admin.layouts._head')

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <img class="" alt="" src="{{ config('shop.logo') }}" style="width: 60%; height: auto; max-width:220px;">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      @include('partials.alert')
      @include('admin.partials.errors')

      <form action="/login" method="post">
        {{ csrf_field() }}
        <div class="form-group has-feedback">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <span class="fa fa-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <span class="fa fa-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox">
              <label>
                <input type="checkbox"> Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="/password/reset">Forgot my password</a><br>


    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

</body>

</html>