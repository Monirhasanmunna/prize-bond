<?php
namespace App\Http\Services\Feature\User;

use App\Models\BondSeries;
use App\Models\User;
use App\Models\UserNotification;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    use Request,Response, QueryAssistTrait;

    /**
     * @param array $query
     * @return array
     */
    public function getListData (array $query): array
    {
        try {
            $user = User::where('id', Auth::id())->firstOrFail();
            if(!$user){
                return $this->response()->error('User not found');
            }

            $notifications = $user->notifications()->orderBy('created_at', 'desc')->get()->select('id', 'title', 'description', 'created_at');

            return $this->response([
                'notifications' => $notifications,
            ])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }
}
