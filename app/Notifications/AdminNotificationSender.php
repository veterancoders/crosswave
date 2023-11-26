<?php


namespace App\Notifications;

use App\Helpers\NotificationTemplateParserTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNotificationSender extends Notification
{
    use Queueable;
    use NotificationTemplateParserTrait;

    public $details;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($templateName, $details = [])
    {

        $this->details = $details;
        $this->getAdminTemplateData($templateName);

    }

}
