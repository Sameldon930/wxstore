<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class CustomEloquentUserProvider extends EloquentUserProvider
{
    /**
     * 重写自带验证方法 添加用户类型判断
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        // 获取后台模块 merchant 或者 agent
        $prefix = request()->route()->getAction()['prefix'];
        if (
            $user->isEnabled() &&
            (
                ($prefix === 'stores' && $user->isStore()) || ($prefix === 'merchant' && $user->isMerchant())||

                ($prefix === 'agent' && $user->isAgent())
            )
            /*$user->isEnabled() && $prefix === 'agent' &&
            (
                $user->isMerchant() || $user->isAgent()
            )*/
        ) {
            return parent::validateCredentials($user, $credentials);
        }

        return false;
    }
}