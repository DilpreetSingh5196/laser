<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Mail\OrderNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Retrieve array of admin notification emails from settings.
     */
    public static function getAdminEmails(): array
    {
        $emailsSetting = Setting::get('notification_emails');
        if (empty($emailsSetting)) {
            return [];
        }

        // Try decoding JSON array first
        $decoded = json_decode($emailsSetting, true);
        if (is_array($decoded)) {
            $emails = $decoded;
        } else {
            // Fallback to comma-separated parsing
            $emails = explode(',', $emailsSetting);
        }

        return array_values(array_filter(array_map('trim', $emails), function($email) {
            return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
        }));
    }

    /**
     * Send order event notification to both the client and all configured admins.
     */
    public static function sendOrderNotification(Order $order, string $title, string $message): void
    {
        try {
            $order->load(['client', 'items']);
            
            $recipients = self::getAdminEmails();
            
            if ($order->client && !empty($order->client->email) && filter_var($order->client->email, FILTER_VALIDATE_EMAIL)) {
                $recipients[] = trim($order->client->email);
            }

            $uniqueRecipients = array_unique($recipients);

            if (empty($uniqueRecipients)) {
                return;
            }

            foreach ($uniqueRecipients as $recipient) {
                try {
                    Mail::to($recipient)->send(new OrderNotificationMail($order, $title, $message));
                } catch (\Exception $mailEx) {
                    Log::error("NotificationService: Failed delivery to {$recipient}. Error: " . $mailEx->getMessage());
                }
            }
        } catch (\Exception $ex) {
            Log::error("NotificationService: Overall error during notification process: " . $ex->getMessage());
        }
    }

    /**
     * Send tailored order completion / payment verification notifications to client and admin.
     */
    public static function sendPaymentVerificationNotification(Order $order): void
    {
        try {
            $order->load(['client', 'items']);

            if ($order->payment_status === 'Approved') {
                // Dedicated client notification: "Your Order is Completed"
                if ($order->client && !empty($order->client->email) && filter_var($order->client->email, FILTER_VALIDATE_EMAIL)) {
                    $clientEmail = trim($order->client->email);
                    try {
                        Mail::to($clientEmail)->send(new OrderNotificationMail(
                            $order,
                            "Your Order (#{$order->id}) is Completed!",
                            "Great news! Your payment of Rs. " . number_format((float)$order->price, 2) . " for Order #{$order->id} has been verified and approved. Your order is now marked as Completed. You can review your finalized order details and item specifications below, or log into the portal to print your invoice bill." . ($order->admin_remark ? " Admin Note: {$order->admin_remark}" : "")
                        ));
                    } catch (\Exception $mailEx) {
                        Log::error("NotificationService: Failed delivery of order completion to client ({$clientEmail}). Error: " . $mailEx->getMessage());
                    }
                }

                // Admin notification: "Order Completed & Payment Approved"
                $adminEmails = self::getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    try {
                        Mail::to($adminEmail)->send(new OrderNotificationMail(
                            $order,
                            "Order Completed & Payment Approved (#{$order->id})",
                            "Payment of Rs. " . number_format((float)$order->price, 2) . " for Order #{$order->id} from client " . ($order->client->firm_name ?? '') . " (" . ($order->client->client_name ?? '') . ") has been approved and marked as completed." . ($order->admin_remark ? " Remark: {$order->admin_remark}" : "")
                        ));
                    } catch (\Exception $mailEx) {
                        Log::error("NotificationService: Failed delivery to admin ({$adminEmail}). Error: " . $mailEx->getMessage());
                    }
                }
            } else {
                // Payment Rejected notification
                self::sendOrderNotification(
                    $order,
                    "Order Payment Rejected (#{$order->id})",
                    "Payment for Order #{$order->id} has been marked as Rejected." . ($order->admin_remark ? " Reason / Remark: {$order->admin_remark}" : "")
                );
            }
        } catch (\Exception $ex) {
            Log::error("NotificationService: Error sending payment verification notification: " . $ex->getMessage());
        }
    }
}
