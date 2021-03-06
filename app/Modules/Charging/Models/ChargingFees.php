<?php

namespace App\Modules\Charging\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use App\User;
class ChargingFees extends Model
{
    protected $table = 'chargings_fees';
    protected $fillable = [
        'code','serial','telco','declared_value', 'type','user'
    ];

    public static function getValueByGroupandTelco($group_id, $telco, $field)
    {
        $val = DB::table('chargings_fees')->where('telco_key',$telco)->where('group',$group_id)->select($field)->first();
        if( $val )
        {
            return $val->$field;
        }
        return '';
    }

    public static function getFees($telco)
    {
        $fees = new ChargingFees;
        $val = $fees->where('telco_key',$telco)->where('group', Auth::user()->group )->select('fees')->first();
        if( $val )
        {
            return $val->fees;
        }
        return null;
    }

    public static function getFeesUserId($telco, $user_id)
    {
        $fees  = new ChargingFees;
        $user = User::findOrFail($user_id);
        $group = $user->group;
        $val = $fees->where('telco_key',$telco)->where('group', $group )->select('fees')->first();
        if( $val )
        {
            return $val->fees;
        }
        return null;
    }

    public static function getPenalty($telco, $user_id)
    {
        $fees  = new ChargingFees;
        $user = User::findOrFail($user_id);
        $group = $user->group;
        $val = $fees->where('telco_key',$telco)->where('group', $group )->select('penalty')->first();
        if( $val )
        {
            return $val->penalty;
        }
        return null;
    }
}
