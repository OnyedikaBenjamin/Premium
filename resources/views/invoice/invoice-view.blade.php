<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="font-weight-bold">Invoice View</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row pb-4 mx-0 card-header-border">
                            <div class="col-lg-12 mb-3">
                                <img class="avatar avatar-50 is-squared" src="{{asset('images/logodemo/02-blue.png')}}">
                            </div>
                            <div class="col-lg-6">
                                <div class="text-left">
                                    <h5 class="font-weight-bold mb-2">Invoice number</h5>
                                    <p class="mb-0">IN-05866</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="text-right">
                                    <h5 class="font-weight-bold mb-2">Invoice Date</h5>
                                    <p class="mb-0">2nd Oct 2019 03:16 PM</p>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-4 pb-5 mx-0">
                            <div class="col-lg-6">
                                <div class="text-left">
                                    <h5 class="font-weight-bold mb-3">Invoice From</h5>
                                    <p class="mb-0 mb-1">Chris Wood</p>
                                    <p class="mb-0 mb-1">4183 Forest Avenue</p>
                                    <p class="mb-0 mb-1">New York</p>
                                    <p class="mb-0 mb-1">10011</p>
                                    <p class="mb-0 mb-2">USA</p>
                                    <p class="mb-0 mb-2">chris.wood@blueberry.com</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="text-right">
                                    <h5 class="font-weight-bold mb-3">Invoice To</h5>
                                    <p class="mb-0 mb-1">Blueberry LLC</p>
                                    <p class="mb-0 mb-1">354 Roy Allen</p>
                                    <p class="mb-0 mb-1">Denver</p>
                                    <p class="mb-0 mb-1">80202</p>
                                    <p class="mb-0 mb-2">USA</p>
                                    <p class="mb-0 mb-2">info@blueberry.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item p-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                <tr class="text-muted">
                                                    <th scope="col" class="text-left">No</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col" class="text-right">Quantity</th>
                                                    <th scope="col" class="text-right">Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="text-left">
                                                        1
                                                    </td>
                                                    <td>
                                                        OR-325548
                                                    </td>
                                                    <td class="text-right">
                                                        6
                                                    </td>
                                                    <td class="text-right">
                                                        $800
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        2
                                                    </td>
                                                    <td>
                                                        OR-500008
                                                    </td>
                                                    <td class="text-right">
                                                        3
                                                    </td>
                                                    <td class="text-right">
                                                        $500
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        3
                                                    </td>
                                                    <td>
                                                        OR-654412
                                                    </td>
                                                    <td class="text-right">
                                                        5
                                                    </td>
                                                    <td class="text-right">
                                                        $600
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-end mb-2">
                                            Subtotal: <p class="ml-2 mb-0">$1,600</p>
                                        </div>
                                        <div class="d-flex justify-content-end mb-2">
                                            Taxes: <p class="ml-2 mb-0">$300</p>
                                        </div>
                                        <div class="d-flex justify-content-end mb-2">
                                            Total: <p class="ml-2 mb-0 font-weight-bold">$1,900</p>
                                        </div>

                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-12">
                                <div class="d-flex flex-wrap justify-content-between align-items-center p-4">
                                    <div class="flex align-items-start flex-column">
                                        <h6>Notes</h6>
                                        <p class="mb-0 my-2">Please send all items at the same time to the shipping address. Thanksin advance.</p>
                                    </div>
                                    <div>
                                        <button class="btn btn-secondary px-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Print
                                        </button>
                                        <button class="btn btn-primary px-4">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
