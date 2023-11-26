<?php

namespace App\Notifications;

use App\Helpers\NotificationTemplateParserTrait;
use App\Settings\GeneralSettings;
use App\Settings\NotificationSettings;
use App\Settings\ShipmentSettings;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class VerifyEmail extends Notification
{
    use NotificationTemplateParserTrait;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    private $details;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        $this->getCustomerTemplateData('account_created_not_verified');
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $shipmentSettings = new ShipmentSettings();
        $generalSettings = new GeneralSettings();
        $notificationSettings = new NotificationSettings();

        $verificationUrl = $this->verificationUrl($notifiable);

        //dd($verificationUrl);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        $this->details = [
            'fields' => [
            ],
            'shortcodes' => [
                '[VERIFICATION_URL]' => $verificationUrl,
                '[EMAIL]' => $notifiable->email,
                '[SITE_NAME]' => $generalSettings->app_name,
                '[CUSTOMER_NAME]' => $notifiable->name,
            ]
        ];

        $message = new MailMessage;
        $message->from($notificationSettings->notification_email_from, $notificationSettings->notification_email_name);
        $message->subject($this->shortCodeReplacer($this->template->subject));
        $message->greeting($this->shortCodeReplacer($this->template->greeting));
        $message->line(new HtmlString($this->shortCodeReplacer($this->template->email_body)));
        $message->action($this->shortCodeReplacer($this->template->action_text), $this->shortCodeReplacer($this->template->action_url));
        $message->line($this->shortCodeReplacer($this->template->thanks));

        return $message;

    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {

        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param \Closure $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
