<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Domains\Organizacion\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [

            'Administración Planta',
            'Administrativa y Financiera',
            'Auditoría',
            'Cadena de Abastecimiento',
            'Cartera',
            'Comercial Biolev',
            'Comercial Consumo',
            'Comercial Exportaciones',
            'Comercial Panadería',
            'Comercio Exterior',
            'Compras',
            'Contabilidad e Impuestos',
            'Control Calidad',
            'Control Financiero',
            'Cuentas por Pagar',
            'Departamento Técnico',
            'Desarrollo de Negocios',
            'Gente y Cultura',
            'Gestión Integral',
            'Gestión Medioambiental',
            'Go To Market',
            'Investigación y Desarrollo',
            'Legal',
            'Logística',
            'Mantenimiento',
            'Mejora continua y proyectos',
            'Mercadeo',
            'Planeación de la Demanda',
            'Planeación Financiera',
            'Producción',
            'Servicios Administrativos',
            'Tecnología',
            'Tesorería',

        ];

        foreach ($areas as $area) {

            Area::create([
                'nombre' => $area,
                'codigo' => Str::slug($area),
            ]);

        }
    }
}