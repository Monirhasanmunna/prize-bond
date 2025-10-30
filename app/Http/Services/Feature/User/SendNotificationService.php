<?php
namespace App\Http\Services\Feature\User;

use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendNotificationService
{
    use Request,Response, QueryAssistTrait;

    protected $messaging;

    /**
     *
     */
    public function __construct()
    {
        $this->messaging = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/service-account.json'))
            ->createMessaging();
    }


    /**
     * @param $token
     * @param $title
     * @param $body
     * @param array $data
     * @return mixed[]
     * @throws FirebaseException
     * @throws MessagingException
     */
    public function sendToToken($token, $title, $body, array $data = []): array
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        return $this->messaging->send($message);
    }
}
