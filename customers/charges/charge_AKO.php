<?php 

    $total = $_POST['total'];

    $token = $_POST['stripeToken'];

    //Moins ou égale à d'1€ aucune charge n'est appliquée
    if ($total <= 100) {
		exit(0);
	} //fin condition.

    require_once('../../vendor/autoload.php');
    include_once('../keys.php');

    //A modifier si nécessaire dans le fichier keys.php
    \Stripe\Stripe::setApiKey($STRIPE_SECRET_KEY_AKO);

    //Compte qui reçoit l'argent
    $stripeAccountId = 'acct_1AzQlhCWCYSXjXda';

    //Modifier les nombres en fonction du du client
	$brandRight = $total * 0.00;
	$authorRight = $total * 0.00;
	$bankFees = $total * 0.045;
	$salesFees = $total * 0.00;
	$marketingFees = $total * 0.00;
	$brandFees = $total * 0.00;
	$authorFees = $total * 0.00;
	$billFees = $total * 0.00;


	//Total des charges de la commission
	$totalFees = $brandRight + $authorRight + $bankFees + $salesFees + $marketingFees + $brandFees + $authorFees + $billFees;

	
	//Si le montant de la commission est inférieur à 50 centimes, le passe à 50 centimes
	if ($totalFees < 50) {
		$totalFees = 50;
	}
	else if ($totalFees > 50){
		$totalFees = $totalFees;
	}
	

	//Montant du total moins les charges
	$amount = $total - $totalFees;

  try {
    $charge = \Stripe\Charge::create(array(
      "amount" => $amount,
      "currency" => "eur",
      "source" => $token,
      "destination" => array(
        "amount" => $totalFees,
        "account" => "$stripeAccountId",
      ),
    ));
  }
  catch (Exception $e){
    echo ("Une erreur est survenue sur notre site, contactez le SAV.");
  }
    

      //Redirection après paiement réussi
    header('Location: https://ako-developpement.com/');
          
  exit(0);

?>