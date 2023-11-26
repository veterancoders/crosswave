<?php


namespace App\Models;

use App\Admin\Controllers\NotificationTemplatesController;
use App\Channels\TwilioChannel;
use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Localizable;
use NotificationChannels\WebPush\WebPushChannel;
use Spatie\Translatable\HasTranslations;

class NotificationTemplate extends Model
{
  
    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */

    protected $table = 'notification_templates';

    protected $fillable = [
        'id',
        'name',
        'subject',
        'help',
        'greeting',
        'thanks',
        'sms_body',
        'slack_body',
        'email_body',
        'action_url',
        'action_text',
        'database_body',
        'enabled_channels',
        'status',
        'active',
        'sms_recipient',
        'default_sms_gateway',
        'webpush_body'
    ];

    protected $casts = [
        'enabled_channels' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
     |--------------------------------------------------------------------------
     | SCOPES
     |--------------------------------------------------------------------------
     */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getSlackChannelAttribute($value)
    {

        if (is_null($value)) {
            return '#general';
        }

        return $value;
    }

    /*public function setEmailBodyAttribute($extra)
    {
        $this->attributes['email_body'] = json_encode(array_values($extra));
    }*/

    public function getChannelsAttribute()
    {

        $selected_channels = [];

        foreach ($this->enabled_channels as $channel) {
            switch ($channel) {
                case 'mail':
                    array_push($selected_channels, 'mail');
                    break;
                case 'webpush':
                    array_push($selected_channels, WebPushChannel::class);
                    break;
                case 'sms':
                    array_push($selected_channels, $this->getDefaultSMSChannel());
                    break;
                case 'database':
                    array_push($selected_channels, 'database');
                    break;
            }
        }

        return $selected_channels;
    }

    public function getDefaultSMSChannel()
    {

        $selected_channels = '';

        switch ($this->default_sms_gateway) {
            case 'twillo':
                $selected_channels = TwilioChannel::class;
                break;
            case 'nexmo':
                $selected_channels = 'nexmo';
                break;
        }

        return $selected_channels;
    }
}
