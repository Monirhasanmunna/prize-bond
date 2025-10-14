<?php
namespace App\Http\Services\Feature\Admin;

use App\Models\AppSetting;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class AppSettingService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @param array $query
     * @return array
     */
    public function getListData (array $query): array
    {
        try {
            if (!array_key_exists('graph', $query)) {
                $query['graph'] = '{*}';
            }

            $dbQuery = AppSetting::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['key', 'status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new AppSetting);
            $appSetting = $dbQuery->get();

            return $this->response([
                'appSetting' => $appSetting,
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
     */
    public function updateAppSetting (array $payload): array
    {
        try {
            if(array_key_exists('logo', $payload) && !empty($payload['logo'])) {
                if(array_key_exists('file', $payload['logo'])){
                    $appSetting = AppSetting::where('key', 'logo')->first();
                    $oldImage = null;
                    if(!empty($appSetting)){
                        $oldImage = json_decode($appSetting->value, true);
                        $oldImage =  $oldImage ? $oldImage['path'] : null;
                    }
                    $logo = $this->upload_file( $payload['logo']['file'], 'app-setting', 'logo', $oldImage);

                    $appSetting ? $appSetting->update(['value' => json_encode(['path' => $logo])])
                        : AppSetting::create(['key' => 'logo', 'value' => json_encode(['path' => $logo])]);
                }
            }

            if(array_key_exists('favicon', $payload) && !empty($payload['favicon'])){
                if(array_key_exists('file', $payload['favicon'])){
                    $appSetting = AppSetting::where('key', 'favicon')->first();
                    $oldImage = null;
                    if(!empty($appSetting)){
                        $oldImage = json_decode($appSetting->value, true);
                        $oldImage =  $oldImage ? $oldImage['path'] : null;
                    }
                    $favicon = $this->upload_file( $payload['favicon']['file'], 'app-setting', 'favicon', $oldImage);

                    $appSetting ? $appSetting->update(['value' => json_encode(['path' => $favicon])])
                        : AppSetting::create(['key' => 'favicon', 'value' => json_encode(['path' => $favicon])]);
                }
            }

            $others = [
                "app_name",
                "contact_info",
                "social_links",
                "copyright"
            ];
            foreach ($others as $key){
                if(array_key_exists($key, $payload)){
                    $appSetting = AppSetting::where('key', $key)->first();
                    $appSetting ?
                        $appSetting->update(['value' => json_encode($payload[$key])])
                        : AppSetting::create(['key' => $key, 'value' => json_encode($payload[$key])]);
                }
            }

            return $this->response()->success('App Setting Updated Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

}

