<?php

namespace App;

use App\GoCash;
use Illuminate\Database\Eloquent\Model;

class ReferralGrant extends Model
{
	protected $fillable = ['referrer_id','receipient_id','grant'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grantReferrer($customer_id)
    {
    	$referrer_code = UserDetail::where('user_id',$customer_id)
    							 ->firstOrFail()
    							 ->referred_by;
    	if($referrer_code){
    		$referrer_id = UserDetail::where('referral_id',$referrer_code)
    								 ->firstOrFail()
    								 ->user_id;
	    	$grantAmount = 100;
	    	$check = ReferralGrant::where('referrer_id',$referrer_id)
	    						  ->where('receipient_id',$customer_id)
	    						  ->exists();

	    	if(!$check){
		    	$grantReferrer = ReferralGrant::create([
		    						'referrer_id' 	=> $referrer_id, 
		    						'receipient_id' => $customer_id,
									'grant' 		=> $grantAmount
								]);

		    	$updateCash = GoCash::firstOrNew(['user_id' => $referrer_id]);

				$updateCash->Vcash = $updateCash->Vcash + $grantAmount;
				$updateCash->save();
		    }
		}
    }
}
