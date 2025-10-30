<?php
namespace App\Http\Services\Feature\Admin;

use App\Http\Services\Feature\User\SendNotificationService;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use App\Imports\WinnerImport;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Maatwebsite\Excel\Facades\Excel;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class NotificationService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    public function __construct(private readonly SendNotificationService $sendNotificationService){}

    /**
     * @param array $query
     * @return array
     */
    public function getListData (array $query): array
    {
        try {
            $validationErrorMsg = $this->queryParams($query)->required([]);
            if ($validationErrorMsg) {
                return $this->response()->error($validationErrorMsg);
            }

            if (!array_key_exists('graph', $query)) {
                $query['graph'] = '{*}';
            }

            $dbQuery = Notification::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new Notification);

            if (array_key_exists('search', $query)) {
                $dbQuery = $dbQuery->where('title', 'like', '%'.$query['search'].'%');
            }

            $count = $dbQuery->count();
            $notifications = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'notifications' => $notifications,
                'count' => $count,
                'notificationStatus' => commonStatus(),
                ...$query
            ])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     * @throws FirebaseException
     * @throws MessagingException
     */
    public function storeData (array $payload): array
    {
        try {
            DB::beginTransaction();
            $notification = Notification::create( $this->_formatedNotificationCreatedData( $payload));

            $users = User::whereNotNull('fcm_token')->get();

            foreach ($users as $user) {
                UserNotification::create([
                    'user_id'        => $user->id,
                    'notification_id'=> $notification->id,
                ]);

                $this->sendNotificationService->sendToToken(
                    $user->fcm_token,
                    $notification->title,
                    $notification->description,
                    [
                        'notification_id' => $notification->id,
                    ]
                );
            }

            DB::commit();
            return $this->response()->success('Notification created successfully');

        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function updateData (array $payload): array
    {
        try {
            $notification = Notification::where('id', $payload['id'])->first();
            if(!$notification) {
                return $this->response()->error('Notification not found');
            }

            $notification->update( $this->_formatedNotificationUpdatedData( $payload));

            return $this->response()->success('Notification updated successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param string $id
     * @return array
     */
    public function deleteData (string $id): array
    {
        try {
            $notification = Notification::where('id', $id)->first();
            if (!$notification) {
                return $this->response()->error("Notification not found");
            }

            $notification->delete();

            return $this->response()->success('Notification Deleted Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedNotificationCreatedData(array $payload): array
    {
        return [
            'title'         => $payload['title'],
            'description'   => $payload['description'],
        ];
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedNotificationUpdatedData(array $payload): array
    {
        $data = [];

        if(array_key_exists('title', $payload)) $data['title']                  = $payload['title'];
        if(array_key_exists('description', $payload)) $data['description']      = $payload['description'];

        return $data;
    }
}
