<?php

/**
 * CourierPlus - Courier Management System
 * Copyright (c) chiwextech.com. All Rights Reserved
 *
 * Website: http://www.chiwextech.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Helpers;

use App\Models\AdminNotificationTemplate;
use App\Models\NotificationTemplate;
use App\Settings\GeneralSettings;
use App\Settings\NotificationSettings;
use App\Settings\ShipmentSettings;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\HtmlString;
use NotificationChannels\WebPush\WebPushMessage;
use Propaganistas\LaravelPhone\PhoneNumber;
use Twilio\Rest\Client;

trait NotificationTemplateParserTrait
{
    public $template;
    public $data;
    public $notify;

    public function via($notifiable)
    {

        if ($this->template && !empty($this->template->channels)) {
            return $this->template->channels;
        }

    }

    public function getCustomerTemplateData($templateNname)
    {
        $this->template = NotificationTemplate::query()
            ->where('name', $templateNname)
            ->first();
    }

    public function getAdminTemplateData($templateNname)
    {

        $this->template = AdminNotificationTemplate::query()
            ->where('name', $templateNname)
            ->first();

    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->shortCodeReplacer($this->template->subject))
            ->image(getLogo())
            ->icon(getLogo())
            ->body($this->shortCodeReplacer($this->template->webpush_body))
            ->action(
                $this->shortCodeReplacer($this->template->action_text),
                $this->shortCodeReplacer($this->template->action_url)
            )
            ->data(['id' => $notification->id]);
    }

    public function toMail($notifiable)
    {
        

        if (!is_null($notifiable)) {

            $message = new MailMessage;

            $message->from('laravel@gmail.com', 'admin');

            $message->subject($this->shortCodeReplacer($this->template->subject));
            $message->greeting($this->shortCodeReplacer($this->template->greeting));

            $message->line(new HtmlString($this->shortCodeReplacer($this->template->email_body)));

            $message->action(
                $this->shortCodeReplacer($this->template->action_text),
                $this->shortCodeReplacer($this->template->action_url)
            );

            $message->line($this->shortCodeReplacer($this->template->thanks));

            return $message;

        }

    }

    public function toSlack($notifiable)
    {

        $settings = new GeneralSettings();

        $content = '';

        foreach ($this->parseBody($this->template->slack_body) as $body) {
            $content .= $body;
        }

        //dd($this->template->slack_channel, $content);

        try {

            $message = new SlackMessage;
            $message->content($content);
            $message->image(getLogo());
            $message->from($settings->app_name, ':ghost:');
            $message->to($this->template->slack_channel);

            $message->attachment(function (SlackAttachment $attachment) {

                $attachment->title(
                    $this->shortCodeReplacer($this->template->action_text),
                    $this->shortCodeReplacer($this->template->action_url)
                );

                $attachment->action(
                    $this->shortCodeReplacer($this->template->action_text),
                    $this->shortCodeReplacer($this->template->action_url)
                );

                if (isset($this->details['fields'])) {
                    $attachment->fields($this->details['fields']);
                }

            });

            return $message;

        } catch (\Throwable $e) {

            report($e);

            //admin_error('Slack Error', $e->getMessage());

        }


    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())
            ->content($this->shortCodeReplacer($this->template->sms_body));
    }


    public function toTwilio($notifiable)
    {

        $code = getCountryCodeById($notifiable->phone_code);
        $number = $notifiable->phone;

        $phone_number = PhoneNumber::make($number, $code)->formatE164();

        if (!is_null($phone_number)) {

            $client = new Client(setting('twiliow_account_id'), setting('twiliow_account_token'));
            $message = $client->messages->create(
                $phone_number,
                [
                    'from' => setting('twiliow_sender_from'), // From a valid Twilio number
                    'body' => $this->shortCodeReplacer($this->template->sms_body)
                ]
            );

        }

    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->shortCodeReplacer($this->template->database_body),
            'url' => admin_url($this->shortCodeReplacer($this->template->action_url)),
        ];
    }

    public function parseBody($body)
    {

        $collect = collect($body);

        $messages = $collect->map(function ($value) {
            if (isset($value['line'])) {
                return $this->shortCodeReplacer($value['line']);
            }
        });

        return $messages->all();

    }

    public function shortCodeReplacer($content)
    {

        $parsed = '';

        if (isset($this->details['shortcodes'])) {

            $parsed = str_replace(
                array_keys($this->details['shortcodes']),
                array_values($this->details['shortcodes']),
                $content
            );

        }

        return $parsed;

    }

}
