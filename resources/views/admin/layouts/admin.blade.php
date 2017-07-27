<!DOCTYPE html>
<html lang="en">

@include('admin.layouts._head')

    <body class="hold-transition skin-red sidebar-mini">

            <div id="admin" class="wrapper">
                @include('admin.layouts.header')

                <!-- Sidebar -->
                @include('admin.layouts.sidebar')

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                      <h1>@yield('heading')</h1>
                        @yield('breadcrumbs')
                </section>


                <!-- Main content -->
                <section class="content">

                    @include('partials.alert')
                    @yield("admin.content")

                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->


        </div>
        <!-- /#admin -->


    @include('admin.layouts.footer')

    {{-- Browserify --}}
    {!! HTML::script(elixir('js/admin.js')) !!}
    {{-- {!! HTML::script('js/admin-lte.js') !!} --}}

    @yield('admin.scripts')
</body>

</html>
