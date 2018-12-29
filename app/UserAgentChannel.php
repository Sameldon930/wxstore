<?php

namespace App;

use App\Exceptions\ErrorMessageException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserAgentChannel extends Model
{
    protected $fillable = [
        'user_id', 'channel_id', 'profit_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function orders()
    {
        return $this->hasOne(Order::class);
    }

    public static function get(User $user, Channel $channel)
    {
        return self::where('user_id', $user->id)
            ->where('channel_id', $channel->id)
            ->first();
    }

    public static function updateUserChannel(User $user, Request $request)
    {
        // TODO：逻辑太乱了
        $channels = Channel::all();
        //dd($user->user_agent_channels);
        $aUserAgentChannel = $user->a_user->user_agent_channels;
        $sUser=$user->s_user()->Agent()->get();

        foreach ($channels as $k => $v) {
            $channelProfitRate = $request->get('profit_rate_' . $v->id);
            if (null === $channelProfitRate) {
                throw new ErrorMessageException('请输入每个渠道的分润费率');
            }
            $channelProfitRate = intval($channelProfitRate);
            if ($channelProfitRate < 0) {
                throw new ErrorMessageException('分润费率必须大于0');
            }
            if (!isset($aUserAgentChannel[$k]['profit_rate']) && $user->aid != 1) {
                throw new ErrorMessageException("上级代理（{$user->a_user->name}）分润费率不完整，请联系管理员");
            }

            if($user->id != User::DEFAULT_PLATFORM_ID) {
                //todo 只有对上级的判断，没有对下级的判断，需要补充
                $aUserChannelProfitRate = $aUserAgentChannel[$k]['profit_rate'];
                if ($channelProfitRate > $aUserChannelProfitRate) {
                    if ($user->id !== User::DEFAULT_PLATFORM_ID) {
                        throw new ErrorMessageException("对于渠道（{$v->display}），你对该代理设置的分润费率（{$channelProfitRate}）不能高于该代理的上级代理对应渠道的分润费率（{$aUserChannelProfitRate}）");
                    }
                }

                //针对下级代理做的判断
                foreach($sUser as $vv){
                    $sUserChannelProfitRate = $vv->user_agent_channels[$k]['profit_rate'];
                    if ($channelProfitRate < $sUserChannelProfitRate) {
                        if ($user->id !== User::DEFAULT_PLATFORM_ID) {
                            throw new ErrorMessageException("对于渠道（{$v->display}），你对该代理设置的分润费率（{$channelProfitRate}）不能低于该代理旗下代理（{$vv->name}）对应渠道的分润费率（{$sUserChannelProfitRate}）");
                        }
                    }
                }


            }
            if (!isset($user->user_agent_channels[$k])) {
                $result = UserAgentChannel::create([
                    'user_id' => $user->id,
                    'channel_id' => $v->id,
                    'profit_rate' => $channelProfitRate,
                ]);
                $user->user_agent_channels[$k] = $result;
            } else {
                $user->user_agent_channels[$k]->profit_rate = $channelProfitRate;
            }
        }

        foreach ($user->user_agent_channels as $k => $agentChannel) {
            $user->user_agent_channels[$k]->save();
        }
    }
}
