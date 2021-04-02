<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#require_once '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class receiveRabbitMQ extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	
    } 
    public function receive()
    {
		$connection = new AMQPStreamConnection('78.47.72.194', 5672, 'admin', 'Dynargy2021','integracion');
		$channel = $connection->channel();	
		$callback = function ($msg) 
		{
  			echo ' [x] Received ', $msg->body, "\n";
		};
		//$channel->basic_consume('propuestas', '', false, true, false, false, $callback);

		/*while ($channel->is_consuming()) {
    $channel->wait();
	}*/

		//echo " [*] Waiting for messages. To exit press CTRL+C\n";
		//$channel->close();
		//$connection->close(); 
		return $channel->basic_consume('propuestas', '', false, true, false, false, $callback);
    }
    public function send()
    {
    	$connection = new AMQPStreamConnection('78.47.72.194', 5672, 'admin', 'Dynargy2021','integracion');
		$channel = $connection->channel();
		$channel->queue_declare('propuestas', true, true, true, true);
		$decode= json_encode('{"datos":{"serviciosAdicionalesMonth":10,"serviciosAdicionales":10,"alquilerEquipoMedida":4.97,"alquilerEquipoMedidaMonth":4.97,"codigoATR":"003","sumPotencia":124.53,"sumEnergia":0,"iePercent":6.36,"impuestoElectrico":124.53,"iva":30.63,"bi":145.86,"total":176.49,"penalizacionPotencia":0,"penalizacionEnergia":0,"numDaysNew":31,"numMonthNew":1,"fechaDesdeConsumo":"29-11-2020","fechaHastaConsumo":"30-12-2020","EnergiaActiva":["0"],"Potencia":["0"],"potenciaP1":18,"potenciaP2":18,"potenciaP3":18,"potenciaP4":0,"potenciaP5":0,"potenciaP6":0,"energiaActivaP1":0,"energiaActivaP2":0,"energiaActivaP3":0,"energiaActivaP4":0,"energiaActivaP5":0,"energiaActivaP6":0,"energiaReactivaP1":0,"energiaReactivaP2":0,"energiaReactivaP3":0,"energiaReactivaP4":0,"energiaReactivaP5":0,"energiaReactivaP6":0},"producto":{"id":"5","companyId":2,"nombreOferta":"Vive Winner 5","idComercializadora":"1207","nombreComercializadora":"VIVE ENERGÍA ELECTRICA S.A.","caractiristicas":null,"infoAdicional":"NO HAY OBLIGACIONES","precioTP1":0.111',JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
		$msg = new AMQPMessage($decode);
		$channel->basic_publish($msg, '', 'propuestas');
		echo " [x] Sent 'Hello World!'\n"; 
		$channel->close();
		$connection->close();

    }

}
