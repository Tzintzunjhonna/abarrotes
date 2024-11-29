<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Customers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomersExport implements
    FromQuery,
    WithMapping,
    WithHeadings,
    WithColumnFormatting,
    WithDrawings,
    WithEvents
{

    public function __construct(Request $request){
        $this->request = $request;
    }

    use Exportable;

    public function query()
    {
        $query = Customers::query();

        if ($this->request->name) {
            $query = $query->whereRaw('LOWER(name) LIKE (?) ', ["%{$this->request->name}%"]);
        }
        if ($this->request->email) {
            $query = $query->whereRaw('LOWER(email) LIKE (?) ', ["%{$this->request->email}%"]);
        }
        
        return $query;
    }

    public function map($collection): array
    {
        // dd($collection);


        $has_billing = $collection->has_billing;
        $has_address = $collection->has_address;

        $has_uso_cdfi = $has_billing->has_uso_cdfi->codigo . ' - ' . $has_billing->has_uso_cdfi->nombre;
        $has_regimen_fiscal = $has_billing->has_regimen_fiscal->codigo . ' - ' . $has_billing->has_regimen_fiscal->nombre;
        $has_metodo_pago = $has_billing->has_metodo_pago->codigo . ' - ' . $has_billing->has_metodo_pago->nombre;
        $has_forma_pago = $has_billing->has_forma_pago->codigo . ' - ' . $has_billing->has_forma_pago->nombre;
        $has_tipo_exportacion = $has_billing->has_tipo_exportacion->codigo . ' - ' . $has_billing->has_tipo_exportacion->nombre;
        $zip_code = $has_billing->zip_code;


        $has_address = $collection->has_address;

        $street = $has_address->street;
        $number = $has_address->number;

        $has_pais = $has_address->has_pais->nombre;
        $has_codigo_postal = $has_address->has_codigo_postal->codigo;
        $has_estado = $has_address->has_estado->nombre;
        $has_municipio = $has_address->has_municipio->nombre;
        $has_localidad = $has_address->has_localidad->nombre;

        return [
            $collection->name,
            $collection->business_name,
            $collection->rfc,
            $collection->address,
            $collection->phone,
            $collection->email,
            $collection->name_contact,
            $collection->is_active == 1 ? 'Activo' : 'Inactivo',
            $collection->created_at,
            $collection->updated_at,
            $has_uso_cdfi,
            $has_regimen_fiscal,
            $has_metodo_pago,
            $has_forma_pago,
            $has_tipo_exportacion,
            $zip_code,
            $street,
            $number,
            $has_pais,
            $has_codigo_postal,
            $has_estado,
            $has_municipio,
            $has_localidad,
        ];
    }

    public function headings(): array
    {
        //Agregar encabezados de la tabla, incluyendo espacio para imagen.
        return [
            [' '],
            [' '],
            [' '],
            [' '],
            [' '],
            ['Lista de clientes'],
            [
                'Nombre',
                'Razón social',
                'RFC',
                'Dirección',
                'Teléfono',
                'Correo',
                'Nombre de contacto',
                'Estatus',
                'Fecha de alta',
                'Fecha de modificación',
                'Uso de CFDI',
                'Regimen fiscal',
                'Forma de pago',
                'Método de pago',
                'Tipo de exportación',
                'Domicilio fiscal',
                'Calle',
                'Número',
                'Pais',
                'Código postal',
                'Estado',
                'Municipio',
                'Localidad'
            ]
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function drawings()
    {
        $company = Company::first();
        
        $path_image = $company == null ? '/images/abarrotes/logo.png' : $company->path_logo;

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path($path_image));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Fusionamos encabezado
                $event->sheet->mergeCells('A1:W5');

                // Asegurarse que el título esté centrado
                $event->sheet->mergeCells('A6:W6'); // Fusionamos las celdas donde estará el título
                $event->sheet->getDelegate()->getStyle('A6:W6')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A6:W6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A6:W6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A6:W6')->getFill()->setFillType(Fill::FILL_SOLID);
                $event->sheet->getDelegate()->getStyle('A6:W6')->getFill()->getStartColor()->setARGB('323a46');
                $event->sheet->getDelegate()->getStyle('A6:W6')->getFont()->getColor()->setARGB('ffffff');


                // Estilo para las cabeceras de las columnas
                $event->sheet->getDelegate()->getStyle('A7:W7')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A7:W7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A7:W7')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Ajustar el tamaño de las columnas
                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
            }
        ];
    }
}
