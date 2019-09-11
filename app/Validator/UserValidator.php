<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/4
 * Time: 16:59
 */
declare(strict_types=1);
namespace App\Validator;


use Swoft\Validator\Annotation\Mapping\ChsAlpha;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class UserValidator
 * @Validator(name="UserValidator")
 * @package App\Validator
 */
class UserValidator
{
    /**
     * @IsString(name="username",message="用户名不能为空")
     * @Length(min=3,max=20,message="用户名为3~20个字符")
     * @var string 用户名
     */
    public $username;

    /**
     * @IsString(name="user_pwd",message="密码不能为空")
     * @var string 密码
     */
    public $userPwd;

    /**
     * @IsString(name="nickname")
     * @ChsAlpha(message="昵称只能由字母或汉字组成")
     * @Length(min=3,max=20,message="昵称只能为3~20个字")
     * @var string 昵称
     */
    public $nickname = "";


}