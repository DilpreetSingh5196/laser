<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(base_path('../public_html'))) {
            $this->app->usePublicPath(realpath(base_path('../public_html')));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $smtpHost = \App\Models\Setting::get('smtp_host');
                if (!empty($smtpHost)) {
                    \Illuminate\Support\Facades\Config::set('mail.default', 'smtp');
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host', $smtpHost);
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port', (int)\App\Models\Setting::get('smtp_port', 587));
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', \App\Models\Setting::get('smtp_username'));
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', \App\Models\Setting::get('smtp_password'));
                    
                    $encryption = strtolower((string)\App\Models\Setting::get('smtp_encryption', 'tls'));
                    if ($encryption === 'ssl' || $encryption === 'smtps') {
                        $scheme = 'smtps';
                    } elseif ($encryption === 'tls' || $encryption === 'smtp') {
                        $scheme = 'smtp';
                    } else {
                        $scheme = null;
                    }
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.scheme', $scheme);
                    
                    $fromAddress = \App\Models\Setting::get('smtp_from_address', \App\Models\Setting::get('smtp_username', 'hello@example.com'));
                    $fromName = \App\Models\Setting::get('smtp_from_name', \App\Models\Setting::get('company_name', 'Jai Maa Durga'));
                    
                    if (!empty($fromAddress)) {
                        \Illuminate\Support\Facades\Config::set('mail.from.address', $fromAddress);
                        \Illuminate\Support\Facades\Config::set('mail.from.name', $fromName);
                    }
                    
                    \Illuminate\Support\Facades\Mail::purge('smtp');
                }
            }
        } catch (\Exception $e) {
            // Silently ignore during migrations or when database is unreachable
        }
    }
}
