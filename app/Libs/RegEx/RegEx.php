<?php
namespace App\Lib\RegEx;

/**
 * 公用正则表达式
 * @author chenxinyue
 */

class RegEx
{
    const MOBILE = '/^1[23456789]\d{9}$/'; // 手机号
    const EMAIL = '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/'; // 邮箱
    const IDENTITY = '/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X|x)$/'; // 身份证
    const ZH = '/^[\x{4e00}-\x{9fa5}]+$/u'; // 中文
    const POSTCODE = '/[1-9]\d{5}(?!\d)/'; // 邮政编码
    const IP = '/((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)/'; // IP地址
    const DEPOSIT = '/^(\d{10,19})$/'; // 储蓄卡
    const CREDIT = '/^(\d{10,19})$/'; // 信用卡
}