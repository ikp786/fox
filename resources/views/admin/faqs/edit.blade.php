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
                        <h1>Faqs </h1>
                    </div>
                    @include('admin.inc.validation_message')
                    @include('admin.inc.auth_message')
                    <div class="ml-auto d-flex align-items-center">
                        <nav>
                            <ol class="breadcrumb p-0 m-b-0">
                                <li class="breadcrumb-item">
                                    <a href="{{route('admin.faqs.index')}}">List</a>
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
                    {!! Form::model($faqs, ['method' => 'PATCH','route' => ['admin.faqs.update', $faqs->id],'files'=>true]) !!}
                        <div class="form-group">
                            <label class="control-label" for="fname">Question</label>
                            <div class="mb-2">
                            {!! Form::text('question', $faqs->question, array('placeholder' => 'Question','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="lname">Answer</label>
                            <div class="mb-2">
                            {!! Form::text('answer', $faqs->answer, array('placeholder' => 'Answer','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::button('Save', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}
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