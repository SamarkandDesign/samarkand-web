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

                @include('admin.partials.alert')
                @yield("admin.content")

            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->


    </div>
    <!-- /#admin -->

    @include('admin.layouts.footer')

    {!! HTML::script(mix('js/admin.js')) !!}

    @yield('admin.scripts')
</body>

</html>