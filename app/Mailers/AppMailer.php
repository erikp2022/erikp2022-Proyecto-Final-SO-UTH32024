<?php

namespace App\Mailers;

use App\Models\GeneralSetting;
use App\Traits\EmailTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class AppMailer
{
    use EmailTrait;

    public function sendEmail($mailText, $email, $subject)
    {
        $setting = GeneralSetting::first();

        if ($setting->mail_driver && $setting->from_email) {
            if($setting->mail_driver == 'smtp') {
                Config::set('mail.username', $setting->smtp_username);
                Config::set('mail.password', $setting->smtp_password);
                Config::set('mail.host', $setting->smtp_host);
                Config::set('mail.driver', $setting->mail_driver);
                Config::set('mail.port', $setting->smtp_port);
                Config::set('mail.encryption', $setting->smtp_encryption);   
            }
            if($setting->mail_driver == 'mailgun') {
                Config::set('services.mailgun.domain', $setting->mailgun_domain);
                Config::set('services.mailgun.secret', $setting->mailgun_api);
            }
            Config::set('mail.from.address', $setting->from_email);
            Config::set('mail.from.name', $setting->from_name);

            try {
                Mail::send([], [], function ($message) use ($email, $subject, $mailText) {
                    $message->to($email)
                            ->subject($subject)
                            ->html($mailText);
                });
                
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }
}
