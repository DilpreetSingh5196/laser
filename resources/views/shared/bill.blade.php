<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill - Order #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555; background: #fff; margin-top: 50px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; }
        .invoice-box table td { padding: 5px; vertical-align: top; word-break: break-word; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 35px; line-height: 45px; color: #333; font-weight: bold; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td{ border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; font-size: 1.2em; }
        @media only screen and (max-width: 768px) {
            .invoice-box { padding: 20px 15px; margin-top: 20px; font-size: 15px; }
            .invoice-box table tr.top table td,
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: left !important;
            }
            .invoice-box table tr.top table td:nth-child(1),
            .invoice-box table tr.information table td:nth-child(1) {
                padding-bottom: 20px;
            }
        }
        @media print {
            .no-print { display: none; }
            .invoice-box { box-shadow: none; border: none; margin-top: 0; }
        }
    </style>
</head>
<body>
    <div class="container mb-3 mt-3 text-end no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Print Bill</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <span style="color: #0d4b80;">{{ \App\Models\Setting::get('company_name', 'Jai Maa Durga') }}</span>
                            </td>
                            
                            <td>
                                Invoice #: {{ $order->id }}<br>
                                Created: {{ $order->created_at->format('M d, Y') }}<br>
                                Status: <span style="color: green; font-weight: bold;">{{ $order->payment_status }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Billed To:</strong><br>
                                {{ $order->client->firm_name }}<br>
                                {{ $order->client->client_name }}<br>
                                {{ $order->client->mobile_number }}<br>
                                {{ $order->client->email }}
                            </td>
                            
                            <td>
                                <strong>Billed By:</strong><br>
                                {{ \App\Models\Setting::get('company_name', 'Jai Maa Durga') }}<br>
                                @if(\App\Models\Setting::get('company_address'))
                                    {!! nl2br(e(\App\Models\Setting::get('company_address'))) !!}<br>
                                @else
                                    Patiala, Punjab<br>
                                @endif
                                @if(\App\Models\Setting::get('company_phones'))
                                    Ph: {{ str_replace(',', ', ', str_replace(', ', ',', \App\Models\Setting::get('company_phones'))) }}<br>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Item Description</td>
                <td>Amount</td>
            </tr>
            
            @foreach($order->items as $index => $item)
            <tr class="item {{ $loop->last ? 'last' : '' }}">
                <td>
                    <strong>Item #{{ $index + 1 }}</strong><br>
                    <small>Quantity: {{ $item->quantity }}</small><br>
                    @if($item->length_inch || $item->length_cm)
                        <small>Length: {{ $item->length_inch ? $item->length_inch . ' Inch' : $item->length_cm . ' cm' }}</small><br>
                    @endif
                    @if($item->breadth_inch || $item->breadth_cm)
                        <small>Breadth: {{ $item->breadth_inch ? $item->breadth_inch . ' Inch' : $item->breadth_cm . ' cm' }}</small><br>
                    @endif
                    @if($item->description)
                        <small>Description: {{ $item->description }}</small><br>
                    @endif
                </td>
                
                <td>
                    @if($item->price)
                        Rs. {{ number_format($item->price, 2) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
            
            <tr class="total">
                <td></td>
                <td>
                   Total: Rs. {{ number_format($order->price, 2) }}
                </td>
            </tr>
        </table>
        
        <div class="mt-5 text-center text-muted" style="font-size: 0.85em;">
            <p>Thank you for your business!</p>
            @if(str_starts_with((string)$order->payment_status, 'Approved'))
                <p class="text-success fw-bold">This bill has been paid and approved ({{ $order->payment_status == 'Approved with Cash' ? 'Payment Mode: Cash' : 'Verified Online' }}).</p>
            @endif
        </div>
    </div>
</body>
</html>
