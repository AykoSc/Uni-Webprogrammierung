<?php if (!isset($abs_path)) include_once "../path.php";

include_once $abs_path . "/php/util.php";
include_once $abs_path . "/controller/NutzerDAODummyImpl.php";

$user = new NutzerDAODummyImpl();
$user->registrieren();
?>
<p>fertig</p>
zur <a href="../index.php">Homepage</a>

<!-- <form method="post" action="php/request-bearbeiten.php"> -->