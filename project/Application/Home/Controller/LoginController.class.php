<?php
namespace Home\Controller;
use Think\Controller;

class LoginController extends Controller
{   
    // 登录方法
    public function index()
    {
        if (IS_POST) {
            // 验证验证码是否OK
            $Verify = new \Think\Verify();
            $res = $Verify->check($_POST['code']);
            if(!$res) $this->error('验证码不对');

            $password = md5($_POST['password']);
            $info = M('vendors')->where("user='{$_POST['name']}'")->find();

            if($info){
                if ($info['password'] == $password) {
                    // 万事大吉
                    $_SESSION['vendorInfo'] = $info;
                    // 分销商主页
                    $this->redirect('vendors/index');
                }else{
                    $this->error('您的密码输入错误！');
                }
            }else{
                $this->error('您输入的用户名不存在！');
            }

        }else{
            // 如果用户已经登录
            if($_SESSION['vendorInfo']){
                // 分销商主页
                $this->redirect('vendors/index');    
            }else{
                $this->display(); 
            }
            
        }
    }

    // 验证码方法
    public function yzm()
    {
        $config = array(
            'font-Size' =>   30, //验证码大小
            'length'    =>   4,  //验证码个数
            'useNoise'  =>   false, //关闭验证码杂点
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }


    public function logout()
    {
        unset($_SESSION['vendorInfo']);
        $this->redirect('Login/index');
    }

}
