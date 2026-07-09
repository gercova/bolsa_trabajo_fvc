<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricalReviewSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            [
                'title'       => 'Etapa 1: Creación y funcionamiento (1991 - 2000)',
                'description' => <<<TEXT
Con fecha de 01 de julio de 1990, se llevó a cabo una reunión de directores de todos los Centros Educativos del radio urbano del Distrito de Uchiza, para coordinar sobre la Creación del Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero", de Uchiza, según Acta de Asamblea, esta reunión fue convocada por el Licenciado Luís Viviano Hilario Vega, Jefe del Comité de Coordinación Educativa de Uchiza, en las Oficinas de dicha institución, que hoy en día se llama ADE - Uchiza, También acompañaron a esta reunión los especialistas del Comité de Coordinación Educativa (Actualmente Oficina de Coordinación Pedagógica - Uchiza), Prof. Heberto Angulo, especialista de alfabetización, Prof. Lily Hidalgo Reátegui, especialista en Educación Inicial, Prof. Liborio Ponce Saavedra, especialista en Educación Secundaria, tomando acuerdos en dicha reunión sobre la Creación del Instituto Superior Tecnológico "Francisco Vigo Caballero", así mismo, organizaron una Comisión para dirigirse a la Municipalidad Distrital de Uchiza, y coordinar esta gestión con el señor Alcalde, el Econ. Demetrio Díaz Guevara, el cual aceptó gustosamente iniciar esta gestión a su vez organiza otra Comisión para elaborar el Proyecto de Creación y organizar documentos para complementar el expediente; dicha Comisión estaba integrada por el Eco. Demetrio Díaz Guevara, Alcalde del Distrito de Uchiza, Lolo Soto Araujo, Gobernador del Distrito de Uchiza, Lic. Luís Viviano Hilario Vega, Jefe de la Oficina de Coordinación Educativa de Uchiza y el Prof. Juan Pablo Vela del Águila, Regidor de la Municipalidad de Uchiza.

Una vez elaborado el Proyecto de Creación del Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" de Uchiza, se hizo ingreso a la CORDESAM, en el mes de septiembre de 1990 y, luego se procedió a hacer el seguimiento correspondiente, hasta que el 08 de enero de 1991, con Decreto Ejecutivo Nº 05-91 –CR-SMLL, y el 21 de marzo de 1991, se crea el Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero", mediante el Decreto Ejecutivo Regional Nº 03-91-CR-GR-SMLL, refrendado por Resolución Presidencial Nº 19-91 – CORDESAM/T, con las Carreras Profesionales de Producción Agropecuaria y Enfermería Técnica, mientras las autoridades de la alta dirección del Ministerio de Educación autoricen oficialmente su funcionamiento acorde con el Decreto Supremo Nº 006-93-ED.

En el año 1994, con R.M Nº 0868-94-ED, de fecha 16 de noviembre de 1994, se autoriza en vía de regularización el reconocimiento y funcionamiento del Instituto de Educación Superiores Tecnológicos Público "Francisco Vigo Caballero" de Uchiza, con este documento queda regularizada la creación y el funcionamiento normal. Iniciando su funcionamiento administrativo y académico bajo la dirección del Ing. Edison Calvo Trujillo desde el año 1991.

Cuando inicia el funcionamiento administrativo y académico dicho Instituto Superior Tecnológico Público "Francisco Vigo Caballero" no contaba con un presupuesto asignado por el Estado, donde todos los docentes laboraron en forma ad honoren en el año 1991. En 1992 la Municipalidad Distrital de Uchiza, por intermedio de su Alcalde el Economista Demetrio Díaz Guevara, otorga un pago mensual mediante planilla en calidad de apoyo a los docentes, hasta el mes de octubre, y no siendo pagado los dos últimos meses de año por dificultades presupuestarias de la Municipalidad.

En el año 1993 la preocupación del Jefe del Comité de Coordinación Educativa el Profesor Luís Viviano Hilario Vega, y del nuevo Alcalde del Distrito de Uchiza, el Méd. Víctor Hugo Romero Gurrionero, hizo que tomaran decisiones para gestionar el presupuesto de pago al personal docente, administrativo y el personal de servicio del Instituto Superior Tecnológico "Francisco Vigo Caballero", y del Instituto Superior Pedagógico Estatal de Uchiza, habiéndose logrado esta gestión en la ciudad de Moyobamba con aprobación del señor Presidente del Gobierno Regional.

La Dirección Regional de Educación por intermedio de la Unidad de Planeamiento Educativo de Moyobamba otorga el presupuesto con una Resolución Directoral Regional Nº 0134, de fecha del 08 de abril de 1993, se transfiere las plazas presupuestadas de las Naciones Unidas de Servicios Educativos de Moyobamba, Rioja, Lamas, Bella Vista, Huallaga y Tarapoto, que conforman 16 plazas, 8 plazas para el instituto tecnológico y pedagógico.

De agosto 1996 a febrero de 1998, se encarga la dirección a la Blga. Delia Esperanza Portella Melgarejo, durante su período se crea la Especialidad de Secretariado Ejecutivo con Resolución Directoral Nº 0091-97-ED, de fecha 10 de marzo de 1997. De marzo 1998 a abril 1998 se encarga la dirección al Med. Vet. José Luis Escalante Mosquera. De mayo 1998 a Julio del 2001 se le encarga la dirección al Lic. Luis Viviano Hilario Vega. En su periodo se apertura la Especialidad de Laboratorio Clínico, lo cual se dio mediante Resolución Directoral Nº 483-2000-ED, de fecha 22 de mayo del 2000, a cambio de la especialidad de Secretariado Ejecutivo.
TEXT,
                'start_year'  => 1991,
                'end_year'    => 2000,
                'order'       => 1,
            ],
            [
                'title'       => 'Etapa 2: Consolidación y crecimiento (2001 - 2015)',
                'description' => <<<TEXT
De agosto 2001 a Julio 2002 se le encarga la dirección a la Lic. Enf. Georgina Antonia Bueno Huayanay, continuando con los trabajos académicos y administrativos. De agosto 2002 a setiembre 2002 se le encarga la dirección al Lic. Wilmer W. Justiniano Sebastián. De octubre 2002 a abril 2003 se le encarga la dirección a la Obst. Danni Castillo Santillán, quien ha continuado con la labor administrativa y académica. De mayo 2003 a marzo 2004 retoma nuevamente la dirección el Lic. Wilmer W. Justiniano Sebastián, quien promovió el traslado definitivo de todos los estamentos a su local central. De abril 2004 a agosto 2004 se hace cargo de la dirección el Ing. José Santos Baldera Suyón, quien desarrolla actividades administrativas y académicas, así mismo, durante este tiempo se ha realizado el vaciado del techo aligerado de la primera planta del pabellón de tres aulas. De setiembre 2004 a diciembre 2004 se le encarga al Ing. Hernrick Ramírez Ramírez, desarrollando actividades administrativas y académicas.

De enero 2005 a diciembre 2010 se encarga la dirección al Ing. Manuel Adolfo Guerra Meléndez quien promueve el trabajo en equipo, así mismo, el Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" de Uchiza, fue Revalidado con Resolución Directoral N° 0298-2006-ED, de fecha 19 de abril del 2006, las carreras de Agropecuaria, Enfermería Técnica y Laboratorio Clínico y en ese mismo año se crea la carrera de Computación e Informática, con Resolución Ministerial Nº 0359-2006-ED y con Resolución Directoral Nº 0166-2007-ED del 17 de abril del 2007, se reapertura la carrera de Secretariado Ejecutivo. En el año 2008 se elabora el perfil del proyecto de infraestructura titulado "Mejoramiento de la Oferta Educativa en el Instituto Superior Tecnológico Francisco Vigo Caballero – Uchiza - Tocache – San Martin", con código SNIP Nº 85718 y en el 2009 se elabora el expediente técnico del mencionado proyecto, iniciando las gestiones para su financiamiento a partir de esa fecha, en este mismo año se inicia con el nuevo Diseño Curricular Básico del sistema modular todas las especialidades (Enfermería Técnica, Producción Agropecuaria, Secretariado Ejecutivo y Computación e Informática), así mismo, con Resolución Directoral N° 0341-2010-ED del 06 de abril del 2010, se crea la carrera de Medio Ambiente y Recursos Naturales, bajo el sistema lineal (por asignatura). Por otro lado, durante el período 2005 al 2010 actuando como director el Ing, Manuel Adolfo Guerra Meléndez, se adquiere 12.0 Hás de terreno agrícola en el sector de Gavilán – Paraíso y se instala el cultivo de palma aceitera y se cuenta con el Centro de Prácticas de San Pedro de Chonta que viene funcionando con dos promociones de la especialidad de Producción Agropecuaria. Además, se adquiere una unidad móvil (auto) en calidad de sesión de uso.
TEXT,
                'start_year'  => 2001,
                'end_year'    => 2015,
                'order'       => 2,
            ],
            [
                'title'       => 'Etapa 3: Modernización y Licenciamiento (2016 - actualidad)',
                'description' => <<<TEXT
Del 2016 al 2019, el Mg. Esmirio Erotides Castillo Gonzales asume la dirección general, durante este periodo se logra la adecuación de los programas de estudio de Producción Agropecuaria, Enfermería Técnica y Computación e Informática (que fue adecuado a Administración de Redes y Comunicaciones) y se inician los trámites para el licenciamiento institucional.

Del 2020 – 2021, ocupa el cargo de Director General el Mg. Esmirio Erotides Castillo Gonzales, cuya gestión fue limitado debido a la emergencia sanitaria del COVID-19 y las cuarentenas dispuestas por el gobierno nacional; sin embargo, se logró la adecuación de los programas de estudio de Producción Agropecuaria, Enfermería Técnica y Computación e Informática que fue adecuado a Administración de Redes y Comunicaciones.

Desde el 2022 al 2023, asume la dirección el Mg. José Santos Baldera Suyon, cuyas acciones están abocados en el mejoramiento de la infraestructura productiva (ampliación del cultivo de palma aceitera) del Instituto, modernización de los laboratorios de los programas de estudio, así como, se ha logrado la adecuación del programa de estudios de estudio de Secretariado Ejecutivo a Asistencia Administrativa e inicio con el proceso de la adecuación del programa de estudios de Medio Ambiente y Recursos Naturales para el proceso de licenciamiento.

Del 2024 al 2025, se hace cargo de la Dirección General el Dr. Modesto Paucar Serpa, quien retoma el proyecto de infraestructura, para la ejecución de este ansiado proyecto ya que ello permitirá cerrar brechas de infraestructura y equipamiento para el proceso de licenciamiento del Instituto; sin embargo, en este periodo se viene adecuando ambientes para los laboratorios y talleres, así como, el mejoramiento de los ambientes Tipo G para el desarrollo de prácticas del programa de estudios de Manejo Forestal y Producción Agropecuaria con recursos propios. También, en este periodo se adecuo al sistema modular el programa de estudios de Medio Ambiente y Recursos Naturales al programa de estudios de Manejo Forestal y el programa de estudios de Producción Agropecuaria, que inició sus actividades lectivas el 2025. En el 2024 al Instituto se le incorpora 08 plazas de gestores, también, en el 2025 mediante el nombramiento extraordinario, se han nombrado 07 plazas orgánicas en la etapa institucional y 05 plazas orgánicas en la etapa nacional, habiendo quedado 02 plazas orgánicas por cobertura. Por otro lado, se viene mejorando la infraestructura productiva, concluyéndose con la ampliación del cultivo de palma aceitera en 12 hás.

En el 2026, el Mg. Teodorico Ganoza Medina, vía concurso nacional, asume la Dirección General del IESTP "Francisco Vigo Caballero", iniciando sus actividades administrativas a partir del 02 de enero del 2026, enfocándose en el mejoramiento de las Condiciones Básicas de Calidad (CBC) para el proceso de licenciamiento del Instituto.

En resumen, en la actualidad el Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" de Uchiza, cuenta con 05 Programas de Estudios (Producción Agropecuaria, Enfermería Técnica, Administración de Redes y Comunicaciones, Asistencia Administrativa y Manejo Forestal) con un promedio de 310 estudiantes, además, se tiene 34 docentes y 04 personal administrativo nombrado en plaza orgánica, 01 personal administrativo contratado en plaza orgánica, 04 personal administrativo contratado por locación de servicios  y 04 personal administrativo de apoyo remunerado con recursos propios. Así mismo, se cuenta con 2.0 Hás en el sector de Barro Blanco, donde se tiene la infraestructura con aulas y laboratorios, auditorio, oficinas administrativas, módulos de crianza de animales mayores y menores, así como, estanques piscícolas, módulos de lombricultura, viveros forestal y agroforestal, áreas de cultivos anuales y módulo de hortalizas. Así mismo, se cuenta con 115.0 Hás de terreno en proceso de reforestación en el sector de Barro Blanco, Pucarrumi y Peso denominado "Fundo Vista Alegre" y 42.0 Hás de terreno agrícola en el sector Gavilán – Paraíso denominado "Fundo Gavilán" con 12.0 hás de palma aceitera en producción y 12 hás en proceso de crecimiento, también se tiene 3.0 Hás de cacao y 3.0 Hás de plátano. Todos estos predios debidamente acreditados con título de propiedad.

Finalmente, cabe mencionar que, a pesar de ser una Institución Educativa joven con 35 años de actividad académica, la comunidad de Uchiza y demás distritos cercanos, son fieles testigos de cómo ha ido creciendo, tanto cuantitativamente como cualitativamente el servicio que presta y su constante participación con miras al desarrollo integral de la comunidad del distrito de Uchiza, provincia de Tocache y región de San Martin.
TEXT,
                'start_year'  => 2016,
                'end_year'    => null,
                'order'       => 3,
            ],
        ];

        foreach ($stages as $stage) {
            DB::table('historical_reviews')->insert([
                'title'       => $stage['title'],
                'description' => $stage['description'],
                'start_year'  => $stage['start_year'],
                'end_year'    => $stage['end_year'],
                'order'       => $stage['order'],
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}