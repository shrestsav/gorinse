<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\DeviceToken;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class AppNotification extends Notification
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->sendFCMNotification($notifiable);
        
        return [
            'notifyType' => $this->message['notifyType'],
            'message'    => $this->message['message'],
            'url'        => $this->message['url'],
            'created_at' => Date('Y-m-d h:i:s')
        ];
    }

    public function sendFCMNotification($notifiable)
    {  
        $device_tokens = DeviceToken::where('user_id',$notifiable->id)->pluck('device_token')->toArray();
        if(count($device_tokens)){
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);

            $title = implode(' ', array_map('ucfirst', explode('_', $this->message['notifyType'])));
            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($this->message['message'])
                                ->setIcon("ic_notification")
                                ->setSound('default')
                                ->setBadge('badge');

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData([
                'notifyType' => $this->message['notifyType'],
                'message'    => $this->message['message'],
                'url'        => $this->message['url'],
                'created_at' => Date('Y-m-d h:i:s')
            ]);

            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $downstreamResponse = FCM::sendTo($device_tokens, $option, $notification, $data);
            // $downstreamResponse = FCM::sendTo($device_tokens, $option, $notification, null);

            $expiredTokens = $downstreamResponse->tokensToDelete();

            if(count($expiredTokens)){
                DeviceToken::whereIn('device_token',$expiredTokens)->delete();
            } 
        }
    }
}
