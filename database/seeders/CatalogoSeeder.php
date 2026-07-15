<?php

namespace Database\Seeders;

use App\Models\DerechoHabiente;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\PersonalArchivo;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            ['nombre' => 'Laboratorios Farmacéuticos SA', 'rfc' => 'LFS850101AB1', 'email' => 'contacto@lfs.com', 'direccion' => 'Av. Industria 1234, CDMX'],
            ['nombre' => 'Distribuidora Médica del Norte', 'rfc' => 'DMN920315CD2', 'email' => 'ventas@dmn.com', 'direccion' => 'Blvd. Industrias 5678, Monterrey'],
            ['nombre' => 'Suministros Hospitalarios México', 'rfc' => 'SHM780620EF3', 'email' => 'info@shm.com', 'direccion' => 'Calle Salud 901, Guadalajara'],
            ['nombre' => 'Grupo Proveedor General', 'rfc' => 'GPG010510GH4', 'email' => 'general@gpg.com', 'direccion' => 'Av. Comercio 3456, Puebla'],
        ];

        foreach ($proveedores as $datos) {
            Proveedor::firstOrCreate(['rfc' => $datos['rfc']], $datos);
        }

        $productos = [
            ['clave' => 'PROD-001', 'nombre' => 'Guantes desechables', 'categoria' => Producto::CATEGORIA_INSUMO, 'unidad_medida' => 'caja', 'stock_minimo' => 10],
            ['clave' => 'PROD-002', 'nombre' => 'Jeringas 5ml', 'categoria' => Producto::CATEGORIA_INSUMO, 'unidad_medida' => 'pieza', 'stock_minimo' => 50],
            ['clave' => 'PROD-003', 'nombre' => 'Gasas estériles', 'categoria' => Producto::CATEGORIA_INSUMO, 'unidad_medida' => 'paquete', 'stock_minimo' => 20],
            ['clave' => 'PROD-004', 'nombre' => 'Paracetamol 500mg', 'categoria' => Producto::CATEGORIA_MEDICAMENTO, 'unidad_medida' => 'caja', 'stock_minimo' => 15],
            ['clave' => 'PROD-005', 'nombre' => 'Amoxicilina 500mg', 'categoria' => Producto::CATEGORIA_MEDICAMENTO, 'unidad_medida' => 'caja', 'stock_minimo' => 15],
            ['clave' => 'PROD-006', 'nombre' => 'Ibuprofeno 400mg', 'categoria' => Producto::CATEGORIA_MEDICAMENTO, 'unidad_medida' => 'caja', 'stock_minimo' => 15],
            ['clave' => 'PROD-007', 'nombre' => 'Papel bond A4', 'categoria' => Producto::CATEGORIA_PAPELERIA, 'unidad_medida' => 'resma', 'stock_minimo' => 5],
            ['clave' => 'PROD-008', 'nombre' => 'Folders tamaño oficio', 'categoria' => Producto::CATEGORIA_PAPELERIA, 'unidad_medida' => 'pieza', 'stock_minimo' => 20],
            ['clave' => 'PROD-009', 'nombre' => 'Oxígeno medicinal', 'categoria' => Producto::CATEGORIA_OTRO, 'unidad_medida' => 'cilindro', 'stock_minimo' => 3],
            ['clave' => 'PROD-010', 'nombre' => 'Alcohol gel 500ml', 'categoria' => Producto::CATEGORIA_INSUMO, 'unidad_medida' => 'pieza', 'stock_minimo' => 10],
        ];

        foreach ($productos as $datos) {
            Producto::firstOrCreate(['clave' => $datos['clave']], $datos);
        }

        $medicamentos = [
            ['clave' => 'MED-001', 'nombre' => 'Paracetamol', 'sustancia_activa' => 'Paracetamol', 'presentacion' => 'Tabletas 500mg, caja c/20', 'stock_minimo' => 20, 'id_producto' => Producto::where('clave', 'PROD-004')->first()?->id],
            ['clave' => 'MED-002', 'nombre' => 'Amoxicilina', 'sustancia_activa' => 'Amoxicilina', 'presentacion' => 'Cápsulas 500mg, caja c/21', 'stock_minimo' => 15, 'id_producto' => Producto::where('clave', 'PROD-005')->first()?->id],
            ['clave' => 'MED-003', 'nombre' => 'Ibuprofeno', 'sustancia_activa' => 'Ibuprofeno', 'presentacion' => 'Tabletas 400mg, caja c/20', 'stock_minimo' => 15, 'id_producto' => Producto::where('clave', 'PROD-006')->first()?->id],
            ['clave' => 'MED-004', 'nombre' => 'Omeprazol', 'sustancia_activa' => 'Omeprazol', 'presentacion' => 'Cápsulas 20mg, caja c/14', 'stock_minimo' => 10, 'id_producto' => null],
            ['clave' => 'MED-005', 'nombre' => 'Losartán', 'sustancia_activa' => 'Losartán potásico', 'presentacion' => 'Tabletas 50mg, caja c/28', 'stock_minimo' => 10, 'id_producto' => null],
            ['clave' => 'MED-006', 'nombre' => 'Metformina', 'sustancia_activa' => 'Metformina clorhidrato', 'presentacion' => 'Tabletas 850mg, caja c/20', 'stock_minimo' => 10, 'id_producto' => null],
        ];

        foreach ($medicamentos as $datos) {
            Medicamento::firstOrCreate(['clave' => $datos['clave']], $datos);
        }

        $medicos = [
            ['nombre' => 'Roberto', 'apellido_paterno' => 'García', 'apellido_materno' => 'Luna', 'rfc' => 'GALR800101AB1', 'numero_empleado' => 'MED-0001', 'cargo' => 'Médico General', 'area' => 'Consulta Externa', 'tipo' => 'base'],
            ['nombre' => 'Laura', 'apellido_paterno' => 'Mendoza', 'apellido_materno' => 'Ríos', 'rfc' => 'MERL850215CD2', 'numero_empleado' => 'MED-0002', 'cargo' => 'Médico Internista', 'area' => 'Medicina Interna', 'tipo' => 'confianza'],
            ['nombre' => 'Javier', 'apellido_paterno' => 'Soto', 'apellido_materno' => 'Campos', 'rfc' => 'SOCJ900320EF3', 'numero_empleado' => 'MED-0003', 'cargo' => 'Médico Residente', 'area' => 'Urgencias', 'tipo' => 'residente'],
        ];

        foreach ($medicos as $datos) {
            Medico::firstOrCreate(['numero_empleado' => $datos['numero_empleado']], $datos);
        }

        $personal = [
            ['nombre' => 'Sandra', 'apellido_paterno' => 'Torres', 'apellido_materno' => 'Vega', 'rfc' => 'TOVS880505GH4', 'numero_empleado' => 'ARC-0001', 'cargo' => 'Encargada de Archivo', 'area' => 'Archivo Clínico', 'tipo' => 'base'],
            ['nombre' => 'Miguel', 'apellido_paterno' => 'Herrera', 'apellido_materno' => 'Paz', 'rfc' => 'HEPM920710IJ5', 'numero_empleado' => 'ARC-0002', 'cargo' => 'Auxiliar de Archivo', 'area' => 'Archivo Clínico', 'tipo' => 'base'],
        ];

        foreach ($personal as $datos) {
            PersonalArchivo::firstOrCreate(['numero_empleado' => $datos['numero_empleado']], $datos);
        }

        // Al crearse, cada derechohabiente genera su expediente automáticamente.
        $derechohabientes = [
            ['nombre' => 'María', 'apellido_paterno' => 'López', 'apellido_materno' => 'Hernández', 'rfc' => 'LOHM750812KL6', 'nss' => '12345678901', 'clave_identificacion' => 1001, 'clave_generada' => 'DH-1001', 'fecha_nacimiento' => '1975-08-12', 'genero' => 'Femenino', 'fecha_registro' => now()->toDateString()],
            ['nombre' => 'José', 'apellido_paterno' => 'Ramírez', 'apellido_materno' => 'Cruz', 'rfc' => 'RACJ680423MN7', 'nss' => '23456789012', 'clave_identificacion' => 1002, 'clave_generada' => 'DH-1002', 'fecha_nacimiento' => '1968-04-23', 'genero' => 'Masculino', 'fecha_registro' => now()->toDateString()],
            ['nombre' => 'Ana', 'apellido_paterno' => 'Flores', 'apellido_materno' => 'Díaz', 'rfc' => 'FODA901130OP8', 'nss' => '34567890123', 'clave_identificacion' => 1003, 'clave_generada' => 'DH-1003', 'fecha_nacimiento' => '1990-11-30', 'genero' => 'Femenino', 'fecha_registro' => now()->toDateString()],
        ];

        foreach ($derechohabientes as $datos) {
            DerechoHabiente::firstOrCreate(['nss' => $datos['nss']], $datos);
        }
    }
}
