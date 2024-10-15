<?php

namespace Modules\Gift\Providers;

use Modules\Gift\Providers\Queue\BroadcastQueueService;
use Modules\Gift\Providers\Queue\StatisticsQueueService;
use Modules\Gift\Providers\Queue\UpdateGiftWallQueueService;

/**
 * 礼物赠送实现功能类
 * present方法为主要实现功能
 * 应用异步队列方案，执行礼物墙更新、更新房间排名、房间消费统计、收益分配、通知机制、飘屏机制等需求的实现
 * 前端可以用轮循或socket等方案实现广播通知更新
 * 这样就可以大大减轻单点需求的功能复杂度
 */
class GiftService
{
    /**
     * 赠送礼物实现方法
     * @param string $sent_u_id 赠送用户id
     * @param string $to_u_ids 接收用户id，多人以逗号隔开
     * @param int $number_group 礼物数量，这里暂定为所有接收用户的数量为一致的
     * @param mixed $room_id 房间id
     * @return bool
     * @throws \Exception
     */
    public function present(string $sent_u_id, string $to_u_ids, int $number_group, mixed $room_id): bool
    {
        $to_u_ids_arr = explode(',', $to_u_ids);

        if (in_array($sent_u_id, $to_u_ids_arr)){
            throw new \Exception("不能送礼物给自己");
        }
        if (!$this->checkValid($sent_u_id, $number_group))
        {
            throw new \Exception("送礼的条件不合法");
        }
        $coinNumber = $this->giftToCoinNumber($number_group);
        //扣减用户账户金币
        if (!UserAccountService::instance($sent_u_id)
            ->operate($coinNumber, 'reduce','送礼扣减'))
        {
            throw new \Exception("账户扣减失败");
        }
        $n = $this->prize($sent_u_id);
        //推入队列，异步操作
        UpdateGiftWallQueueService::push([
            'u_ids' => $to_u_ids_arr,
            'number_group' => $number_group,
            'room_id' => $room_id,
            'coinNumber' => $coinNumber,
            'prize' => $n
        ]);

        //读取系统设定N倍增幅数值
        $sys_n = 2;
        if ($n>=$sys_n){
            //应用队列，推送广播，与前端结合实现飘屏机制
            $this->broadcast('floatingScreen', [
                'n' => $n,
                'u_id' => $sent_u_id
            ]);
        }
        //用户排名
        $this->updateUserRank($sent_u_id, $number_group);
        //更新用户等级、魅力值
        $this->updateUserInfo($sent_u_id, ['level' => 1, 'charm' => 1]);
        return true;
    }

    /**
     * 检查送礼的条件是否符合规则,如金币是否充足
     * @param string $sent_u_id
     * @param int $number_group
     * @return bool
     */
    protected function checkValid(string $sent_u_id, int $number_group):bool
    {
        //读取用户账户，与$number_group的需求比较
        return true;
    }

    /**
     * 礼物转换金币数模拟
     * @param $number_group
     * @return int
     */
    protected function giftToCoinNumber($number_group):int
    {
        return 1;
    }
    /**
     *
     * @param string $u_id
     * @return void
     */
    public function updateGiftWall(string $u_id): void
    {
        //模拟队列轮循
        while ($data = UpdateGiftWallQueueService::pop())
        {
            foreach ($data['u_ids'] as $u_id) {
                $this->profitDistribute($data['room_id'], $u_id, $data['number_group']);
                //赠送成功广播通知
                $this->broadcast('presentSuccess', [
                    'u_id' => $u_id,
                    'number_group' => $data['number_group']
                ]);
            }
            StatisticsQueueService::push($data);
        }
    }

    /**
     * 福利概率
     * 返回增幅收益的倍数
     * @param string $u_id
     * @return int
     */
    public function prize(string $u_id):int
    {
        return 1;
    }

    /**
     * 队列异步操作房间排名更新
     * @return void
     */
    public function updateRoomRank(): void
    {
        while ($data = StatisticsQueueService::pop())
        {

            $room_id = $data['room_id'];
            $this->roomConsumptionStatistics($room_id, $data['coinNumber']);
            //更新房间操作
        }
    }

    /**
     * 用户排名更新
     * @param string $u_id
     * @param int $number_group
     * @return bool
     */
    public function updateUserRank(string $u_id, int $number_group): bool
    {
        //更新用户的送礼数据 $number_group 魅力值
        //$number_group
        return $this->updateUserInfo($u_id, ['level'=>1]);
    }

    /**
     * 更新房间消费统计
     * @param mixed $room_id
     * @param int $coinNumber
     * @return void
     */
    public function roomConsumptionStatistics(mixed $room_id, int $coinNumber)
    {

    }

    /**
     * 收益分配
     * @param mixed $room_id
     * @param string $u_id
     * @param int $coinNumber
     * @param int $prize
     * @return void
     */
    public function profitDistribute(mixed $room_id, string $u_id, int $coinNumber, int $prize): void
    {
        if ($prize>1){
            //福利概率 N倍增幅收益 后的一些操作
        }
    }
    /**
     * 用户信息更新操作 用户等级 魅力值
     * @param string $u_id
     * @param array $info
     * @return bool
     */
    public function updateUserInfo(string $u_id, array $info): bool
    {
        //更新用户信息 如 用户等级 魅力值
        return true;
    }

    /**
     * 广播队列
     * @param string $broadcast_name
     * @param array $params
     * @return void
     */
    public function broadcast(string $broadcast_name, array $params): void
    {
        BroadcastQueueService::push([
            'type' => $broadcast_name,
            'params' => $params
        ]);
    }


}

