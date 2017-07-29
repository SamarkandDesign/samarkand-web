@extends('admin.layouts.admin')

@section('title')
    Dashboard
@stop

@section('heading')
    Dashboard
@stop

@section('admin.content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $orderCountByStatus->get(App\Order::PAID) ?: '0' }}</h3>

                    <p>New Orders</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-bag"></i>
                </div>
                <a href="/admin/orders/processing" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $lowStockedProducts }}</h3>

                    <p>{{ str_plural('Product', $lowStockedProducts) }} low in stock</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cart-arrow-down"></i>
                </div>
                <a href="/admin/products" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $outOfStockProducts }}</h3>

                    <p>{{ str_plural('Product', $outOfStockProducts) }} out of stock</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cart-arrow-down"></i>
                </div>
                <a href="/admin/products" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>


        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $upcomingEvents }}</h3>

                    <p>Upcoming {{ str_plural('event', $upcomingEvents) }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar"></i>
                </div>
                <a href="/admin/events" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Quick Links</h3>
            </div>
            <div class="box-body no-padding" style="display: block;">
              <ul class="nav nav-pills nav-stacked">
                 <li ><a href="/admin/orders"><i class="fa fa-shopping-cart"></i> All Orders</a></li>
                <li ><a href="/admin/products"><i class="fa fa-shopping-bag"></i> All Products</a></li>
                <li ><a href="/admin/products/create"><i class="fa fa-shopping-bag"></i> New Product</a></li>
                <li><a href="/admin/pages/create"><i class="fa fa-file-text"></i> New Page</a></li>
                <li><a href="/admin/menus"><i class="fa fa-bars"></i> Menus</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>

          <!-- /.box -->
        </div>

        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header">Sales</div>
                <div class="box-body">
                    <graph></graph>
                </div>
            </div>
        </div>
    </div>
@stop
