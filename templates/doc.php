<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bank API Documentation</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

		<link rel="stylesheet" href="public/css/style.css" />

	</head>
	<body>
		<div class="container">
			<h1>Bank API documentation</h1>

			<h3>Create a payment</h3>

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
					<td>Optionnal. Simulates a rejected payment if set to "rejected"</td>
					<td>String. Only "rejected" supported for the moment.</td>
					<td>rejected</td>
				</tr>
			</table>

			<h4>Token (tok) parameter generation</h4>
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

			<h4>Demo query</h4>
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
			<?php 
				$link = "http://localhost/bank/payment/create?ccn=$ccn&cvv=123&exp=122017&amo=$amo&cur=eur&mid=$mid&tim=$tim&tok=$tok";
			?>
			<a href="<?php echo $link; ?>"><?php echo $link; ?></a>
		</div>
	</body>
</html>