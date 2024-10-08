<?php

namespace Database\Seeders\Catalogues\Sat;

use App\Models\Sat\CatSatStatusCfdiCancel;
use Illuminate\Database\Seeder;

class CatStatusCfdiCancelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatSatStatusCfdiCancel::create(['codigo' => '201', 'nombre' => 'Solicitud recibida', 'descripcion' => 'Se ha recibido la petición de cancelación. No necesariamente significa que el Comprobante se ha cancelado.']);
        CatSatStatusCfdiCancel::create(['codigo' => '202', 'nombre' => 'Solicitud recibida anteriormente', 'descripcion' => 'Se ha recibido la petición de cancelación anteriormente. No necesariamente significa que el Comprobante se ha cancelado.']);
        CatSatStatusCfdiCancel::create(['codigo' => '203', 'nombre' => 'UUID No encontrado o no corresponde a emisor', 'descripcion' => 'El Folio Fiscal que se solicitó cancelar no se ha podido encontrar, o se encontró pero no fue emitido por el RFC especificado.']);
        CatSatStatusCfdiCancel::create(['codigo' => '204', 'nombre' => 'UUID No aplicable para cancelación', 'descripcion' => 'El Folio Fiscal no se puede cancelar. El SAT no especifica bajo que criterios puede ocurrir este código, pero no es frecuente.']);
        CatSatStatusCfdiCancel::create(['codigo' => '205', 'nombre' => 'UUID No existe', 'descripcion' => 'El SAT todavía no publica en su portal de internet el comprobante, y por lo tanto aún no puede ser cancelado.']);
        CatSatStatusCfdiCancel::create(['codigo' => '301', 'nombre' => 'XML mal formado', 'descripcion' => 'El XML de solicitud que se envió al SAT no está correctamente formado. Sólo relevante para el método de cancelación en línea con solicitud firmada de origen.']);
        CatSatStatusCfdiCancel::create(['codigo' => '302', 'nombre' => 'Sello mal formado o inválido', 'descripcion' => 'El Sello que se usó para generar la solicitud de cancelación es incorrecto.']);
        CatSatStatusCfdiCancel::create(['codigo' => '303', 'nombre' => 'Sello no corresponde a emisor', 'descripcion' => 'El certificado de sello digital (CSD) con el que se firmó la solicitud no corresponde al RFC del Emisor del CFDI.']);
        CatSatStatusCfdiCancel::create(['codigo' => '304', 'nombre' => 'Sello revocado o caduco', 'descripcion' => 'El certificado de sello digital con el que se firmó la solicitud de cancelación ha sido revocado o no esta vigente.']);
        CatSatStatusCfdiCancel::create(['codigo' => '305', 'nombre' => 'Certificado inválido', 'descripcion' => 'No se está usando para firmar la solicitud de cancelación un certificado expedido por el SAT']);
        CatSatStatusCfdiCancel::create(['codigo' => '310', 'nombre' => 'Uso de certificado e.firma inválido', 'descripcion' => 'Se está usando un certificado de e.firma en vez de un Certificado de Sello Digital (CSD)']);

    }
}
