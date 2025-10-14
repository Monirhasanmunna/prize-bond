<?php
namespace App\Http\Services\Feature\Admin;

use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @return array
     */
    public function getData (): array
    {
        try {
            $user = Auth::user();

            return $this->response([
                'profile' => $user,
            ])->success();
        }
        catch (\Exception $exception) {
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
            $user = Auth::user();
            if(!$user){
                return $this->response()->error('User not found');
            }

            $user->update( $this->_formatedProfileUpdatedData( $payload));

            return $this->response()->success('Profile updated successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @param array $payload
     * @return array
     */
    private function _formatedProfileUpdatedData(array $payload): array
    {
        $data = [];

        if(array_key_exists('name', $payload) && !empty($payload['name']))                $data['name']           = $payload['name'];
        if(array_key_exists('email', $payload) && !empty($payload['email']))              $data['email']          = $payload['email'];
        if(array_key_exists('password', $payload) && !empty($payload['password']))        $data['password']       = Hash::make($payload['password']);
        return $data;
    }
}
