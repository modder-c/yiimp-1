<?php  
  
function BackendClearEarnings($coinid = NULL)  
{  
    $delay = time() - (int) YAAMP_CLEARS_DELAY;  
    $total_cleared = 0.0;  
    $processedEarnings = []; // Used to track processed combinations of blockid and userid  
  
    $sqlFilter = $coinid ? " AND coinid=" . intval($coinid) : '';  
  
    // Retrieve all eligible earnings records  
    $list = getdbolist('db_earnings', "status=1 AND mature_time<$delay $sqlFilter");  
  
    foreach ($list as $earning) {  
        // Check if this combination of blockid and userid has already been processed  
        if (isset($processedEarnings[$earning->blockid]) && in_array($earning->userid, $processedEarnings[$earning->blockid])) {  
            continue; // Skip this record if already processed  
        }  
  
        // Add this blockid and userid to the processed list  
        $processedEarnings[$earning->blockid][] = $earning->userid;  
  
        $coin = getdbo('db_coins', $earning->coinid);  
        if (!$coin) {  
            $earning->delete();  
            continue;  
        }  
  
        // Retrieve user information based on currency symbol  
        if ($coin->symbol === 'DOGM') {  
            $user = getdbo('db_accountsdogm', $earning->userid);  
        } elseif ($coin->symbol === 'DOGE') {  
            $user = getdbo('db_accountsdoge', $earning->userid);  
        } else {  
            $user = getdbo('db_accounts', $earning->userid);  
        }  
  
        // Update the earnings record status and save  
        $earning->status = 2;  
        $earning->save();  
  
        // Update user balance and save  
        $value = $earning->amount;  
        $user->balance += $value;  
        $user->save();  
  
        $total_cleared += $earning->amount;  
    }  
  
    if ($total_cleared > 0) {  
        debuglog("Total cleared earnings: " . $total_cleared);  
    }  
}

