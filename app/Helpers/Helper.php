<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Log;
use App\Models\User;
use App\Models\Permission_item;
use App\Models\Permission_link;

class Helper
{
    public static function saveLog($user_id, $detail, $action, $date_action)
    {
        $log = new Log();
        $log->company_id = Auth::user()->company_id;
        $log->user_id = $user_id;
        $log->detail = json_encode($detail);
        $log->action = $action;
        $log->created_at = $date_action;
        $log->save();
    }

    public static function get_permissions() {
        $user = Auth::user();

        $user_permissions = Permission_link::select('permission_items.slug')
        ->where('permission_group_id', $user->nivel_acesso)
        ->join('permission_items', 'permission_items.id', 'permission_links.permission_item_id')
        ->get();

        $permissions = [];
        foreach ($user_permissions as $item) {
            $permissions[] = $item->slug;
        }

        return $permissions;
    }

    public static function get_hour_format($segundos) {
        $init = $segundos;
        $hours = (floor($init / 3600) < 10) ? '0'.(floor($init / 3600)) : floor($init / 3600);
        $minutes = ((floor(($init / 60) % 60) < 10) ? '0'.(floor(($init / 60) % 60)) : floor(($init / 60) % 60));
        $seconds = (($init % 60) < 10) ? '0'.($init % 60) : ($init % 60);

        return $hours.':'.$minutes.':'.$seconds;
    }
}
