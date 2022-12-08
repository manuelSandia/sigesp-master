<?
 function dar_encabezado($as_titulo,$as_fecha){
  $hora=date("h:i a"); $fecha_hoy=date("d/m/Y");
  echo "<table width='1000' border='0'><tr><td colspan='6'><img src='http://172.0.0.6/sigesp/shared/imagebank/logo_mh.jpg' width='700' height='71'></td></tr>
    <tr><td colspan='6'>&nbsp;</td></tr>
    <tr><td colspan='6'>&nbsp;</td></tr>
    <tr><td colspan='6'>&nbsp;</td></tr>
  <tr align='center'><td colspan='5'>".$as_titulo."</td><td>".$fecha_hoy."</td></tr><tr align='center'><td colspan='5'>".$as_fecha."</td><td>".$hora."</td></tr>
        <tr><td colspan='5'>&nbsp;</td><td>&nbsp;</td></tr></table>";
 }
 
 
 function dar_cabecera($as_nomban,$as_ctaban,$as_dencta,$as_ban){
 if($as_ban<>0){
 echo "</tr></table><table width='1000' border='1' bgcolor='#F2F2F2'>
  <tr>
    <td colspan='6'>
	  <div>".$as_nomban."</div>
	  <div>".$as_ctaban."     ".$as_dencta."</div>
	</td>
  </tr>
</table>" ; }
 }
 
 function dar_detalle_sub($x){
 echo "<table width='1000' border='1'>
  <tr align='center'>
    <td><strong>Fecha Emision</strong></td>
    <td><strong>Document</strong>o</td>
    <td><strong>Beneficiario</strong></td>
    <td><strong>Monto</strong></td>
    <td><strong>Estatus</strong></td>
    <td><strong>Fecha Vcto.</strong></td>
  </tr>" ;
 }
 
 function dar_detalle($ad_fecemi,$as_numdoc,$as_nomproben,$adec_monto,$as_estmov,$ad_fecvenc){
 echo "<tr align='center'>
    <td>".$ad_fecemi."</td>
    <td>".$as_numdoc."</td>
    <td>".$as_nomproben."</td>
    <td>".$adec_monto."</td>
    <td>".$as_estmov."</td>
    <td>".$ad_fecvenc."</td>
  </tr>" ;
 }
?>

