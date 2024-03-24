<x-app-layout>
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="font-weight-bold">All Invoice</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="modal-product-search d-flex">
                                <form class="mr-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText" aria-describedby="textHelp" placeholder="Search Invoice">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                                <a href="../backend/invoice-view.html" class="btn btn-primary position-relative d-flex align-items-center justify-content-between">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Invoice
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <h5 class="font-weight-bold">Invoices List</h5>
                                    <button class="btn btn-secondary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Export
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                        <tr class="text-light">
                                            <th scope="col" class="pr-0 w-01">
                                                <div class="d-flex justify-content-start align-items-end mb-1">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input m-0" id="customCheck">
                                                        <label class="custom-control-label" for="customCheck"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th scope="col">
                                                <label class="text-muted m-0" for="text1">Customer</label>
                                            </th>
                                            <th scope="col" class="dates">
                                                <label class="text-muted mb-0" for="validationServer01">Date</label>
                                            </th>
                                            <th scope="col">
                                                <label class="text-muted mb-0" for="text2">ID</label>
                                            </th>
                                            <th scope="col" class="text-right">
                                                <label class="text-muted mb-0" for="text3">Total</label>
                                            </th>
                                            <th scope="col">
                                                <label class="text-muted mb-0" for="validationServer02">Status</label>
                                            </th>
                                            <th scope="col" class="text-right">
                                                <span class="text-muted" for="validationServer01">Action</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck1">
                                                    <label class="custom-control-label" for="customCheck1"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/1.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Christian Bale</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            christian.Bale@blueberrye.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>01 Jan 2021</td>
                                            <td>
                                                IN-325560
                                            </td>
                                            <td class="text-right">
                                                $104.98
                                            </td>
                                            <td>
                                                <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Paid
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck2">
                                                    <label class="custom-control-label" for="customCheck2"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/2.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Matt Damon</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            mattdamon@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>02 Jan 2021</td>
                                            <td>
                                                IN-120010
                                            </td>
                                            <td class="text-right">
                                                $99.00
                                            </td>
                                            <td>
                                                <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Paid
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck3">
                                                    <label class="custom-control-label" for="customCheck3"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/3.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Caitriona Balfe</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            caitrionabalfe204@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>02 Jan 2021</td>
                                            <td>
                                                IN-125623
                                            </td>
                                            <td class="text-right">
                                                $249.49
                                            </td>
                                            <td>
                                                <p class="mb-0 text-warning font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Pending
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck4">
                                                    <label class="custom-control-label" for="customCheck4"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/4.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Josh Lucas</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            joshlucas007@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>02 Jan 2021</td>
                                            <td>
                                                IN-662210
                                            </td>
                                            <td class="text-right">
                                                $9.99
                                            </td>
                                            <td>
                                                <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Paid
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck5">
                                                    <label class="custom-control-label" for="customCheck5"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/5.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Jon Bernthal</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            jon.Bernthal@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>03 Jan 2021</td>
                                            <td>
                                                IN-901020
                                            </td>
                                            <td class="text-right">
                                                $90.49
                                            </td>
                                            <td>
                                                <p class="mb-0 text-danger font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Cancelled
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck6">
                                                    <label class="custom-control-label" for="customCheck6"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/6.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Noah Jupe</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            nnoahjupe@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>05 Jan 2021</td>
                                            <td>
                                                IN-902210
                                            </td>
                                            <td class="text-right">
                                                $39.99
                                            </td>
                                            <td>
                                                <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Paid
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck7">
                                                    <label class="custom-control-label" for="customCheck7"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/3.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Tracy Letts</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            tracyetts@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>06 Jan 2021</td>
                                            <td>
                                                IN-902559
                                            </td>
                                            <td class="text-right">
                                                $19.22
                                            </td>
                                            <td>
                                                <p class="mb-0 text-warning font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Pending
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck8">
                                                    <label class="custom-control-label" for="customCheck8"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/5.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Ray McKinnon</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            raymcKinnon@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>09 Jan 2021</td>
                                            <td>
                                                IN-911211
                                            </td>
                                            <td class="text-right">
                                                $102.9
                                            </td>
                                            <td>
                                                <p class="mb-0 text-danger font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Cancelled
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck9">
                                                    <label class="custom-control-label" for="customCheck9"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/4.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Remo Girone</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            remo.girone420@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>10 Jan 2021</td>
                                            <td>
                                                IN-902559
                                            </td>
                                            <td class="text-right">
                                                $13.99
                                            </td>
                                            <td>
                                                <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Paid
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input m-0" id="customCheck10">
                                                    <label class="custom-control-label" for="customCheck10"></label>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        <img class="avatar rounded-circle" src="{{asset('images/user/3.jpg')}}">
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">Jack McMullen</span>
                                                        </div>
                                                        <p class="m-0 text-secondary small">
                                                            jackmullen@blueberry.com
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>12 Jan 2021</td>
                                            <td>
                                                IN-302240
                                            </td>
                                            <td class="text-right">
                                                $25.99
                                            </td>
                                            <td>
                                                <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small><i class="fas fa-circle mr-2"></i></small>Paid
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" href="{{route('invoice.view')}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
