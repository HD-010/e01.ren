<?php

namespace app\components\sensors;

// 在发送的数据格式有误时，SDK会抛出此异常，用户应当捕获并处理。
class SensorsAnalyticsIllegalDataException extends SensorsAnalyticsException {
}
