<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bank API Documentation</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.4/cosmo/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
			<h1>Bank API documentation</h1>

			<h2>Create a payment</h2>
			<p>Send all queries to:</p>
			<pre>http://guillaume.zz.mu/bank/payment/create</pre>

			<h3>Query parameters</h3>
			<table class="table table-striped">
				<tr>
					<th>Parameter</th>
					<th>Description</th>
					<th>Formatting</th>
					<th>Example</th>
				</tr>
				<tr>
					<td>ccn</td>
					<td>Credit card number</td>
					<td>Only digits</td>
					<td>1111222233334444</td>
				</tr>
				<tr>
					<td>cvv</td>
					<td>That little number on the back of the credit card</td>
					<td>Only digits</td>
					<td>123</td>
				</tr>
				<tr>
					<td>exp</td>
					<td>Credit card expiration date</td>
					<td>mmyyyy (two digits month, four digits year, only digits)</td>
					<td>032015</td>
				</tr>
				<tr>
					<td>amo</td>
					<td>Payment amount</td>
					<td>In cents ! No dots, no commas, only digits</td>
					<td>5599 (for a payment of 55.99)</td>
				</tr>
				<tr>
					<td>cur</td>
					<td>Payment currency. Only euro supported for the moment.</td>
					<td>eur</td>
					<td>eur</td>
				</tr>
				<tr>
					<td>mid</td>
					<td>Your merchant id</td>
					<td>alpha-numeric, lowercase</td>
					<td>gidow9302jg29bqo2r309z</td>
				</tr>
				<tr>
					<td>tim</td>
					<td>The transaction UNIX timestamp</td>
					<td>In seconds (10 digits)</td>
					<td>1431283077</td>
				</tr>
				<tr>
					<td>tok</td>
					<td>See below</td>
					<td>sha256 string (hexadecimal, 64 characters length)</td>
					<td>e9058ab198f6908f702111b0c0fb5b36f99[...]</td>
				</tr>
				<tr>
					<td>tes</td>
					<td>Optional. Simulates a rejected payment if set to "rejected"</td>
					<td>String. Only "rejected" supported for the moment.</td>
					<td>rejected</td>
				</tr>
			</table>

			<h3>Token (tok) parameter generation</h3>
			<p>To generate a transaction "tok":</p>
			<ol>
				<li>Concatenate, with no spaces, no dashes, and in that precise sequence:
					<ol>
						<li>Your "secret" token</li>
						<li>Your merchant id (mid)</li>
						<li>The credit card number (ccn)</li>
						<li>The transaction amount (amo)</li>
						<li>The transaction UNIX timestamp (tim)</li>
					</ol>
				</li>
				<li>Generate a sha256 hash of that string. This is your tok.</li>
			</ol>
			<p>In PHP, that would look like that:</p>
			<code>$tmp = $secret . $mid . $ccn . $amo . $tim;</code><br />
			<code>$tok = hash("sha256", $tmp);</code>
			

			<h2>Demo query</h2>
			<p>This is a demo query URI for the following data:</p>
			<table class="table table-striped">
				<tr>
					<td>Payment</td>
					<td><?php echo $amo ?> €</td>
				</tr>
				<tr>
					<td>Credit card</td>
					<td><?php echo $ccn; ?></td>
				<tr>
					<td>Merchant id</td>
					<td>abcd2345abcd2345abcd2345abcd2345</td>
				</tr>
				<tr>
					<td>Merchant secret</td>
					<td>pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876[...]</td>
				</tr>
				<tr>
					<td>Transaction time</td>
					<td><?php echo $tim; ?></td>
				</tr>
			</table>

			<h3>Demo query URI</h3>
			<?php 
				$link = "http://guillaume.zz.mu/bank/payment/create?ccn=$ccn&cvv=123&exp=122017&amo=$amo&cur=eur&mid=$mid&tim=$tim&tok=$tok";
			?>
			<pre><a href="<?php echo $link; ?>"><?php echo $link; ?></a></pre>

			<h3>Demo response</h3>
<pre>
	{
	   "transaction_id":"55506fad526f3",
	   "status":"payment_ok",
	   "message":"Payment created",
	   "data":{
	      "ccn":"4485491159053724",
	      "cvv":"123",
	      "exp":"122017",
	      "amo":"99",
	      "cur":"eur",
	      "mid":"abcd2345abcd2345abcd2345abcd2345",
	      "tim":"1431334815",
	      "tok":"c6614323a0e04438476d55711f876b12343a4ad4f7a08f1b084fe16625cd235d"
	   },
	   "errors":[

	   ]
	}
</pre>
		</div>
	</body>
</html>