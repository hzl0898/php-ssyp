<?php

namespace Admin\Controller; //当前命名空间跟文件目录一样
use Think\Controller;

class AdminController extends Controller
{
	//进入后台必须进行管理员登录
	public function load()
	{
		if (IS_POST)  //如果有表单提交
		{
			//获取视图的post参数
			$admin_info = I('post.');
			//实例化管理员对象
			$model = D('admin');
			//进行登录信息检查
			if ($model->checkValidation($admin_info['validation']) === false) {
				$this->error("验证码错误，请重试！！^-^", U("?m=admin&c=admin&a=load"));
				die;
			}
			//判断管理员名称是否存在
			if ($adminInfo = $model->where(array('aname' => $admin_info['aname']))->find()) {
				//验证密码是否正确
				$apwd = md5($adminInfo['salt'] . md5($admin_info['apwd']));
				if ($apwd == $adminInfo['apwd']) {
					session("admin_name", $admin_info['aname']); //保存管理员登录信息
					$this->success("欢迎管理员登录！！^-^", U("?m=admin&c=index&a=index"));
					die;
				}
				$this->error("密码验证失败，请好好想想哦！！^-^", U("?m=admin&c=admin&a=load"));
				die;
			}
			$this->error("管理员不存在！！请联系数据库管理人员处理", U("?m=admin&c=admin&a=load"));
		}
		$address = COMMON_PATH . 'Common';
		$this->assign('address', $address); //传递验证码所在目录地址
		//管理员登录界面
		$this->display();
	}

	//退出
	public function logout()
	{
		session(null);
		$this->success("退出成功。", U('?m=Admin&c=admin&a=load'));
	}
}
