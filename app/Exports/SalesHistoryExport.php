<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\SalesManagement\Sale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesHistoryExport implements
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
        $query = Sale::query();

        if ($this->request->ticket_no) {
            $query = $query->where(Sale::TICKET_NO, 'LIKE', "%$this->request->ticket_no%");
        }
        if ($this->request->status_sale_id) {
            $query = $query->where(Sale::STATUS_SALE_ID, $this->request->status_sale_id);
        }
        if ($this->request->payment_type_id) {
            $query = $query->where(Sale::PAYMENT_TYPE_ID, $this->request->payment_type_id);
        }

        if ($this->request->customer) {

            $request = $this->request;
            $query = $query->whereHas('customer', function ($query) use ($request) {

                $query = $query->whereRaw('LOWER(business_name) LIKE (?) ', ["%{$request->customer}%"]);
            });
        }
        return $query->orderBy(Sale::DATE_SALE, 'desc');
    }

    public function map($collection): array
    {

        return [
            $collection->ticket_no,
            $collection->date_sale,
            $collection->total,
            $collection->sub_total,
            $collection->total_discount,
            $collection->total_taxt,
            $collection->mount_pay,
            $collection->change,
            $collection->voucher_payment_reference,
            $collection->card_payment_reference,
            $collection->is_with_invoice == 1 ? 'Sí': 'No',
            $collection->customer ? $collection->customer->name : 'Sin cliente',
            $collection->paymentType ? $collection->paymentType->name : 'Sin tipo de cobro',
            $collection->statusSale ? $collection->statusSale->name : 'Sin estatus',
            
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
            ['Lista de ventas'],
            [
                'No. ticket',
                'Día de venta',
                'Total',
                'Sub total',
                'Total de descuento',
                'Total de impuesto',
                'Monto pagado',
                'Cambio',
                'Referencia de vale',
                'Referencia de tarjeta de crédito o débito',
                'Aplica factura',
                'Cliente',
                'Tipo de cobro',
                'Estatus de venta',
            ]
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
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
                $event->sheet->mergeCells('A1:U5');

                // Asegurarse que el título esté centrado
                $event->sheet->mergeCells('A6:U6'); // Fusionamos las celdas donde estará el título
                $event->sheet->getDelegate()->getStyle('A6:U6')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A6:U6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A6:U6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A6:U6')->getFill()->setFillType(Fill::FILL_SOLID);
                $event->sheet->getDelegate()->getStyle('A6:U6')->getFill()->getStartColor()->setARGB('323a46');
                $event->sheet->getDelegate()->getStyle('A6:U6')->getFont()->getColor()->setARGB('ffffff');


                // Estilo para las cabeceras de las columnas
                $event->sheet->getDelegate()->getStyle('A7:U7')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A7:U7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A7:U7')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Ajustar el tamaño de las columnas
                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
            }
        ];
    }
}
