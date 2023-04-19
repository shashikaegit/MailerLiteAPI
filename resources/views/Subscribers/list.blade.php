@extends('layouts.app')

@section('title', 'Subscribers')

@section('content')
    <div class="breadcrumb-box">
        <div class="row align-items-center">
            <div class="col-md-8 col-lg-8">
                <h4 class="page-title">Subscriber List</h4>
                <div class="breadcrumb-list">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/subscriber') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/subscriber') }}">Subscribers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subscriber List</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="widgetbar text-right">
                    <a href="{{url('/subscriber/create')}}" class="btn btn-info">Add Subscriber</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-box">
        <div class="card-body">
            <div class="searchBox">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="search-input">Search: &nbsp;</label>
                        <input type="text" class="form-control form-control-sm" id="subscribersList_search" placeholder="example@example.com">
                    </div>
                </div>
            </div>

            <table id="subscribersList" class="display"></table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let dataArray = [];
            dataArray.next = '';
            dataArray.previous = '';
            dataArray.prevStart = 0;

            // Initialize toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            const table =  $('#subscribersList').DataTable({
                processing: true,
                serverSide: true,
                pagingType: "simple",
                searching: false,
                ajax: {
                    url: "/subscriber/list",
                    method: "GET",
                    data: function (d) {
                        d.searchField = $('#subscribersList_search').val();
                        d.next = dataArray.next; // Send next page token
                        d.previous = dataArray.previous; // Send previous token

                        // Check which button is clicked (Next or Previous)
                        if (d.start > dataArray.prevStart) {
                            d.click = 'next';
                        } else if (d.start < dataArray.prevStart) {
                            d.click = 'previous'
                        }
                        dataArray.prevStart = d.start; // Set start number
                    },
                    dataSrc: function(response) {
                        if (response.status == false) {
                            if (response.search_failed == false) {
                                toastr.error(response.message);
                            }
                        }else{
                            dataArray.next = response.meta.next_cursor;
                            dataArray.previous = response.meta.prev_cursor;

                            // Return the data for the datatable
                            return response.data;
                        }
                    },
                },
                columns: [
                    {
                        title: 'Email',
                        name: 'email',
                        orderable: false,
                        data : null,
                        render: function(data) {
                            return `<a href="subscriber/`+data.id+`/edit" class="text-info">`+data.email+`<a>`;
                        }
                    },
                    { data: 'fields.name', name: 'name', title:'Name', orderable: false },
                    {
                        title: 'Country',
                        data: 'fields.country',
                        orderable: false,
                        render: function(data) {
                            if (data != null){
                                return data;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        title: 'Subscribe Date',
                        data: 'subscribed_at',
                        orderable: false,
                        render: function(data) {
                            const date = new Date(data);
                            const day = date.getDate();
                            const month = date.getMonth() + 1; // Months are zero-indexed, so we add 1
                            const year = date.getFullYear();
                            return `${day}/${month}/${year}`;
                        }
                    },
                    {
                        title: 'Subscribe Time',
                        data: 'subscribed_at',
                        orderable: false,
                        render: function(data) {
                            const time = new Date(data);
                            const hours = time.getHours();
                            const minutes = time.getMinutes();
                            const formattedHours = hours < 10 ? `0${hours}` : hours;
                            const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;
                            return `${formattedHours}:${formattedMinutes}`;
                        }
                    },
                    {
                        title: 'Actions',
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return '<button data-id="'+data+'" class="btn btn-outline-danger btn-sm deleteItem"><i class="fa-regular fa-trash-can"></i></button>';
                        }
                    }
                ],
            });

            // Add debounce delay for search field to avoid calling search function on every keystroke
            const debounceDelay = 1000;
            $('#subscribersList_search').on('keyup', _.debounce(function() {
                table.search($(this).val()).draw();
            }, debounceDelay));

            // Handle delete button click event
            $('#subscribersList tbody').on('click', '.deleteItem', function() {

                $.ajax({
                    url: '/subscriber/'+$(this).attr('data-id'),
                    type: 'DELETE',
                    data: {},
                    success: function (response) {
                        if (response.status === false) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message);
                            table.draw();
                        }
                    },
                    error: function (xhr) {
                        toastr.error(xhr.message);
                    }
                });
            });
        });
    </script>
@endpush
