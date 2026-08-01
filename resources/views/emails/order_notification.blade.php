<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $notifTitle }}</title>
    <style>
        /* Base fallback styles for clients that support stylesheets */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; line-height: 1.6; color: #333333; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f0f2f5; padding: 30px 10px; }
        .main-content { max-width: 680px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e1e8ed; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 12px 16px; text-align: left; }
    </style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px 0; color: #333333;">

<div style="max-width: 680px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e1e8ed;">

    <!-- Header Section -->
    <div style="background-color: #0d4b80; background: linear-gradient(135deg, #0d4b80, #1a237e); color: #ffffff; padding: 25px 30px; text-align: center; border-bottom: 4px solid #f59e0b;">
        <h1 style="margin: 0; font-size: 26px; font-weight: 700; letter-spacing: 0.5px; color: #ffffff;">
            {{ \App\Models\Setting::get('company_name', 'Jai Maa Durga') }}
        </h1>
        <p style="margin: 5px 0 0; font-size: 14px; color: #e0e7ff; text-transform: uppercase; letter-spacing: 1px;">Order Notification</p>
    </div>
    
    <!-- Body Content -->
    <div style="padding: 30px;">
        <div style="font-size: 22px; font-weight: 700; color: #0d4b80; margin-bottom: 12px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
            {{ $notifTitle }}
        </div>
        <p style="font-size: 15px; color: #475569; margin-top: 0; margin-bottom: 25px; line-height: 1.6;">
            {{ $notifMessage }}
        </p>
        
        @if($order)
        <!-- Order Summary Cards / Table -->
        <h3 style="font-size: 16px; font-weight: 700; color: #334155; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Order Overview</h3>
        <table style="width: 100%; border-collapse: collapse; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 25px; font-size: 14px;">
            <tr>
                <th style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #64748b; width: 35%; background-color: #f1f5f9; text-align: left;">Order Reference</th>
                <td style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 16px; font-weight: bold;">#{{ $order->id }}</td>
            </tr>
            <tr>
                <th style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #64748b; background-color: #f1f5f9; text-align: left;">Order Date</th>
                <td style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #1e293b;">{{ $order->created_at->format('d M, Y - h:i A') }}</td>
            </tr>
            @if($order->client)
            <tr>
                <th style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #64748b; background-color: #f1f5f9; text-align: left;">Client Details</th>
                <td style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #1e293b;">
                    <strong>{{ $order->client->client_name }}</strong>
                    @if($order->client->firm_name) <br><span style="color: #64748b;">Firm: {{ $order->client->firm_name }}</span> @endif
                    @if($order->client->mobile_number) <br><span style="color: #64748b;">Ph: {{ $order->client->mobile_number }}</span> @endif
                </td>
            </tr>
            @endif
            <tr>
                <th style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #64748b; background-color: #f1f5f9; text-align: left;">Total Order Price</th>
                <td style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-size: 16px;">
                    <strong>{{ $order->price ? 'Rs. ' . number_format((float)$order->price, 2) : 'Pending Assignment' }}</strong>
                </td>
            </tr>
            <tr>
                <th style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0; color: #64748b; background-color: #f1f5f9; text-align: left;">Order Status</th>
                <td style="padding: 10px 15px; border-bottom: 1px solid #e2e8f0;">
                    @if($order->status == 'Approved')
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #d1fae5; color: #065f46; border: 1px solid #34d399;">Approved</span>
                    @else
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #e0f2fe; color: #0369a1; border: 1px solid #38bdf8;">{{ $order->status }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th style="padding: 10px 15px; color: #64748b; background-color: #f1f5f9; text-align: left;">Payment Status</th>
                <td style="padding: 10px 15px;">
                    @if($order->payment_status == 'Approved')
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #d1fae5; color: #065f46; border: 1px solid #34d399;">Approved</span>
                    @elseif($order->payment_status == 'Rejected')
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #fef2f2; color: #991b1b; border: 1px solid #f87171;">Rejected</span>
                    @else
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24;">{{ $order->payment_status }}</span>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Items Breakdown Section -->
        @if($order->items && $order->items->count() > 0)
        <h3 style="font-size: 16px; font-weight: 700; color: #334155; margin-bottom: 10px; margin-top: 30px; text-transform: uppercase; letter-spacing: 0.5px;">Order Items & Specifications ({{ $order->items->count() }})</h3>
        
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1; margin-bottom: 25px; font-size: 13px;">
            <thead>
                <tr style="background-color: #e2e8f0; color: #1e293b;">
                    <th style="padding: 12px 10px; border: 1px solid #cbd5e1; text-align: center; width: 15%;">Item</th>
                    <th style="padding: 12px 10px; border: 1px solid #cbd5e1; text-align: left; width: 35%;">Specifications (Qty & Dimensions)</th>
                    <th style="padding: 12px 10px; border: 1px solid #cbd5e1; text-align: left; width: 30%;">Description</th>
                    <th style="padding: 12px 10px; border: 1px solid #cbd5e1; text-align: right; width: 20%;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f8fafc' }};">
                    <td style="padding: 12px 10px; border: 1px solid #cbd5e1; text-align: center; font-weight: bold; color: #334155;">
                        #{{ $index + 1 }}
                    </td>
                    <td style="padding: 12px 10px; border: 1px solid #cbd5e1; color: #334155;">
                        <div style="font-weight: bold; color: #0f172a; font-size: 14px;">Qty: {{ $item->quantity }}</div>
                        @if($item->length_inch || $item->breadth_inch)
                            <div style="color: #475569; margin-top: 3px;">
                                <strong>Size:</strong> {{ $item->length_inch ?? '-' }} &times; {{ $item->breadth_inch ?? '-' }} Inches
                            </div>
                        @elseif($item->length_cm || $item->breadth_cm)
                            <div style="color: #475569; margin-top: 3px;">
                                <strong>Size:</strong> {{ $item->length_cm ?? '-' }} &times; {{ $item->breadth_cm ?? '-' }} cm
                            </div>
                        @endif
                    </td>
                    <td style="padding: 12px 10px; border: 1px solid #cbd5e1; color: #475569;">
                        {{ !empty($item->description) ? $item->description : 'No description provided.' }}
                    </td>
                    <td style="padding: 12px 10px; border: 1px solid #cbd5e1; text-align: right; font-weight: 600; color: #0f172a;">
                        @if($item->price)
                            Rs. {{ number_format((float)$item->price, 2) }}
                        @else
                            <span style="color: #d97706;">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                <!-- Total Row -->
                <tr style="background-color: #f1f5f9; font-weight: bold; font-size: 14px;">
                    <td colspan="3" style="padding: 12px 15px; border: 1px solid #cbd5e1; text-align: right; color: #1e293b;">
                        Grand Total:
                    </td>
                    <td style="padding: 12px 10px; border: 1px solid #cbd5e1; text-align: right; color: #0d4b80; font-size: 15px;">
                        {{ $order->price ? 'Rs. ' . number_format((float)$order->price, 2) : 'Pending' }}
                    </td>
                </tr>
            </tbody>
        </table>
        @endif

        @if($order->admin_remark)
        <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 12px 16px; margin-bottom: 25px; border-radius: 4px;">
            <strong style="color: #92400e; display: block; margin-bottom: 4px;">Admin Remark / Instruction:</strong>
            <span style="color: #b45309;">{{ $order->admin_remark }}</span>
        </div>
        @endif

        @endif
        
        <!-- Action Button -->
        <div style="text-align: center; margin: 35px 0 15px;">
            <a href="{{ url('/') }}" style="display: inline-block; background-color: #2563eb; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 10px rgba(37,99,235,0.3); letter-spacing: 0.5px;">
                Open Portal to Manage Order
            </a>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background-color: #f8fafc; color: #64748b; text-align: center; padding: 20px 30px; font-size: 12px; border-top: 1px solid #e2e8f0; line-height: 1.8;">
        <p style="margin: 0 0 5px; font-weight: 600; color: #475569;">{{ \App\Models\Setting::get('company_name', 'Jai Maa Durga') }}</p>
        @if(\App\Models\Setting::get('company_address'))
            <p style="margin: 0; color: #94a3b8;">{!! nl2br(e(\App\Models\Setting::get('company_address'))) !!}</p>
        @endif
        @if(\App\Models\Setting::get('company_phones'))
            <p style="margin: 0; color: #94a3b8;">Phone: {{ \App\Models\Setting::get('company_phones') }}</p>
        @endif
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 15px 0;">
        <p style="margin: 0; color: #94a3b8;">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'Jai Maa Durga') }}. All rights reserved.</p>
        <p style="margin: 0; color: #cbd5e1;">This is an automated operational notification. Please do not reply directly to this message.</p>
    </div>

</div>

</body>
</html>
