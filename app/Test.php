<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $primaryKey="test_id";

    // 关联到模型的数据表
    protected $table = 'test';

    // 可以被批量赋值的属性
    protected $guarded = [];//黑名单

    // 表明模型是否应该被打上时间戳
    public $timestamps =false;
}
