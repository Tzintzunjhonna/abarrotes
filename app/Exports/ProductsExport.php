<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Products\Products;
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

class ProductsExport implements
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
        $query = Products::query();

        if ($this->request->name) {
            $query = $query->whereRaw('LOWER(name) LIKE (?) ', ["%{$this->request->name}%"]);
        }

        if ($this->request->description) {
            $query = $query->whereRaw('LOWER(description) LIKE (?) ', ["%{$this->request->description}%"]);
        }

        if ($this->request->barcode) {
            $query = $query->whereRaw('LOWER(barcode) LIKE (?) ', ["%{$this->request->barcode}%"]);
        }

        if ($this->request->name_provider) {

            $request = $this->request;
            $query = $query->whereHas('has_provider', function ($query) use ($request) {

                $query = $query->whereRaw('LOWER(name) LIKE (?) ', ["%{$request->name_provider}%"]);
            });
        }
        if ($this->request->category_id) {

            $query = $query->whereRaw('LOWER(category_id) LIKE (?) ', ["%{$this->request->category_id}%"]);

        }
        
        return $query;
    }

    public function map($collection): array
    {

        // Obtener todos los impuestos asociados al producto
        $taxes = $collection->has_taxes()->with('tax_setting')->get();


        $taxNames = $taxes->isNotEmpty()
            ? $taxes->map(function ($tax) {
                return $tax->tax_setting->name;
            })->implode(', ')
            : 'Sin IVA';

        $taxPercentages = $taxes->isNotEmpty()
            ? $taxes->map(function ($tax) {
                return $tax->tax_setting->tasa_cuota_porcentage . '%';
            })->implode(', ')
            : 'Sin IVA';

        $is_retencion = $taxes->isNotEmpty()
            ? $taxes->map(function ($tax) {
                return $tax->tax_setting->is_retencion == 1 ? 'Sí' : 'No';
            })->implode(', ')
            : 'Sin IVA';

        $is_traslado = $taxes->isNotEmpty()
            ? $taxes->map(function ($tax) {
                return $tax->tax_setting->is_traslado == 1 ? 'Sí' : 'No';
            })->implode(', ')
            : 'Sin IVA';

        $tipo_impuesto = $taxes->isNotEmpty()
            ? $taxes->map(function ($tax) {
                return $tax->tax_setting->has_tipo_impuesto == null ? 'Sin tipo de impuesto' : $tax->tax_setting->has_tipo_impuesto->nombre;
            })->implode(', ')
            : 'Sin IVA';

        $tipo_factor = $taxes->isNotEmpty()
            ? $taxes->map(function ($tax) {
                return $tax->tax_setting->has_tipo_factor == null ? 'Sin tipo de factor' : $tax->tax_setting->has_tipo_factor->nombre;
            })->implode(', ')
            : 'Sin IVA';


        return [
            $collection->name,
            $collection->description,
            $collection->barcode,
            $collection->price,
            $collection->discount,
            $collection->stock,
            $collection->has_categorie_products ? $collection->has_categorie_products->name : 'Sin categoría',
            $collection->has_provider ? $collection->has_provider->name : 'Sin proveedor',
            $collection->has_unit_of_measurement ? $collection->has_unit_of_measurement->name : 'Sin unidad de medida',
            $collection->is_active == 1 ? 'Activo' : 'Inactivo',
            $collection->revenue,
            $collection->sale_price,
            $collection->wholesale_price,
            $collection->created_at,
            $collection->updated_at,
            $taxNames,
            $taxPercentages,
            $is_retencion,
            $is_traslado,
            $tipo_impuesto,
            $tipo_factor,
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
            ['Lista de productos'],
            [
                'Nombre',
                'Descipción',
                'Codigo',
                'Precio',
                'Descuento',
                'Inventario',
                'Categoría',
                'Proveedor',
                'Unidad de Medida',
                'Estatus',
                'Ganancía',
                'Precio venta',
                'Precio mayoreo',
                'Fecha de alta',
                'Fecha de modificación',
                'IVA/Impuestos',
                'Porcentaje',
                'Retención',
                'Traslado',
                'Tipo de impuesto',
                'Tipo factor',
            ]
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_NUMBER,
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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
