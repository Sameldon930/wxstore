<?php

namespace App;

use App\Exceptions\ErrorMessageException;
use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Searchable, Notifiable, SoftDeletes;

    const LEVEL_MERCHANT = 1;
    const LEVEL_AGENT = 2;
    const LEVEL_STORE = 4;
    const LEVEL_CHECKED_MERCHANT = 16;
    const LEVEL_CHECKED_AGENT = 32;

    const LEVEL_MERCHANTS = [
        self::LEVEL_MERCHANT => '商户未审核',
        self::LEVEL_CHECKED_MERCHANT => '商户已审核',
    ];
    const LEVEL_AGENTS = [
        self::LEVEL_AGENT => '代理未审核',
        self::LEVEL_CHECKED_AGENT => '代理已审核',
    ];
    const LEVEL_STORES = [
        self::LEVEL_STORE => '门店',
    ];

    const LEVEL_MAP = self::LEVEL_MERCHANTS + self::LEVEL_AGENTS + self::LEVEL_STORES;


    const DEFAULT_PLATFORM_ID = 1; // 默认平台ID

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    const STATUS = [
        self::STATUS_ENABLED => '正常',
        self::STATUS_DISABLED => '已冻结',
    ];

    public static function getFlatform()
    {
        return self::find(self::DEFAULT_PLATFORM_ID);
    }

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new StatusScope());
    }


    protected $fillable = ['name', 'mobile', 'real_mobile', 'password', 'level', 'aid', 'aids', 'status', 'avatar','wx_openid','wx_nickname','wx_headimgurl'];

    protected $hidden = ['password', 'remember_token',];


    public function scopeMerchant($query)
    {
        return $query->where('level', '&', self::LEVEL_MERCHANT);
    }
        //select 34&32 如果输出32 检查代理属性
    public function scopeAgent($query)
    {
        return $query->where('level', '&', self::LEVEL_AGENT);
    }

    public function scopeStore($query)
    {
        return $query->where('level', '&', self::LEVEL_STORE);
    }

    /*public function scopeMerchantOrStore($query){
        return $query->where(function ($subQuery){
            $subQuery->orWhere('level', '&', self::LEVEL_MERCHANT);
            $subQuery->orWhere('level', '&', self::LEVEL_STORE);
        });
    }*/
    public function scopeCheckedMerchant($query)
    {
        return $query->where('level', '&', self::LEVEL_CHECKED_MERCHANT);
    }

    public function scopeCheckedAgent($query)
    {
        return $query->where('level', '&', self::LEVEL_CHECKED_AGENT);
    }


    public function scopePlatform($query)
    {
        return $query->where('id', self::DEFAULT_PLATFORM_ID);
    }


    public function scopeEnabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }

    public function scopeDisabled($query)
    {
        return $query->where('status', self::STATUS_DISABLED);
    }


    public function isMerchant()
    {
        return ($this->level & self::LEVEL_MERCHANT) ? true : false;
    }

    public function isAgent()
    {
        return ($this->level & self::LEVEL_AGENT) ? true : false;
    }

    public function isStore()
    {
        return ($this->level & self::LEVEL_STORE) ? true : false;
    }

    public function isCheckedMerchant()
    {
        return ($this->level & self::LEVEL_CHECKED_MERCHANT) ? true : false;
    }

    public function isCheckedAgent()
    {
        return ($this->level & self::LEVEL_CHECKED_AGENT) ? true : false;
    }

    public function isUncheckedMerchant()
    {
        return !$this->isCheckedMerchant();
    }

    public function isUncheckedAgent()
    {
        return !$this->isCheckedAgent();
    }

    public function isPlatform()
    {
        return $this->id === self::DEFAULT_PLATFORM_ID;
    }


    public function isEnabled()
    {
        return $this->status === self::STATUS_ENABLED;
    }

    public function isDisabled()
    {
        return $this->status === self::STATUS_DISABLED;
    }

    public function a_user()
    {
        return $this->hasOne(User::class, 'id', 'aid');
    }

    public function s_user()
    {
        return $this->hasMany(User::class, 'aid', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function account_logs()
    {
        return $this->hasMany(AccountLog::class);
    }

    public function action_logs()
    {
        return $this->hasMany(ActionLog::class);
    }

    public function settle_logs()
    {
        return $this->hasMany(SettleLog::class);
    }

    public function user_merchant_info()
    {
        return $this->hasOne(UserMerchantInfo::class);
    }

    public function user_agent_info()
    {
        return $this->hasOne(UserAgentInfo::class);
    }

    public function user_accounts()
    {
        return $this->hasMany(UserAccount::class);
    }

    public function user_agent_channels()
    {
        return $this->hasMany(UserAgentChannel::class);
    }

    public function user_merchant_tubes()
    {
        return $this->hasMany(UserMerchantTube::class);
    }
    public function merchantInfo()
    {
        return $this->hasOne(MerchantInfo::class);
    }
    //如果是门店，获取其上游的商户
    public function getPayableUserAttribute($value)
    {
        return $this->isStore() ? $this->a_user : $this;
    }

    public static function getAgentAuthUser()
    {
        return Auth::guard('agent')->user();
    }

    public static function getMerchantAuthUser()
    {
        return Auth::guard('merchant')->user();
    }

    public static function getStoreAuthUser()
    {
        return Auth::guard('stores')->user();
    }


    public static function getAgentUserByMobile($mobile = null)
    {

        if ($mobile) {
            $user = User::where('mobile', $mobile)->agent()->first();
        } else {
            $user = self::getFlatform();
        }

        if (!$user) {
            throw new ErrorMessageException('上级代理商户号不存在或者不是代理');
        }

        return $user;
    }

    public static function getMerchantUserByMobile($mobile)
    {
        return self::where('mobile', $mobile)
            ->with('a_user')
            ->merchant()
            ->enabled()
            ->firstOrFail();
    }

    public static function getPayableMerchantUserByMobile($mobile)
    {
        return self::where('mobile', $mobile)
            ->with('a_user')
            ->with('user_merchant_info')
            //->has('user_merchant_info')
            //->checkedMerchant()
            ->enabled()
            ->firstOrFail();
    }

    public static function getAidsByAUser(self $aUser)
    {
        if ($aUser->isPlatform()) {
            return $aUser->id;
        } else {
            return $aUser->aids . ',' . $aUser->id;
        }
    }

    public function getAccountByTubeId($tube_id)
    {
        $UserAccount = UserAccount::where('user_id', $this->id)->where('tube_id', $tube_id)->first();
        if (empty($UserAccount)) {
            $UserAccount = UserAccount::create([
                'user_id' => $this->id,
                'tube_id' => $tube_id,
                'balance' => 0,
                'change' => 0,
            ]);
        }
        return $UserAccount;
    }

    public static function getNewInsertMobile()
    {
        return intval(self::withTrashed()->latest('mobile')->first()->mobile) + 1;
    }

    public static function createMerchant($name, $password, self $aUser, $real_mobile)
    {
        self::checkAUserIsAValidAgent($aUser);

        $user = self::create([
            'password' => bcrypt($password),
            'mobile' => self::getNewInsertMobile(),
            'real_mobile' => $real_mobile,
            'name' => $name,
            'aid' => $aUser->id,
            'aids' => self::getAidsByAUser($aUser),
            'level' => self::LEVEL_MERCHANT,
        ]);

        //TODO 修改创建商户-通道关系
        foreach (Tube::all() as $tube) {
            /* UserAccount::create([
                 'user_id' => $user->id,
                 'tube_id' => $tube->id,
                 'balance' => 0,
                 'change' => 0,
             ]);*/

            UserMerchantTube::create([
                'user_id' => $user->id,
                'tube_id' => $tube->id,
                'profit_rate' => 0,
                'tube_rate' => 0,
            ]);
        }

        self::becomeMerchant($user);

        return $user;
    }

    public static function createAgent($name, $password, self $aUser)
    {
        self::checkAUserIsAValidAgent($aUser);

        $user = self::create([
            'password' => bcrypt($password),
            'mobile' => self::getNewInsertMobile(),
            'level' => self::LEVEL_AGENT,
            'name' => $name,
            'aid' => $aUser->id,
            'aids' => self::getAidsByAUser($aUser),
        ]);

        foreach (Tube::all() as $tube) {
            UserAccount::create([
                'user_id' => $user->id,
                'tube_id' => $tube->id,
                'balance' => 0,
                'change' => 0,
            ]);
        }

        self::becomeAgent($user);

        return $user;
    }

    public static function createStore($name, $password, self $aUser)
    {
        self::checkAUserIsAValidMerchant($aUser);

        $user = self::create([
            'password' => bcrypt($password),
            'mobile' => self::getNewInsertMobile(),
            'name' => $name,
            'aid' => $aUser->id,
            'aids' => self::getAidsByAUser($aUser),
            'level' => self::LEVEL_STORE,
        ]);
        //TODO 修改创建商户-通道关系
        foreach (Tube::all() as $tube) {
            /*UserAccount::create([
                'user_id' => $user->id,
                'tube_id' => $tube->id,
                'balance' => 0,
                'change' => 0,
            ]);*/

            UserMerchantTube::create([
                'user_id' => $user->id,
                'tube_id' => $tube->id,
                'profit_rate' => 0,
                'tube_rate' => 0,
            ]);
        }

        self::becomeStore($user);

        return $user;
    }

    public static function becomeMerchant(self $user, self $aUser = null)
    {
        if ($aUser) {
            self::checkAUserIsAValidAgent($aUser);
        }

        if (!$user->isMerchant()) {
            $user->level = $user->level | self::LEVEL_MERCHANT;
            $user->save();
        }
    }

    public static function becomeStore(self $user, self $aUser = null)
    {
        if ($aUser) {
            self::checkAUserIsAValidMerchant($aUser);
        }

        if (!$user->isStore()) {
            $user->level = $user->level | self::LEVEL_STORE;
            $user->save();
        }
    }

    public static function becomeAgent(self $user, self $aUser = null)
    {
        if ($aUser) {
            self::checkAUserIsAValidAgent($aUser);
        }

        if (!$user->isAgent()) {
            $user->level = $user->level | self::LEVEL_AGENT;
            $user->save();
        }
        //todo 拓展性不行，之前的数据都需要增加额外的通道
        foreach (Channel::all() as $channel) {
            UserAgentChannel::create([
                'user_id' => $user->id,
                'channel_id' => $channel->id,
                'profit_rate' => 0,
            ]);
        }
    }

    public static function becomeCheckedMerchant(self $user, $config)
    {
        if (!$user->isMerchant()) {
            $user->becomeMerchant($user, $user->a_user);
        }
        $user->level = $user->level | self::LEVEL_MERCHANT | self::LEVEL_CHECKED_MERCHANT;
        $user->save();
        //进件完成填完所有商户支付宝账号后进行
        $userMerchantInfo = UserMerchantInfo::where('user_id','=',$user->id)->first();
        if (empty($userMerchantInfo)) {
            UserMerchantInfo::create([
                'user_id' => $user->id,
                'wechat_merchant_no' => $config['wechat_merchant_no'],
                'ali_merchant_no' => $config['ali_merchant_no'],
                'ali_auth_token' => $config['ali_auth_token'],
            ]);
        } else {
            $userMerchantInfo->wechat_merchant_no = $config['wechat_merchant_no'];
            $userMerchantInfo->ali_merchant_no = $config['ali_merchant_no'];
            $userMerchantInfo->ali_auth_token = $config['ali_auth_token'];
            $userMerchantInfo->status = MerchantInfo::SUCCESS_AUDIT;
            $userMerchantInfo->save();
        }
    }

    public static function becomeCheckedAgent(self $user)
    {
        if (!$user->isAgent()) {
            $user->becomeAgent($user, $user->a_user);
        }

        if (!$user->user_agent_info) {
            throw new ErrorMessageException('该商户还没有上传代理审核资料');
        }

        $user->user_agent_info->status = UserAgentInfo::CHECKED;
        $user->user_agent_info->save();
        $user->level = $user->level | self::LEVEL_AGENT | self::LEVEL_CHECKED_AGENT;
        $user->save();
    }


    public static function checkAUserIsAValidAgent(self $aUser)
    {
        if ($aUser->isUncheckedAgent()) {
            throw new ErrorMessageException('上级代理未通过代理审核');
        }
        if ($aUser->isDisabled()) {
            throw new ErrorMessageException('上级代理已被禁用');
        }
    }

    public static function checkAUserIsAValidMerchant(self $aUser)
    {
        if ($aUser->isUncheckedMerchant()) {
            throw new ErrorMessageException('上级商户未通过商户审核');
        }
        if ($aUser->isDisabled()) {
            throw new ErrorMessageException('上级商户已被禁用');
        }
    }

//  LEVEL_MERCHANT 商户未审核
//  LEVEL_CHECKED_MERCHANT 商户已审核
    public final function custom_level($search_item, $search_params)
    {
        $level = intval($this->request->get($search_item));
        if ($level === self::LEVEL_MERCHANT) {
            $this->builder->where($search_item, self::LEVEL_MERCHANT);
        } elseif ($level === self::LEVEL_CHECKED_MERCHANT) {
            $this->builder->where($search_item, "&", self::LEVEL_CHECKED_MERCHANT);

        } elseif ($level === self::LEVEL_AGENT) {
            $this->builder->where($search_item , self::LEVEL_AGENT);
        } elseif ($level === self::LEVEL_CHECKED_AGENT) {
            $this->builder->where($search_item, "&", self::LEVEL_CHECKED_AGENT);
        } elseif ($level === self::LEVEL_STORE) {
            $this->builder->where($search_item, "&", self::LEVEL_STORE);
        } else {
            throw new ErrorMessageException('该状态为定义');
        }
//        dump($search_item);
//        dump($search_params);
//        dump($this->request->get($search_item));
//        dd(1);
        $this->builder->where($search_item, "&", $this->request->get($search_item));
    }
}
