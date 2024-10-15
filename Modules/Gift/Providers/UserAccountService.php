<?php

namespace Modules\Gift\Providers;

/**
 * 用户账户操作统一入口
 */
final class UserAccountService
{
    private static string $u_id;
    private static array $u_m = [];
    private function __construct()
    {
    }

    public static function instance(string $u_id)
    {
        self::$u_id = $u_id;
        if (isset( self::$u_m[$u_id]))
        {
            return self::$u_m[$u_id];
        }
        self::$u_m[$u_id] = new UserAccountService();
        return self::$u_m[$u_id];
    }

    public function operate(int $coin_number, string $type = 'plus', $message = ''): bool
    {
        try {
            $this->lock();
            //账户金币或资金操作
            //记录$message
        }catch (\Exception $exception){
            return false;
        } finally {
            $this->unlock();
        }
        return true;
    }

    private function plus()
    {
        //账户增加
    }

    private function reduce()
    {
        //账户扣减
    }

    private function lock()
    {
        //上锁
    }
    public function unlock()
    {
        //解锁
    }
}
