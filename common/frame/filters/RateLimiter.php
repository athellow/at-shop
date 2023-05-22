<?php
/**
 * 限流
 * @author Athellow
 * @version 1.0
 */
namespace common\frame\filters;

use Yii;

class RateLimiter extends \yii\filters\RateLimiter
{
    /**
     * @var bool whether to include rate limit headers in the response
     */
    public $enableRateLimitHeaders = false;

    /**
     * 获取唯一标识key
     * @param \yii\base\Action $action the action to be executed
     * @return string
     */
    public static function getIdentityKey($action)
    {
        return sprintf('api::ratelimit::%s::%s', $action->getUniqueId(), Yii::$app->user->id);
    }

    /**
     * 返回允许的请求的最大数目及时间
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action  the action to be executed
     * @return array an array of two elements. The first element is the maximum number of allowed requests,
     * and the second element is the size of the window in seconds.
     */
    public static function getRateLimit($request, $action)
    {
        return $action->rateLimit ?? [
            Yii::$app->params['api.ratelimit.limit'] ?? 3, 
            Yii::$app->params['api.ratelimit.window'] ?? 1
        ];
    }

    /**
     * 返回剩余的允许的请求和最后一次速率限制检查时 相应的 UNIX 时间戳数
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action  the action to be executed
     * @return array an array of two elements. The first element is the number of allowed requests,
     * and the second element is the corresponding UNIX timestamp.
     */
    public static function loadAllowance($request, $action)
    {
        $limitInfo = Yii::$app->cache->get(self::getIdentityKey($action));
        if (empty($limitInfo)) {
            return [self::getRateLimit($request, $action)[0], time()];
        }

        return $limitInfo;
    }
    
    /**
     * 保存剩余的允许请求数和当前的 UNIX 时间戳
     * @param \yii\web\Request $request   the current request
     * @param \yii\base\Action $action    the action to be executed
     * @param int              $allowance the number of allowed requests remaining.
     * @param int              $timestamp the current timestamp.
     */
    public static function saveAllowance($request, $action, $allowance, $timestamp)
    {
        Yii::$app->cache->set(self::getIdentityKey($action), [$allowance, $timestamp], 60 * 5);
    }

}