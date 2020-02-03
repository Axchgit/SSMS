<?php

namespace app\admin\model;

use think\Model;

class User extends Model{
    public function getStrTime(){
        $no=date("H",time());
        if ($no>0&&$no<=6){
            return $mtime = "凌晨好";
        }
        if ($no>6&&$no<9){
            return $mtime = "早上好";
        }
        if ($no>=9&&$no<12){
            return $mtime = "上午好";
        }
        if ($no>=12&&$no<=18){
            return $mtime = "下午好";
        }
        if ($no>18&&$no<=24){
            return $mtime = "晚上好";
        }
        return $mtime = "您好";
    }
    
}