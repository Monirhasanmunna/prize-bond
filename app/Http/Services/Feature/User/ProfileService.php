<?php
namespace App\Http\Services\Feature\User;

use App\Models\PrizeBond;
use App\Models\User;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Facades\Auth;


class ProfileService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @param array $query
     * @return array
     */
    public function edit(array $query): array
    {
        try {
            $user = User::where('id', Auth::id())->where('role', ROLE_USER)->first();

            if(!$user) {
                return $this->response()->error('Unauthorized');
            }

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'nid' => $user->nid,
                'image' => $user->image,
            ];

            return $this->response(['user' => $data])->success();
        }
        catch (\Exception $e) {
            return $this->response()->error($e->getMessage());
        }
    }

    /**
     * @param array $payload
     * @return array
     */
    public function update(array $payload): array
    {
        try {
            $user = User::where('id', Auth::id())->where('role', ROLE_USER)->first();

            if(!$user){
                return $this->response()->error('Not authenticated');
            }

            $imageName = null;
            if(!empty($payload['image'])){
                $imageName = $this->upload_file($payload['image'], 'user', 'image', $user->image);
            }

            $user->update( $this->formateUpdatedData($payload, $imageName));

            return $this->response(['user' => $user->fresh()])->success('Profile updated successfully');
        }
        catch (\Exception $exception){
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @param string|null $imageName
     * @return array
     */
    private function formateUpdatedData(array $payload, string $imageName = null): array
    {
        $data = [];

        if(!empty($payload['name']))       $data['name'] = $payload['name'];
        if(!empty($payload['phone']))      $data['phone'] = $payload['phone'];
        if(!empty($payload['nid']))        $data['nid'] = $payload['nid'];
        if(!empty($imageName))             $data['image'] = $imageName;

        return $data;
    }
}
