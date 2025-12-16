<?php


error_reporting(0);

class factElect
{

function facturaElectronica(string $crt,string $key,string $cuitEmisor,string $tipoIvaEmisor,string $ptoVta,string $clienteTipoIva,string $clienteCuit,
    float $total, float $neto, float $iva,string $tipo,string $fecha,
    string $emisor , string $ingresosBrutos, string $fechaInicioActividades, string $domicilio , string $razonSocial , string $nombreFantasia,
    string $clienteNombre , string $clienteDomicilio, array $detalle,
    string $clienteEmail, string $asunto, string $cuerpo,string $compAsoc,string $letraCompAsoc, string $destino)
{
    if ($tipo == "FA") {
        $tipoComp = "FACTURA";
    } else {
        $tipoComp = "NOTA DE CREDITO";
    }
        
    $F =  $this->getFactElect($crt,$key,$cuitEmisor,$tipoIvaEmisor,$ptoVta,$clienteTipoIva,$clienteCuit,$total,$neto,$iva,$tipoComp,$compAsoc,$letraCompAsoc);

    if ($F['Resultado'] != "A") {
        throw new Exception("Error al generar la factura electronica: " . $F['Resultado']);
    }
    $letra = $F['letra'];
    $codComp = $F['TipoComp'];
    $clienteDoc = $F['Tipodoc'];
    $copia = "ORIGINAL";
    $nroComp = $F['Nro'];
    $puntoVta = $F['PtoVta'];
    $cae = $F['CAE'];
    $fechaVtoCae = substr($F['Vto'], 6, 2)."/".substr($F['Vto'], 4, 2)."/".substr($F['Vto'], 0, 4);

    if ($destino == "PDF") {
        if ($asunto == '') {
            $asunto = "Factura Electrónica ".$tipoComp." Nro ".$nroComp;
        }
        if ($cuerpo == '') {
            $cuerpo = "Estimado/a ".$clienteNombre."\nAdjuntamos la Factura Electrónica Nro ".$nroComp.".\n\nSaludos Cordiales.";
        }
        
        $pdf =  $this->getPdf($tipoComp,$letra,$codComp,$copia,$nroComp,$puntoVta,$fecha,
        $cuitEmisor,$tipoIvaEmisor,$emisor ,$ingresosBrutos,$fechaInicioActividades,$domicilio ,$razonSocial ,$nombreFantasia,
        $clienteTipoIva,$clienteDoc,$clienteCuit,$clienteNombre , $clienteDomicilio,$cae,$fechaVtoCae, $neto, $iva, $total, $detalle);
        

        if ($clienteEmail <> '') {
            if ($this->enviarMail($pdf,$clienteEmail,$asunto,$cuerpo) ) 
            {
                $F['Mail'] = 'Mail enviado a '.$clienteEmail;
            }
        }
    }
    return $F;
}

function getQR(string $text){ 
  
    QRcode::png($text, 'testQR.png', 'L', 4, 2);
    QRcode::png($text);
}

function getPdf(string $tipoComp,string $letra,string $codComp,string $copia,string $nroComp,string $puntoVta,string $fecha,
    string $cuitEmisor,string $tipoIvaEmisor,string $emisor ,string $ingresosBrutos,string $fechaInicioActividades,string $domicilio ,string $razonSocial ,string $nombreFantasia,
    string $clienteTipoIva,string $clienteDoc,string $clienteCuit,string $clienteNombre , string $clienteDomicilio,string $cae,string $vtoCae,
    float $neto, float $iva, float $total, array $detalle)
{
    if ($tipoIvaEmisor == "RI") {
        $tipoIvaEmisor = "IVA Responsable Inscripto";
    } elseif ($tipoIvaEmisor == "MT") {
        $tipoIvaEmisor = "Monotributista";
    } else {
        $tipoIvaEmisor = "Exento";
    }   
    if ($clienteTipoIva == "RI") {
        $clienteTipoIva = "IVA Responsable Inscripto";
    } elseif ($clienteTipoIva == "MT") {
        $clienteTipoIva = "Monotributista";
    } elseif ($clienteTipoIva == "CF") {
        $clienteTipoIva = "Consumidor Final";
    } else {
        $clienteTipoIva = "Exento";
    }

    $pdf = new FPDF( 'P', 'mm', 'A4' );
    $pdf->SetAutoPagebreak(False);
    $pdf->SetMargins(0,0,0);
    $pdf->AddPage();
    $pdf->Line(5, 8, 205, 8);
    $pdf->Line(5, 18, 205, 18);
    $pdf->Line(95, 18, 95, 32);
    $pdf->Line(112, 18, 112, 32);
    $pdf->Line(95, 32, 112, 32);
    $pdf->Line(103, 32, 103, 65);

    $pdf->Line(5, 8, 5, 100);
    $pdf->Line(205, 8, 205, 100);

    $pdf->SetXY( 90, 10 ); $pdf->SetFont( "Arial", "B", 15 ); $pdf->Cell( 66, 8, $copia, 0, 0, 'L');
    // Lado Derecho
    //$pdf->Image('logo-rs-2.png', 10, 15, 55, 35);
    //$pdf->SetXY( 10, 20 ); $pdf->SetFont( "Arial", "B", 10 ); $pdf->Cell( 66, 8, $emisor, 0, 0, 'L');
    $pdf->SetXY( 10, 45 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 66, 8, 'Razon Social: '.$razonSocial, 0, 0, 'L');
    $pdf->SetXY( 10, 50 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 66, 8, 'Domicilio Comercial: '.$domicilio, 0, 0, 'L');
    $pdf->SetXY( 10, 55 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 66, 8, 'Condicion frente al Iva: '.$tipoIvaEmisor, 0, 0, 'L');
    // Lado Izquierdo

    $pdf->SetXY( 100, 20 ); $pdf->SetFont( "Arial", "B", 18 ); $pdf->Cell( 66, 8, $letra, 0, 0, 'L');
    $pdf->SetXY( 98, 25 ); $pdf->SetFont( "Arial", "B", 6 ); $pdf->Cell( 66, 8, 'Cod. '.$codComp, 0, 0, 'L');
    $pdf->SetXY( 120, 20 ); $pdf->SetFont( "Arial", "B", 18 ); $pdf->Cell( 85, 8, $tipoComp, 0, 0, 'L');
    $pdf->SetXY( 120, 30 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Punto de Venta: '.$puntoVta, 0, 0, 'L');
    $pdf->SetXY( 160, 30 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Comp. Nro. '.$nroComp, 0, 0, 'L');
    $pdf->SetXY( 120, 35 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Fecha: ' . $fecha, 0, 0, 'L');

    $pdf->SetXY( 120, 45 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 85, 8, 'CUIT: '.$cuitEmisor, 0, 0, 'L');
    $pdf->SetXY( 120, 50 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 85, 8, 'Ingresos Brutos: '.$ingresosBrutos, 0, 0, 'L');
    $pdf->SetXY( 120, 55 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 85, 8, 'Fecha de Inicio de Actividades: '.$fechaInicioActividades, 0, 0, 'L');


    $pdf->Line(5, 65, 205, 65);
    $pdf->SetXY( 10, 65 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Perido Facturado Desde: ', 0, 0, 'L');
    $pdf->SetXY( 45, 65 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 85, 8, $fecha, 0, 0, 'L');
    $pdf->SetXY( 85, 65 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Hasta: ', 0, 0, 'L');
    $pdf->SetXY( 95, 65 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, $fecha, 0, 0, 'L');
    $pdf->SetXY( 130, 65 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Fecha Vto para el pago: ', 0, 0, 'L');
    $pdf->SetXY( 170, 65 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, $fecha, 0, 0, 'L');

    $pdf->Line(5, 75, 205, 75);
    $pdf->SetXY( 10, 78 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'CUIT: '.$clienteCuit, 0, 0, 'L');
    $pdf->SetXY( 55, 78 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Apellido Nombre / Razon social:', 0, 0, 'L');
    $pdf->SetXY( 105, 78 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 85, 8, $clienteNombre, 0, 0, 'L');
    $pdf->SetXY( 10, 85 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Condicion frente al Iva:', 0, 0, 'L');
    $pdf->SetXY( 55, 85 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 85, 8, $clienteTipoIva, 0, 0, 'L');
    $pdf->SetXY( 105, 85 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Domicilio: ', 0, 0, 'L');
    $pdf->SetXY( 120, 85 ); $pdf->SetFont( "Arial", "", 8 ); $pdf->Cell( 85, 8, $clienteDomicilio, 0, 0, 'L');
    $pdf->SetXY( 10, 93 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Condicion de Venta: Contado', 0, 0, 'L');
    $pdf->Line(5, 100, 205, 100);

    $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(5, 102, 200, 5, "DF");
    $pdf->SetXY( 8, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Codigo', 0, 0, 'L');
    $pdf->Line(25, 102, 25, 107);
    $pdf->SetXY( 28, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Producto / Servicio', 0, 0, 'L');
    $pdf->Line(75, 102, 75, 107);
    $pdf->SetXY( 78, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Cantidad', 0, 0, 'L');
    $pdf->Line(96, 102, 96, 107);
    $pdf->SetXY( 98, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'U. Medida', 0, 0, 'L');
    $pdf->Line(115, 102, 115, 107);
    $pdf->SetXY( 117, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Precio Unit.', 0, 0, 'L');
    $pdf->Line(140, 102, 140, 107);
    $pdf->SetXY( 142, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, '% Bonif.', 0, 0, 'L');
    $pdf->Line(160, 102, 160, 107);
    $pdf->SetXY( 162, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Imp. Bonif.', 0, 0, 'L');
    $pdf->Line(180, 102, 180, 107);
    $pdf->SetXY( 188, 101 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Subtotal', 0, 0, 'L');

    $linea= 102;
    foreach ($detalle as $item) {
        $pdf->SetLineWidth(0.1); 
        $pdf->SetXY( 8, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, $item['codigo'], 0, 0, 'L');
        $pdf->SetXY( 28, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, $item['descripcion'], 0, 0, 'L');
        $pdf->SetXY( 78, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, $item['cantidad'], 0, 0, 'L');
        $pdf->SetXY( 98, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, $item['unidad'], 0, 0, 'L');
        $pdf->SetXY( 117, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, number_format($item['precioUnitario'],2,",","."), 0, 0, 'R');
        $pdf->SetXY( 142, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, number_format($item['bonificacion'],2,",","."), 0, 0, 'R');
        $pdf->SetXY( 162, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, number_format($item['importeBonificacion'],2,",","."), 0, 0, 'R');
        $pdf->SetXY( 188, $linea+5 ); $pdf->SetFont( "Arial", "", 7 ); $pdf->Cell( 85, 8, number_format($item['subtotal'],2,",","."), 0, 0, 'R');
        $linea += 4;
    }

    $pdf->Line(5, 210, 205, 210);
    $pdf->Line(5, 250, 205, 250);
    $pdf->Line(5, 210, 5, 250);
    $pdf->Line(205, 210, 205, 250);

    //$pdf->SetXY( 188, 210 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Subtotal', 0, 0, 'L');
    //$pdf->SetXY( 188, 218 ); $pdf->SetFont( "Arial", "B", 8 ); $pdf->Cell( 85, 8, 'Bonif', 0, 0, 'L');
    $pdf->SetXY( 158, 226 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 17, 8,  "Subtotal: $", 0, 0, 'R');
    $pdf->SetXY( 188, 226 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 17, 8,  number_format($total,2,",","."), 0, 0, 'R');
    $pdf->SetXY( 158, 234 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 17, 8,  "Importe Otros Tributos: $", 0, 0, 'R');
    $pdf->SetXY( 188, 234 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 17, 8, number_format($iva,2,",","."), 0, 0, 'R');
    $pdf->SetXY( 158, 242 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 17, 8,  "Importe Total: $", 0, 0, 'R');
    $pdf->SetXY( 188, 242 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 17, 8, number_format($total,2,",","."), 0, 0, 'R');

    $pdf->SetXY( 158, 252 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 85, 8,  "CAE: ".$cae, 0, 0, 'L');
    $pdf->SetXY( 128, 258 ); $pdf->SetFont( "Arial", "B", 9 ); $pdf->Cell( 85, 8,  "Fecha Vencimiento CAE: ".$vtoCae, 0, 0, 'L');
    
    
    // Generacion del QR
    
    $fecha = substr($fecha, 6, 4)."-".substr($fecha, 3, 2)."-".substr($fecha, 0, 2);
    $qrClave = '{"ver":1,"fecha": "'.$fecha.'","cuit":'.$cuitEmisor.',"ptoVta":'.$puntoVta.',"tipoCmp":'.$codComp.
',"nroCmp":'.$nroComp.',"importe":'.$total.',"moneda":"PES","ctz":1,"tipoDocRec":'.$clienteDoc.',"nroDocRec":'.$clienteCuit.',"tipoCodAut":"E","codAut":'.$cae.'}';
    
    //$pdf->SetXY( 10, 140 ); $pdf->SetFont( "Arial", "B", 4); $pdf->Cell( 85, 8, $qrClave, 0, 0, 'L');
    
    $qrCode =  "https://www.arca.gob.ar/fe/qr/?p=".base64_encode($qrClave);
    QRcode::png($qrCode, 'QR.png', 'L', 4, 2);
    $pdf->Image('QR.png', 10, 252, 35, 35);

    // Rellenar con ceros a la izquierda
    while (strlen($nroComp) < 8) {
        $nroComp = "0".$nroComp;
    }
     while (strlen($puntoVta) < 4) {
        $puntoVta = "0".$puntoVta;
    }

    
   
    // Reemplazar expacios por guiones
    $clienteNombre = str_replace(" ", "_", $clienteNombre);
    // Convertir a mayusculas
    $clienteNombre = strtoupper($clienteNombre);
    $salida = 'facturas/'.$clienteNombre.'_FE_'.$cuitEmisor.'_'.$letra.'_'.$puntoVta.'_'.$nroComp.'.pdf';
    $pdf->Output('F',$salida);
    return $salida;
}

function getFactElect(string $crt,string $key,string $cuitEmisor,string $tipoIvaEmisor,string $ptoVta,string $clienteTipoIva,string $clienteCuit,
    float $total, float $neto, float $iva , string $tipoComp, string $compAsoc, string $letraCompAsoc)
{
# Ejemplo de Uso de Interface COM con Web Services AFIP (PyAfipWs) para PHP
# WSFEv1 2.5 (factura electrónica mercado interno sin detalle -régimen general-)
# RG2485 RG2485/08 RG2757/10 RG2904/10 RG3067/11 RG3571/13 RG3668/14 RG3749/15
# 2015 (C) Mariano Reingart <reingart@gmail.com> licencia AGPLv3+
#
# Documentación:
#  * http://www.sistemasagiles.com.ar/trac/wiki/ProyectoWSFEv1
#  * http://www.sistemasagiles.com.ar/trac/wiki/ManualPyAfipWs
#
# Instalación: agregar en el php.ini las siguientes lineas (sin #)
# [COM_DOT_NET] 
# extension=ext\php_com_dotnet.dll 

$HOMO = false;   # homologación (testing / pruebas) o producción
$CACHE = "";    # directorio para archivos temporales (usar por defecto)

try {

	# Crear objeto interface Web Service Autenticación y Autorización
	$WSAA = new COM('WSAA'); 
	# Generar un Ticket de Requerimiento de Acceso (TRA)
	$tra = $WSAA->CreateTRA() ;
	
	# Especificar la ubicacion de los archivos certificado y clave privada
	$path = getcwd()  . "\\certificados\\"; # directorio de certificados
	# Certificado: certificado es el firmado por la AFIP
	# ClavePrivada: la clave privada usada para crear el certificado
	$Certificado = $crt; // certificado de prueba
	$ClavePrivada = $key; // clave privada de prueba
	# Generar el mensaje firmado (CMS) ;
	$cms = $WSAA->SignTRA($tra, $path . $Certificado, $path . $ClavePrivada);

    # iniciar la conexión al webservice de autenticación
    if ($HOMO)
        $wsdl = "https://wsaahomo.afip.gov.ar/ws/services/LoginCms";
    else
        $wsdl = "https://wsaa.afip.gov.ar/ws/services/LoginCms"; # producción
	$ok = $WSAA->Conectar($CACHE, $wsdl);
	
	# Llamar al web service para autenticar
	$ta = $WSAA->LoginCMS($cms);
	
	//echo "Token de Acceso: $WSAA->Token \n";
	//echo "Sing de Acceso: $WSAA->Sign \n";

	
	# Crear objeto interface Web Service de Factura Electrónica v1 (version 2.5)
	$WSFEv1 = new COM('WSFEv1');
	# Setear tocken y sing de autorización (pasos previos) Y CUIT del emisor
	$WSFEv1->Token = $WSAA->Token;
	$WSFEv1->Sign = $WSAA->Sign; 
	$WSFEv1->Cuit = $cuitEmisor;
	
	# Conectar al Servicio Web de Facturación: homologación testing o producción
	if ($HOMO)
    	$wsdl = "https://wswhomo.afip.gov.ar/wsfev1/service.asmx?WSDL";	
	else
    	$wsdl = "https://servicios1.afip.gov.ar/wsfev1/service.asmx?WSDL"; 
	$ok = $WSFEv1->Conectar($CACHE, $wsdl); // pruebas
	#$ok = WSFE.Conectar() ' producción # producción
	
	# Llamo a un servicio nulo, para obtener el estado del servidor (opcional)
	$WSFEv1->Dummy();
	//echo "appserver status $WSFEv1->AppServerStatus \n";
	//echo "dbserver status $WSFEv1->DbServerStatus \n";
	//echo "authserver status $WSFEv1->AuthServerStatus \n";
		
	# Recupero último número de comprobante para un punto venta/tipo (opcional)
    // Letra A : 1 Factura - 2 Nota de Débito - 3 Nota de Crédito
    // Letra B : 6 Factura - 7 Nota de Débito - 8 Nota de Crédito
    // Letra C : 11 Factura - 12 Nota de Débito - 13 Nota de Crédito
    $tipo_doc=99;
    if ($tipoIvaEmisor == "RI") {
        if ($clienteTipoIva == "RI" ) {
            $letra = "A";
            if ($tipoComp == "FACTURA") {
                $tipo_cbte = 1;
            } else {
                $tipo_cbte = 3;
            } 
            
            $tipo_doc = 80;                 
            $nro_doc = $clienteCuit; 
        } elseif ($clienteTipoIva == "MT" ) {
            $letra = "A";
             if ($tipoComp == "FACTURA") {
                $tipo_cbte = 1;
            } else {
                $tipo_cbte = 3;
            } 
            if ($clienteCuit == "0") {
                $tipo_doc = 99;
                $nro_doc = "0";
            } else {
                $tipo_doc = 96;
                $nro_doc = $clienteCuit;
            }  
        } else {
            $letra = "B";
             if ($tipoComp == "FACTURA") {
                $tipo_cbte = 6;
            } else {
                $tipo_cbte = 8;
            } 
            if ($clienteCuit == "0") {
                $tipo_doc = 99;
                $nro_doc = "0";
            } else {
                $tipo_doc = 96;
                $nro_doc = $clienteCuit;
            }  
        }
    	 
    } elseif ($tipoIvaEmisor == "MT") {
        if ($tipoComp == "FACTURA") {
            $tipo_cbte = 11;
        } else {
            $tipo_cbte = 13;
        } 
        if ($clienteTipoIva == "RI" ) {
            $letra = "C";
            $tipo_doc = 80;                 
            $nro_doc = $clienteCuit;
        } else {
            $letra = "C";
            if ($clienteCuit == "0") {
                $tipo_doc = 99;
                $nro_doc = "0";
            } else {
                $tipo_doc = 96;
                $nro_doc = $clienteCuit;
            }
        }
    }    
    $punto_vta = $ptoVta; # punto de venta (sucursal)
	$ult = $WSFEv1->CompUltimoAutorizado($tipo_cbte, $punto_vta);
	
	# Establezco los valores de la factura o lote a autorizar:
	$fecha = date("Ymd");
	//echo "Fecha $fecha \n";
	$concepto = 1;                    # 1: productos, 2: servicios, 3: ambos
	//$tipo_doc : 80: CUIT, 96: DNI, 99: Consumidor Final
	//$nro_doc : 0 para Consumidor Final (<$10000000) o CUIT sin guiones o DNI

            
	$cbt_desde = $ult + 1; 
	$cbt_hasta = $ult + 1;

    if ($tipoIvaEmisor == "MT" ) {
        $imp_total = $total;          # total del comprobante
        $imp_tot_conc = "0.00";         # subtotal de conceptos no gravados
        $imp_neto = $total;           # subtotal neto sujeto a IVA
        $imp_iva = "0";             # subtotal impuesto IVA liquidado
        $imp_trib = "0.00";             # subtotal otros impuestos
        $imp_op_ex = "0.00";            # subtotal de operaciones exentas
    } else {
        $imp_total = $total;          # total del comprobante
        $imp_tot_conc = "0.00";         # subtotal de conceptos no gravados
        $imp_neto = $neto;           # subtotal neto sujeto a IVA
        $imp_iva = $iva;             # subtotal impuesto IVA liquidado
        $imp_trib = "0.00";             # subtotal otros impuestos
        $imp_op_ex = "0.00";            # subtotal de operaciones exentas
    }

    $fecha_cbte = $fecha;
    $fecha_venc_pago = "";          # solo servicios
    # Fechas del período del servicio facturado (solo si concepto = 1?)
    $fecha_serv_desde = "";
    $fecha_serv_hasta = "";
    $moneda_id = "PES";             # no utilizar DOL u otra moneda 
    $moneda_ctz = "1.000";          # (deshabilitado por AFIP)

    $caea = "";                     # CAEA (si es necesario)
    $fecha_hs_gen = date("YmdHis"); # fecha y hora de generación
    $cancela_misma_moneda_ext = "N"; # S/N (solo si moneda_id distinta de PES)

    if ($tipo_cbte == 6 || $tipo_cbte == 7 || $tipo_cbte == 8 || $tipo_cbte == 3 || $tipo_cbte == 13) 
    {
        $condicion_iva_receptor_id = 5;
    } else {
        $condicion_iva_receptor_id = 1;
    }
         
	
	# Inicializo la factura interna con los datos de la cabecera

    $ok = $WSFEv1->CrearFactura($concepto, $tipo_doc, $nro_doc, 
	    $tipo_cbte, $punto_vta, $cbt_desde, $cbt_hasta, 
	    $imp_total, $imp_tot_conc, $imp_neto, $imp_iva, $imp_trib, $imp_op_ex,
	    $fecha_cbte, $fecha_venc_pago, $fecha_serv_desde, $fecha_serv_hasta,
        $moneda_id, $moneda_ctz, $caea, $fecha_hs_gen, 
        $cancela_misma_moneda_ext, $condicion_iva_receptor_id);

    # Agrego los comprobantes asociados (solo para notas de crédito y débito):
    if ($tipo_cbte == 3 || $tipo_cbte == 13 || $tipo_cbte == 8 ) {
        if ($letraCompAsoc == "A") {
            $tipo = 1;
        } elseif ($letraCompAsoc == "B") {
            $tipo = 1;
        } elseif ($letraCompAsoc == "C") {
            $tipo = 11;
        }    
        //$tipo = 1; # tipo de comprobante asociado 
        $ok = $WSFEv1->AgregarCmpAsoc($tipo, $punto_vta, $compAsoc);
    }
        
    # Agrego impuestos varios
    /*
    $tributo_id = 99;
    $ds = "Impuesto Municipal Matanza'";
    $base_imp = "100.00";
    $alic = "0.10";
    $importe = "0.10";
    $ok = $WSFEv1->AgregarTributo($tributo_id, $ds, $base_imp, $alic, $importe);

    # Agrego impuestos varios
    $tributo_id = 4;
    $ds = "Impuestos internos";
    $base_imp = "100.00";
    $alic = "0.40";
    $importe = "0.40";
    $ok = $WSFEv1->AgregarTributo($tributo_id, $ds, $base_imp, $alic, $importe);

    # Agrego impuestos varios
    $tributo_id = 1;
    $ds = "Impuesto nacional";
    $base_imp = "50.00";
    $alic = "1.00";
    $importe = "0.50";
    $ok = $WSFEv1->AgregarTributo($tributo_id, $ds, $base_imp, $alic, $importe);
    */

    if ($tipoIvaEmisor == "RI" ) {
        # Agrego tasas de IVA
        $iva_id = 5;             # 21%
        $base_imp = "100.00";
        $importe = "21.00";
        $ok = $WSFEv1->AgregarIva($iva_id, $base_imp, $importe);
    }
    # Agrego tasas de IVA 
    /*
    $iva_id = 4;            # 10.5%  
    $base_imp = "50.00";
    $importe = "5.25";
    $ok = $WSFEv1->AgregarIva($iva_id, $base_imp, $importe);
    */
    
    # Agrego datos opcionales  RG 3668 Impuesto al Valor Agregado - Art.12 
    # ("presunción no vinculación la actividad gravada", F.8001):
    /*
    if ($tipo_cbte == 1) {  # solo para facturas A
        # IVA Excepciones (01: Locador/Prestador, 02: Conferencias, 03: RG 74, 04: Bienes de cambio, 05: Ropa de trabajo, 06: Intermediario).
        $ok = $WSFEv1->AgregarOpcional(5, "02");
        # Firmante Doc Tipo (80: CUIT, 96: DNI, etc.)
        $ok = $WSFEv1->AgregarOpcional(61, "80");
        # Firmante Doc Nro:
        $ok = $WSFEv1->AgregarOpcional(62, "20267565393");
        # Carácter del Firmante (01: Titular, 02: Director/Presidente, 03: Apoderado, 04: Empleado)
        $ok = $WSFEv1->AgregarOpcional(7, "01");
    }
    */
    # proximamente más valores opcionales para RG 3749/2015
    
    # Habilito reprocesamiento automático (predeterminado):
    $WSFEv1->Reprocesar = true;
        
	# Llamo al WebService de Autorización para obtener el CAE
	$cae = $WSFEv1->CAESolicitar();
	
    /*
	echo "Resultado=$WSFEv1->Resultado \n";
	echo "Nro CBTE=$WSFEv1->CbteNro \n";
	echo "CAE=$cae \n";
	echo "Vencimiento $WSFEv1->Vencimiento"; # Fecha de vto. de la autorización
	echo "Tipo Emision=$WSFEv1->EmisionTipo\n";	
	echo "Reproceso=$WSFEv1->Reproceso\n";
	echo "Errores=$WSFEv1->ErrMsg\n";
    */

    
	
	# Verifico que no haya rechazo o advertencia al generar el CAE
    $mensaje = "";
	if ($cae=="") {
        $mensaje = "La página esta caida o la respuesta es inválida";
	} elseif ($cae=="NULL" || $WSFEv1->Resultado!="A") {
		$mensaje = "No se asignó CAE (Rechazado). Motivos: $WSFEv1->Motivo";
	} elseif ($WSFEv1->Obs!="") {
		$mensaje = "Se asignó CAE pero con advertencias. Motivos: $WSFEv1->Obs";
	} 


   $R = array (
            'Resultado' => $WSFEv1->Resultado,
            'letra' => $letra,
            'PtoVta' => $punto_vta,
            'Nro' => $WSFEv1->CbteNro, 
            'CAE' => $cae, 
            'Vto' => $WSFEv1->Vencimiento, 
            'TipoComp' => $tipo_cbte,
            'Tipodoc' => $tipo_doc,
            'EmisionTipo' => $WSFEv1->EmisionTipo,
            'Reproceso' => $WSFEv1->Reproceso,
            'ErrMsg' => $WSFEv1->ErrMsg,
            'Mensaje' =>  $mensaje ,
            "Mail" => ''
     );

    file_put_contents("resultado.txt", print_r($R, true));

} catch (Exception $e) {
	echo 'Excepción: ',  $e->getMessage(), "\n";
	if (isset($WSAA)) {
	    //echo "WSAA.Excepcion: $WSAA->Excepcion \n";
	    //echo "WSAA.Traceback: $WSAA->Traceback \n";
        $excepcion =  $WSAA->Excepcion;
        $traceback =  $WSAA->Traceback;
	}
	if (isset($WSFEv1)) {
	    //echo "WSFEv1.Excepcion: $WSFEv1->Excepcion \n";
	    //echo "WSFEv1.Traceback: $WSFEv1->Traceback \n";
        $excepcion =  $WSFEv1->Excepcion;
        $traceback =  $WSFEv1->Traceback;
	}
    $R =array ( 
            'Excepcion' => $excepcion,
            'Traceback' => $traceback
    );
}
if (isset($WSFEv1)) {
    # almacenar la respuesta para depuración / testing
    # (guardar en un directorio no descargable al subir a un servidor web)
    file_put_contents("request.xml", $WSFEv1->XmlRequest);
    file_put_contents("response.xml", $WSFEv1->XmlResponse);

} // fin if isset WSFEv1   
return $R; 
}


function enviarMail(string $pdf, string $clienteEmail, string $asunto, string $cuerpo)
{
    


$mail = new \PHPMailer\PHPMailer\PHPMailer(true);

// admin@restosoft.com.ar
// Rest0.s0ft

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'mail.restosoft.com.ar'; // Tu servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@restosoft.com.ar'; // Tu usuario SMTP
    $mail->Password = 'Rest0.s0ft'; // Tu contraseña SMTP
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465; // O el puerto de tu servidor SMTP

    // Destinatarios
    $mail->setFrom('admin@restosoft.com.ar', 'RestoSoft Administracion');
    $mail->addAddress($clienteEmail);
    $mail->AddAttachment($pdf); 
    //$mail->addCC('otro_remitente@example.com');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body    = $cuerpo;

    $mail->send();
    //echo 'El mensaje se ha enviado';
    return true;
} catch (Exception $e) {
    //echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
    return false;
}
}

} // fin class factElect

?>