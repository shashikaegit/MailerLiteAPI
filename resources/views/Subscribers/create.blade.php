@extends('layouts.app')

@section('title', 'Add Subscriber')

@section('content')
    <div class="breadcrumb-box">
        <div class="row align-items-center">
            <div class="col-md-8 col-lg-8">
                <h4 class="page-title">Add Subscriber</h4>
                <div class="breadcrumb-list">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/subscriber') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/subscriber') }}">Subscribers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Subscriber</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="widgetbar text-right">
                    <a href="{{url('/subscriber')}}" class="btn btn-info">Subscriber List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="loader-container">
        <div class="loader">
            <div class="spinner"></div>
            <div class="spinner"></div>
            <div class="spinner"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('subscriber.store') }}" method="POST" class="formSubmit">
                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">(Mandatory)</span></label>
                    <input type="text" class="form-control" id="name" name="name">
                    <div class="invalid-feedback name-error"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address <span class="text-danger">(Mandatory)</span></label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="invalid-feedback email-error"></div>
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

        });
    </script>
@endpush
