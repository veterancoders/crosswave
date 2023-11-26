<?php

namespace App\Notifications;

use App\Helpers\NotificationTemplateParserTrait;
use App\Settings\GeneralSettings;
use App\Settings\NotificationSettings;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class ResetPassword extends Notification
{
    use NotificationTemplateParserTrait;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    public $details;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token, $user)
    {

        $generalSetings = new GeneralSettings();

        $verificationUrl = url(config('app.url').route('password.reset', $token, false));

        $this->details = [
            'fields' => [
            ],
            'shortcodes' => [
                '[RESET_URL]' => $verificationUrl,
                '[EMAIL]' => $user->email,
                '[SITE_NAME]' => $generalSetings->app_name,
                '[CUSTOMER_NAME]' => $user->name,
            ]
        ];

        $this->getCustomerTemplateData('password_reset');
        $this->token = $token;

    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $notificationSetings = new NotificationSettings();

        $message = new MailMessage;
        $message->from($notificationSetings->notification_email_from, $notificationSetings->notification_email_name);
        $message->subject($this->shortCodeReplacer($this->template->subject));
        $message->greeting($this->shortCodeReplacer($this->template->greeting));
        $message->line(new HtmlString($this->shortCodeReplacer($this->template->email_body)));
        $message->action($this->shortCodeReplacer($this->template->action_text), route($this->shortCodeReplacer($this->template->action_url)));
        $message->line($this->shortCodeReplacer($this->template->thanks));

        return $message;

    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {

        static::$toMailCallback = $callback;

    }

}
