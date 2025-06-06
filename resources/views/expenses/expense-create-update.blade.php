@php $expense_types = config('const.expense_type'); @endphp
<div class="ibox-content m-b-sm border-bottom">
    <form method="POST" role="form" action="" id="expenseForm">
        @csrf
        <input type="hidden" name="item_id" id="item_id" value="0">
        <div class="row">
            <div class="ibox-title d-flex justify-content-between pr-3 align-items-center flex-wrap">
                <h5 class="mb-0 mt-1">Add Expense </h5>
            </div>
        </div>
        <div class="row">

            <div class="col-12 col-lg-7">
                <div class="row">

                    <div class="col-sm-6 col-xl-3">
                        <div class="form-group">
                            <label class="col-form-label" for="home_filter">Care Home Name *</label>
                            <select id="home_filter" name="home_name"
                                class="form-control select2_element w-100">
                                <option value="">Select care home</option>
                                @foreach ($homes as $homeKey => $home)
                                    <option value="{{ $homeKey }}">{{ $home }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="form-group">
                            <label class="col-form-label" for="user_type">Select Type *</label>
                            <select name="type" id="user_type" class="form-control" >
                                <option value="">Select</option>
                                <option value="1">Patient</option>
                                <option value="2">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3 d-none" id="patient_list_div">
                        <div class="form-group">
                            <label class="col-form-label" for="user_id">Patients *</label>
                            <select class="form-control" name="patient_id" id="patient_id" >
                                <option value="">Select</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3 d-none" id="user_list_div">
                        <div class="form-group">
                            <label class="col-form-label" for="patient_id">Users *</label>
                            <select class="form-control" name="user_id" id="user_id" >
                                <option value="">Select</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="form-group">
                            <label class="col-form-label" for="expense_type">Expense Type *</label>
                            <select class="form-control px-1" name="expense_type" id="expense_type" >
                                <option value="">Expense Type</option>
                                @foreach ($expense_types as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="form-group">
                            <label class="col-form-label" for="amount">Amount *</label>
                            <input type="text" id="amount" name="amount" value="" placeholder="Amount"
                                class="form-control valid_price" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="row">
                    <div class="col w-100">
                        <div class="form-group">
                            <label class="col-form-label" for="description">Description *</label>
                            <input type="text" id="description" name="description" value=""
                                placeholder="Description" class="form-control" >
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto">
                        <label class="col-form-label d-none d-lg-block" style="min-height: 34px;"></label>
                        <div class="text-center" id="submit_button_div">
                            <button type="button" class="btn btn-primary" id="submit_expense_form"
                                data-submit-url="{{ route('expenses.store') }}">Save</button>
                        </div>
                        <div class="text-center" id="loader_button_div" style="display: none;">
                            <button type="button" class="btn btn-primary" disabled>
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Wait...
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
