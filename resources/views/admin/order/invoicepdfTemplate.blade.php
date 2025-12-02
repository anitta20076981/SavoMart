<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8" />
    <title> Order Invoice</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #f3f8f8e0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.heading td {
            background: #3a8568;
            font-weight: bold;
            color: #fff;
        }

        .invoice-box table tr.details td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            font-weight: bold;
        }

        .footer {
            background: #e1dfe9;
            text-align: center;
            padding: 20px 5px;
            margin-top: 20px;
        }

        .footer td {
            text-align: center;
            padding: 0 !important;
            font-size: 13px;
            border: none
        }

        .footer a {
            color: #555;
        }

        tr.top td {
            border: none;
        }

        tr.information td {
            border: none;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /* RTL */
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

        td {
            font-size: 13px;
        }

        .invoice-title {
            background: #3a8568;
            color: #fff;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            border: none;
        }

        b {
            font-size: 14px;
        }

        tr.information {
            width: 100%;
        }
    </style>
</head>

<body>
    @php
        $invoiceData = $invoicedata;
        $settings = $settings;
        $currency = $currency;
        $order = $order;
        $invoice_total_amount = $invoice_total_amount;
        $invoice_tax_amount = $invoice_tax_amount;
    @endphp

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="3">
                    <table>
                        <tr>
                            <td class="title">
                                <a href="#" class="d-block mw-150px ms-sm-auto">
                                    <img alt="Logo" src="{{ asset('images/admin/logos/logo.png') }}" class="w-100" />
                                </a>
                            </td>
                            <td>
                                {{ $settings->get('company_name')->value }}<br />
                                {{ $settings->get('company_description')->value }}, <br />
                                Phone : {{ $settings->get('phone')->value }}<br />
                                {{ $settings->get('email')->value }}<br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table>
            <tr class="information">
                <td class="invoice-title"> Performa invoice</td>
            </tr>
        </table>
        <table>
            <tr class="information">
                <td colspan="">
                    <table>
                        <tr>
                            <td><b>Shipping To</b><br>
                                {{ $order->location->address_line1 }},
                                <br />{{ $order->location->street }},
                                <br />Ph : {{ $order->location->number }}<br />
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="text-align:right"><b>Invoice#</b></td>
                                        <td style="text-align:right">{{ $invoiceData['invoice_no'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right"><b>Invoice Date</b></td>
                                        <td style="text-align:right">{{ $invoiceData['created_at'] }}</td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th>#</th>
                <th>Item And Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Shipping cost</th>
                <th>Amount</th>
            </tr>
            @foreach ($invoicedata->invoiceItems as $key => $item)
                <tr class="item-list">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->product->name }} - {{ $item->product->sku }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td>0</td>
                    <td>{{ $item->total_amount - $item->tax_amount }}</td>
                </tr>
            @endforeach
        </table>
        <table style="margin-top:5px;">
            <tr>
                <td style="text-align: right;padding-right: 10px;"><b>Sub total</b></td>
                <td> &#8377;{{ $invoice_total_amount }}</td>
            </tr>
            <tr>
                <td style="text-align: right;padding-right: 10px;"><b>Tax</b></td>
                <td> &#8377; {{ $invoice_tax_amount }}</td>
            </tr>
            <tr>
                <td style="text-align: right;padding-right: 10px;"><b>Total</b></td>
                <td> &#8377; {{ $invoice_total_amount + $invoice_tax_amount }}</td>
            </tr>
            <tr>
                <td style="text-align: right;padding-right: 10px;"><b>Balance</b></td>
                <td> &#8377;0.00</td>
            </tr>
        </table>
        <table style="margin-top: 20px;">
            <tr class="footer">
                <td colspan="3">
                    <table>
                        <tr>
                            <td style="padding: 5px !important;">
                                Tearms and conditions
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
