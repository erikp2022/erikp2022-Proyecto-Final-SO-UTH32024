@section('style')
    <!-- data table css -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fixedHeader.bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
@stop

<div class="card-body table-responsive">
    <div class="row mb-2">
        <div class="col-md-4 mb-2">
            <select class="form-control" id="ticketDepartment">
                <option value="all" selected>{{ __('theme.all_department') }}</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <select class="form-control" id="ticketPriority">
                <option value="all" selected>{{ __('theme.all_priority') }}</option>
                <option value="low">{{ __('theme.low') }}</option>
                <option value="medium">{{ __('theme.medium') }}</option>
                <option value="medium">{{ __('theme.high') }}</option>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <div id="reportrange" class="w-100 pointer pad-border">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
            </div>
        </div>
    </div>

    <table id="data_table" class="table table-bordered table-striped table-hover dataTable w-100">
        <thead>
        <tr>
            <th>{{ __('theme.sl_no') }}</th>
            <th>{{ __('theme.department') }}</th>
            <th>{{ __('theme.id') }}</th>
            <th>{{ __('theme.title') }}</th>
            <th>{{ __('theme.priority') }}</th>
            <th>{{ __('theme.user') }}</th>
            <th>{{ __('theme.status') }}</th>
            <th>{{ __('theme.last_updated') }}</th>
            <th>{{ __('theme.actions') }}</th>
        </tr>
        </thead>
    </table>
</div>

<!-- Edit Product Modal -->
<div class="modal" id="TicketAssignedDepartmentModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg p-3 mb-5 bg-white rounded">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">{{ __('theme.change_or_assign') }}</h5>
                <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                    <strong>{{ __('theme.success') }} </strong>{{ __('theme.ticket_successfully_assigned') }}.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="AssignedTicketModalBody">

                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="SubmitTicketAssignedForm">{{ __('theme.update') }}</button>
                <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">{{ __('theme.close') }}</button>
            </div>
        </div>
    </div>
</div>

<!--page-loader-->
<div class="page-loader d-none">
    <div class="loader">
        <span class="dot dot_1"></span>
        <span class="dot dot_2"></span>
        <span class="dot dot_3"></span>
        <span class="dot dot_4"></span>
    </div>
</div>

@section('js')
    <!-- dataTables  -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <!-- bootstrap dataTables  -->
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.fixedHeader.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/dtMain.js') }}"></script>
@endsection