<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{asset('js/invoice.js')}}"></script>
    <!-- jQuery -->
</head>
<body class="">


<div class="container" style="min-height:500px;">
    <div class="error_msg"></div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class=''>
    </div>
    <div class="container content-invoice">
        <form action="{{route('save.invoice')}}" id="invoice-form" method="POST" class="invoice-form" role="form">
            @csrf
            <div class="load-animate animated fadeInUp">
                <div class="row">
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                        <h2 class="title">Invoice System</h2>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{$code}}" name="code" id="serial"
                                   placeholder="Serial" readonly>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="store_id" id="store_id">
                                <option value="">اختر المخزن</option>
                                @foreach($stores as $store)
                                    <option value="{{$store->id}}">{{$store->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="date" class="form-control" name="created_date" id="created_date">
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" rows="3" name="description" id="description"
                                      placeholder="Your Description"></textarea>
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
                        <h3>To,</h3>
                        <div class="form-group">
                            <select class="form-control" name="customer_id" id="customer">
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="invoice Natural" name="invoice_natural"
                                   id="invoice_natural">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <table class="table table-bordered table-hover" id="invoiceItem">
                            <tr>
                                <th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
                                <th width="23%">Item Name</th>
                                <th width="15%">Unit</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Price</th>
                                <th width="15%">Total</th>
                            </tr>
                            <tr>
                                <td><input class="itemRow" type="checkbox"></td>

                                <td>
                                    <select class="form-control" name="product_id[]" id="productName_1">
                                        <option value=""></option>
                                    </select>
                                </td>

                                <td>
                                    <select class="form-control" name="unit[]" id="unit_1">
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity"
                                           autocomplete="off"></td>
                                <td><input type="number" name="price[]" id="price_1" class="form-control price"
                                           autocomplete="off"></td>
                                <td><input type="number" name="total[]" id="total_1" class="form-control total"
                                           autocomplete="off"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <button class="btn btn-danger delete" id="removeRows" type="button">- Delete</button>
                        <button class="btn btn-success" id="addRows" type="button">+ Add More</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                        <br>
                        <br>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <span class="form-inline">
                        <div class="form-group">
                        <label>Subtotal:  </label>
                        <div class="input-group">
                        <div class="input-group-addon currency">$</div>
                        <input value="" type="number" class="form-control" name="subTotal" id="subTotal"
                               placeholder="Subtotal">
                        </div>
                        </div>
                            <div class="form-group">
                            <label>Tax Rate:  </label>
                            <div class="input-group">
                            <input value="" type="number" class="form-control" name="taxRate" id="taxRate"
                                   placeholder="Tax Rate">
                            <div class="input-group-addon">%</div>
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Tax Amount:  </label>
                            <div class="input-group">
                            <div class="input-group-addon currency">$</div>
                            <input value="" type="number" class="form-control" name="taxAmount" id="taxAmount"
                                   placeholder="Tax Amount">
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Total:  </label>
                            <div class="input-group">
                            <div class="input-group-addon currency">$</div>
                            <input value="" type="number" class="form-control" name="totalAftertax" id="totalAftertax"
                                   placeholder="Total">
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Amount Paid:  </label>
                            <div class="input-group">
                            <div class="input-group-addon currency">$</div>
                            <input value="" type="number" class="form-control" name="amountPaid" id="amountPaid"
                                   placeholder="Amount Paid">
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Amount Due:  </label>
                            <div class="input-group">
                            <div class="input-group-addon currency">$</div>
                            <input value="" type="number" class="form-control" name="amountDue" id="amountDue"
                                   placeholder="Amount Due">
                            </div>
                            </div>
                            </span>
                        </div>

                    </div>

                    <div class="form-group">
                        <input class="btn btn-success" type="submit" value="save As Draft">
                        {{--                        <input class="btn btn-primary" type="button" value="save And Print">--}}
                        {{--                        <input class="btn btn-info" type="button" value="send Approval">--}}

                        {{--                        <input data-loading-text="Saving Invoice..." type="submit" name="invoice_btn"--}}
                        {{--                               value="Save Invoice" class="btn btn-success submit_btn invoice-save-btm">--}}
                    </div>

                </div>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

