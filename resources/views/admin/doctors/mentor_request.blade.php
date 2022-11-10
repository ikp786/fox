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
                        <h1>Mantors Request</h1>
                    </div>
                    <!-- <div class="ml-auto d-flex align-items-center">
                        <nav>
                            <ol class="breadcrumb p-0 m-b-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html"><i class="ti ti-home"></i></a>
                                </li>
                                <li class="breadcrumb-item">
                                    Tables
                                </li>
                                <li class="breadcrumb-item active text-primary" aria-current="page">Data Table</li>
                            </ol>
                        </nav>
                    </div> -->
                </div>
                <!-- end page title -->
            </div>
        </div>
        <!-- end row -->
        <!-- begin row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="datatable-wrapper table-responsive">
                            <table id="datatable2" class="display compact table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>SR. NO.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>Bio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $cnt = 1; @endphp
                                    @forelse($doctors as $val)
                                    <tr>
                                        <td>{{$cnt}}</td>
                                        <td>{{$val->first_name.' '.$val->last_name}}</td>
                                        <td>{{$val->email}}</td>
                                        <td>{{$val->mobile}}</td>
                                        <td>{{$val->gender}}</td>
                                        <td>{{$val->dob}}</td>
                                        <td>{{$val->bio}}</td>
                                    </tr>
                                    @php $cnt++; @endphp
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container-fluid -->
</div>
<!-- end app-main -->
@endsection
@section('script')
@endsection