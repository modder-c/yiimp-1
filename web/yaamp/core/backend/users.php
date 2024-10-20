<?php  
  
function BackendUsersUpdate($coin)  
{  
    $t1 = microtime(true); // Start timing the function execution  
  
    // Retrieve user list based on currency symbol  
    if ($coin->symbol === 'DOGM') {  
        $list = getdbolist('db_accountsdogm', "coinid IS NULL OR IFNULL(coinsymbol,'') != ''");   
    } elseif ($coin->symbol === 'DOGE') {   
        $list = getdbolist('db_accountsdoge', "coinid IS NULL OR IFNULL(coinsymbol,'') != ''");   
    } else {   
        $list = getdbolist('db_accounts', "coinid IS NULL OR IFNULL(coinsymbol,'') != ''");   
    }   
  
    // Iterate through each user in the list  
    foreach($list as $user)  
    {  
        $old_usercoinid = $user->coinid; // Store the old coinid for reference  
  
        // If coinsymbol is not empty, attempt to update coinid  
        if(!empty($user->coinsymbol))  
        {  
            // Fetch coin details based on coinsymbol  
            $coin = getdbosql('db_coins', "symbol=:symbol", array(':symbol'=>$user->coinsymbol));  
            $user->coinsymbol = ''; // Clear the coinsymbol since it's being processed  
  
            if($coin)  
            {  
                // If the current coinid does not match the fetched coin id, update it (without converting balance)  
                if($user->coinid != $coin->id)  
                {  
                    $user->coinid = $coin->id; // Update the coinid  
                    $user->save(); // Save the changes to the user  
  
                    // Optional: Log the update  
                    debuglog("{$user->username} updated to coin {$coin->symbol} without balance conversion");  
                }  
            }  
        }  
  
        // If coinsymbol is empty or no matching coin found, handle coinid = 0 case  
        if (empty($user->coinsymbol) || !$coin) {  
            $user->coinid = 0; // Set coinid to 0 as a default  
  
            // Iterate through enabled coins and update coinid based on validity (without converting balance)  
            $order = YAAMP_ALLOW_EXCHANGE ? "difficulty" : "id"; // Determine the order of coins based on configuration  
            $coins = getdbolist('db_coins', "enable ORDER BY $order DESC"); // Fetch the list of enabled coins  
            foreach($coins as $coin)  
            {  
                $remote = new WalletRPC($coin); // Create a WalletRPC instance for the current coin  
  
                // Validate the user's address with the remote wallet  
                $b = $remote->validateaddress($user->username);  
                if(arraySafeVal($b,'isvalid')) // Check if the address is valid  
                {  
                    $user->coinid = $coin->id; // Update the coinid to the current coin's id  
                    $user->save(); // Save the changes to the user  
  
                    // Optional: Log the update  
                    debuglog("{$user->username} set to coin {$coin->symbol} (no balance conversion)");  
                    break; // Exit the loop since a valid coin was found  
                }  
            }  
  
            // If no valid coinid was found, log the unknown address  
            if (empty($user->coinid)) {  
                debuglog("{$user->hostaddr} - {$user->username} is an unknown address!");  
            }  
        }  
    }  
  
    // Calculate the execution time and add it to the monitoring system  
    $d1 = microtime(true) - $t1;  
    controller()->memcache->add_monitoring_function(__METHOD__, $d1);  
}
