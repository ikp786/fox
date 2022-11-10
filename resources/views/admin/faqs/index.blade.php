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
                        <h1>Faqs</h1>
                    </div>
                    <div class="ml-auto d-flex align-items-center">
                        <nav>
                            <ol class="breadcrumb p-0 m-b-0">
                                <li class="breadcrumb-item">
                                    <a href="{{route('admin.faqs.create')}}">Add</a>
                                </li>
                                <!-- <li class="breadcrumb-item">
                                    Tables
                                </li> -->
                                <!-- <li class="breadcrumb-item active text-primary" aria-current="page">Add</li> -->
                            </ol>
                        </nav>
                    </div>
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
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $cnt = 1; @endphp
                                    @forelse($faqs as $val)
                                    <tr>
                                        <td>{{$cnt}}</td>
                                        <td>{{$val->question}}</td>
                                        <td>{{$val->answer}}</td>
                                        <td>{{$val->created_at}}</td>
                                        <td>
                                            <a class="btn-xs sharp me-1" href="{{ route('admin.faqs.edit',$val->id) }}">Edit</a>
                                            {!! Form::open(['method' => 'DELETE','route' => ['admin.faqs.destroy', $val->id],'style'=>'display:inline']) !!}<button onclick="return confirm('Are you sure to delete Faqs?')" class="delete btn-xs sharp" type="submit">Delete </button>
                                            {!! Form::close() !!}
                                        </td>
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