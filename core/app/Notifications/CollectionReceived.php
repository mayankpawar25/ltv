<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CollectionReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public $id;
    private $name;
    private $amount;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($id, $name, $amount)
    {
        //
        $this->id     = $id;
        $this->name   = $name;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return (isset($notifiable->customer_id)) ? ['mail'] : ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {          
        $message        = sprintf(__('form.collection_received'), $this->amount , $this->name );       

        $mail = (new MailMessage)->line($message);

        // if(!isset($notifiable->customer_id))
        // {
        //    $mail->action(__('form.view_receipt'), route('collection.show', $this->id )); 
        // }

        return $mail;            
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
       
    }
}