<br>

<div class="main-left-box">
<div class="main-left-title">YiiMP API</div>
<div class="main-left-inner">

<p>Simple REST API.</p>

<p><b>Wallet Status</b></p>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/wallet?address=<b>WALLET_ADDRESS</b></p>

result:
<pre class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
{
        "currency": "IFC",
	"unsold": 8584.62238943426,
	"balance": 2172.90325027,
	"unpaid": 10757.52563970,
	"paid24h": 118953.22180769,
	"total": 129710.74744739
}
</pre>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
        http://<?=YAAMP_API_URL?>/api/walletEx?address=<b>WALLET_ADDRESS</b></p>

result:
<pre class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
{
        "currency": "IFC",
	"unsold": 8584.62238943426,
	"balance": 2172.90325027,
	"unpaid": 10757.52563970,
	"paid24h": 118953.22180769,
	"total": 129710.74744739,
	"miners":[{
		"version": "ccminer\/4.10.0",
		"password": "123",
		"ID": "",
		"algo": "scrypt",
		"difficulty": 54630,
		"subscribe": 0,
		"accepted": 109984734.533,
		"rejected": 0
	}]
<?php if (YAAMP_API_PAYOUTS) : ?>
	,"payouts":[{
		"time": 1529860641,
		"amount": "0.001",
		"tx": "transaction_id_of_the_payout"
	}]
<?php endif; ?>
}
</pre>
<?php
if (YAAMP_API_PAYOUTS)
	echo "Payouts of the last ".(YAAMP_API_PAYOUTS_PERIOD / 3600)." hours are displayed, please use a block explorer to see all payouts.";
?>
<p><b>Pool Status</b></p>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/status</p>

result:
<pre class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
{
	"scrypt": {
		"name": "scrypt",
		"port": 9391,
		"coins": 2,
		"fees": 2,
		"hashrate": 3454313881,
		"workers": 29,
		"estimate_current": "0.00000000",
		"estimate_last24h": "0.00000000",
		"actual_last24h": "0.00000",
                "mbtc_mh_factor":1,
		"hashrate_last24h": 3434413815.3459
	},

	...
}
</pre>


request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/currencies</p>

result:
<pre class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #41464b; font-family: monospace;'>
{
	"DOGM": {
		"algo": "scrypt",
		"port": 9391,
		"name": "Dogmcoin",
		"height": 4744432,
                "difficulty":890,
                "minimumPayment":1,
		"workers": 0,    
		"shares": 1,
		"hashrate": 3195417287.938113,   
		"24h_blocks": 72,
		"lastblock": 4744426,
		"timesincelast": 390
        },
        "IFC": {
		"algo": "scrypt",
		"port": 9391,
		"name": "Infinitecoin",
		"height": 9288841,
                "difficulty":245,
                "minimumPayment":1,
		"workers": 29,
		"shares": 151,
		"hashrate": 3195417288,
		"24h_blocks": 358,
		"lastblock": 9288835,
		"timesincelast": 145
	},

	...
}
</pre>

<?php if (YAAMP_RENTAL) : ?>

<p><b>Rental Status</b></p>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #ffffee; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/rental?key=API_KEY</p>

result:
<pre class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #ffffee; font-family: monospace;'>
{
	"balance": 0.00000000,
	"unconfirmed": 0.00000000,
	"jobs":
	[
		{
			"jobid": "19",
			"algo": "x11",
			"price": "1",
			"hashrate": "1000000",
			"server": "stratum.server.com",
			"port": "3333",
			"username": "1A5pAdfWLUFXoqcUb6N9Fre2EApr5QLNdG",
			"password": "xx",
			"started": "1",
			"active": "1",
			"accepted": "586406.2014805333",
			"rejected": "",
			"diff": "0.04"
		},

		...

	]
}
</pre>

<p><b>Rental Price</b></p>

<p>Set the rental price of a job.</p>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #ffffee; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/rental_price?key=API_KEY&jobid=xx&price=xx</p>

</pre>

<p><b>Rental Hashrate</b></p>

<p>Set the rental max hashrate of a job.</p>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #ffffee; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/rental_hashrate?key=API_KEY&jobid=xx&hashrate=xx</p>

</pre>

<p><b>Start Rental Job</b></p>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #ffffee; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/rental_start?key=API_KEY&jobid=xx</p>

</pre>

<p><b>Stop Rental Job</b></p>

request:
<p class="main-left-box" style='padding: 3px; font-size: .8em; background-color: #ffffee; font-family: monospace;'>
	http://<?=YAAMP_API_URL?>/api/rental_stop?key=API_KEY&jobid=xx</p>

</pre>

<?php endif; /* RENTAL */ ?>

<br><br>

</div></div>

<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>

<script>


</script>


