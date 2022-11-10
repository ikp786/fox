@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')
<!-- end app-navbar -->
<!-- begin app-main -->
<div class="app-main" id="main">
    <!-- begin container-fluid -->
    <div class="container-fluid">
        <!-- begin row -->
        <div class="row">
            <div class="col-md-12 m-b-30">
                <!-- begin page title -->
                <div class="d-block d-sm-flex flex-nowrap align-items-center">
                    <div class="page-title mb-2 mb-sm-0">
                        <h1>Sliders </h1>
                    </div>
                    @include('admin.inc.validation_message')
                    @include('admin.inc.auth_message')
                    <div class="ml-auto d-flex align-items-center">
                        <nav>
                            <ol class="breadcrumb p-0 m-b-0">
                                <li class="breadcrumb-item">
                                    <a href="{{route('admin.sliders.index')}}">List</a>
                                </li>

                                <!-- <li class="breadcrumb-item">
                                    List
                                </li> -->
                                <!-- <li class="breadcrumb-item active text-primary" aria-current="page">Validation</li> -->
                            </ol>
                        </nav>
                    </div>
                </div>
                <!-- end page title -->
            </div>
        </div>
        <!-- end row -->
        <!-- start Validation row -->
        <div class="row formavlidation-wrapper">
            <div class="col-xl-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <div class="card-heading">
                            <h4 class="card-title">Add</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! Form::open(array('route' => 'admin.sliders.store','method'=>'POST','files'=>true)) !!}
                        <div class="form-group">
                            <div class="col-md-6">
                            <label class="control-label" for="fname">Title</label>
                            <div class="mb-2">
                            {!! Form::text('title', '', array('placeholder' => 'Title','class' => 'form-control')) !!}
                            </div>
                            </div>
                            <div class="col-md-6">
                            <label class="control-label" for="lname">Image</label>
                            <div class="mb-2">
                            {!! Form::file('image', array('placeholder' => 'Answer','class' => 'form-control')) !!}
                            </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="form-group">
                            {{ Form::button('Save', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg'] )  }}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
        <!-- end Validation row  -->
    </div>
    <!-- end container-fluid -->
</div>
<!-- end app-main -->
@endsection
@section('script')
@endsection