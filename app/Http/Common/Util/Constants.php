<?php
/**
 * Created by PhpStorm.
 * User: warthur
 * Date: 17/9/9
 * Time: 下午12:03
 */

namespace App\Http\Common\Util;


use MyCLabs\Enum\Enum;

final class Constants extends Enum
{
    const REQUEST_SUCCESS = "操作成功！";
    const REQUEST_ERROR = "操作失败！";
    const UNKNOW_ERROR = "未知错误";

    const OAUTH_CACHE_TOKEN = 'oauth_cache_token';
}