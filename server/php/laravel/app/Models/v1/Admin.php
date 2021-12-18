<?php

namespace App\Models\v1;

use DateTimeInterface;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @property string password
 * @property string name
 * @property string email
 * @property string portrait
 * @property string real_name
 * @property int state
 * @property int cellphone
 * @property int user_id
 */
class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;
    const ADMIN_STATA_NORMAL = 1; //正常
    const ADMIN_STATA_FORBID = 2; //禁止

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // 角色
    protected function role($db)
    {
        foreach ($db as $id => $data) {
            if ($data->authGroup->count() > 0) {
                foreach ($data->authGroup as $auth_group) {
                    $data->group = $auth_group['introduction'] . ' ';
                }
            } else {
                $data->group = __('requests.auth_group.undistributed');
            }
        }
    }


    /**
     * 通过用户名找到对应的用户信息
     *
     * @param string $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('name', $username)->first();
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function AuthGroup()
    {
        return $this->belongsToMany(AuthGroup::class);
    }
}
