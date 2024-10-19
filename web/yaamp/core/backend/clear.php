<?php

function BackendClearEarnings($coinid = NULL)
{
//	debuglog(__FUNCTION__);

	if (YAAMP_ALLOW_EXCHANGE)
		$delay = time() - (int) YAAMP_PAYMENTS_FREQ;
	else
		$delay = time() - (YAAMP_PAYMENTS_FREQ / 2);
	$total_cleared = 0.0;

	$sqlFilter = $coinid ? " AND coinid=".intval($coinid) : '';

	$list = getdbolist('db_earnings', "status=1 AND mature_time<$delay $sqlFilter");
	foreach($list as $earning)
	{
		$coin = getdbo('db_coins', $earning->coinid);
		if(!$coin)
		{
		    $earning->delete();
		    continue;
		}

		if ($coin->symbol === 'DOGM') {  
			$user = getdbo('db_accountsdogm', $earning->userid);  
		} elseif ($coin->symbol === 'DOGE') {  
			$user = getdbo('db_accountsdoge', $earning->userid);  
		} else {  
			$user = getdbo('db_accounts', $earning->userid);  
		}  

		if(!$user)
		{
			$earning->delete();
			continue;
		}
			
		$earning->status = 2;		// cleared
		$earning->price = $coin->price;
		$earning->save();

		$value = yaamp_convert_amount_user($coin, $earning->amount, $user);

		if($user->coinid == 6 && !YAAMP_ALLOW_EXCHANGE)
			continue;

		$user->balance += $value;
		$user->save();

		if($user->coinid == 6)
			$total_cleared += $value;
	}

	if($total_cleared>0)
	 	debuglog("total cleared from mining $total_cleared BTC");
}

