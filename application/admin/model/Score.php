<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class Score extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    // public function scopeSemester($query, $semester)
    // {
    //     $query->where('semester',$semester);
    // }
    public function scopeExcellent($query,$semester)
    {
        $query->where($semester, '>=', 90);
    }
    // public function scopeFailed($query,$semester)
    // {
    //     $query->where($semester, '<', 60);
    // }
}
