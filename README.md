# 礼物系统
该项目是一部分功能的伪代码 不保证是否可运行

入口文件为

Modules/Gift/Routes/api.php

## 1. 用户礼物赠送功能

### 1.1 赠送行为
- **A用户**可以在直播间给所有房间用户赠送礼物，除了**A用户**自己。
- **送礼人**记为**A**。
- **收礼人**记为**B**。

### 1.2 赠送礼物处理流程
1. **前置验证**：检查送礼的条件是否符合规则。
2. **扣除金币**：从**A用户**的账户扣除相应的金币，并记录礼物的详细信息。
3. **礼物墙更新**：在**B用户**的礼物墙上记录收到的礼物。
4. **福利概率**：**A用户**赠送的礼物有一定概率触发**N倍增幅收益**，将增幅后的礼物收益分配给**B用户**。
5. **更新房间排名**：礼物赠送会更新房间的流水排名，用于首页推荐。
6. **用户排名更新**：更新**A用户**在房间的消费情况，用于房间内用户排名。
7. **房间消费统计**：更新房间的总消费币数和金额，用于与官方的对账。
8. **收益分配**：记录礼物收益的分配，包括房主、主持、以及礼物接收者的分成。
9. **魅力值增加**：赠送礼物的**A用户**在房间内的魅力值会增加。
10. **用户等级增加**：**A用户**的等级会因为送礼行为而提升。
11. **通知机制**：发送通知给房间内，告知其已成功赠送礼物给**B用户**。
12. **飘屏机制**：当**N倍增幅数值**达到较高倍数时，系统会进行全服飘屏展示。

## 2. 项目进展

### 2.1 项目一期
- **A用户**赠送的礼物数量仅为1个（`number_group = 1`）。

### 2.2 项目二期
- **A用户**赠送的礼物数量可以大于等于1个（`number_group >= 1`）。

### 2.3 项目三期
- **A用户**可以同时赠送多个礼物给多个用户（`number_group >= 1`，且`to_uid = B,C,D`）。

## 3. 项目瓶颈

### 3.1 场景描述
当在一个房间内有7个用户互相赠送礼物时，系统可能会崩溃和卡顿。具体场景如下：
- **用户A**：`number_group = 10`，`to_uid = B, C, D, E, F, G`。
- **用户B**：`number_group = 10`，`to_uid = A, C, D, E, F, G`。
- **用户C**：`number_group = 10`，`to_uid = A, B, D, E, F, G`。
- **用户D**：`number_group = 10`，`to_uid = A, B, C, E, F, G`。
- **用户E**：`number_group = 10`，`to_uid = A, B, C, D, F, G`。
- **用户F**：`number_group = 10`，`to_uid = A, B, C, D, E, G`。
- **用户G**：`number_group = 10`，`to_uid = A, B, C, D, E, F`。

这种高频率、多用户、多目标的礼物互赠场景可能会导致系统资源紧张，从而出现崩溃和卡顿问题。

## 要求
因为没有读场景,所以暂时不考虑数据库索引
##### - 请描述出现崩溃和卡顿的原因
##### - 请描述解决该崩溃和卡顿的方案
##### - 请git clone该项目 实现解决方案的伪代码
