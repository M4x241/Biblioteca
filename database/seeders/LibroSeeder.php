<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Libro;

class LibroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Libros con datos realistas
        $librosReales = [
            [
                'titulo' => 'Cien años de soledad',
                'autor' => 'Gabriel García Márquez',
                'categoria' => 'Ficción',
                'sinopsis' => 'La obra maestra del realismo mágico que narra la historia de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.',
                'imagen' => 'images-libros/cien_anos_de_soledad.jpg',
            ],
            [
                'titulo' => 'Don Quijote de la Mancha',
                'autor' => 'Miguel de Cervantes',
                'categoria' => 'Ficción',
                'sinopsis' => 'Las aventuras del ingenioso hidalgo Don Quijote y su escudero Sancho Panza, considerada una de las obras cumbre de la literatura universal.',
                'imagen' => 'images-libros/don_quijote_de_la_mancha.jpg',
            ],
            [
                'titulo' => '1984',
                'autor' => 'George Orwell',
                'categoria' => 'Ficción',
                'sinopsis' => 'Una distopía que presenta un futuro totalitario donde el Gran Hermano todo lo ve y controla, explorando temas de vigilancia, manipulación y libertad.',
                'imagen' => 'images-libros/1984.jpg',
            ],
            [
                'titulo' => 'El Principito',
                'autor' => 'Antoine de Saint-Exupéry',
                'categoria' => 'Ficción',
                'sinopsis' => 'Un cuento poético y filosófico sobre un pequeño príncipe que viaja por el universo visitando diversos planetas, explorando temas de amor, amistad y soledad.',
                'imagen' => 'images-libros/el_principito.jpg',
            ],
            [
                'titulo' => 'La Ruta de la Plata',
                'autor' => 'Clara López Beltrán',
                'categoria' => 'Historia',
                'sinopsis' => 'Este libro traza el recorrido, paso a paso, de dos caminos que vinculan las tierras altas andinas con el litoral del Pacífico',
                'imagen' => 'images-libros/la_ruta_de_la_plata.jpg',
            ],
            [
                'titulo' => 'El Arte de la Guerra',
                'autor' => 'Sun Tzu',
                'categoria' => 'Educación',
                'sinopsis' => 'Este libro traza el recorrido, paso a paso, de dos caminos que vinculan las tierras altas andinas con el litoral del Pacífico..',
                'imagen' => 'images-libros/el_arte_de_la_guerra.jpg',
            ],
            [
                'titulo' => 'La Villa Imperial, La 25.04.1716',
                'autor' => 'Álvaro Cuadros',
                'categoria' => 'Historia',
                'sinopsis' => 'una descripción social y urbana a partir de un estudio del lienzo “Entrada del virrey arzobispo Morcillo a Potosí” de 1716.',
                'imagen' => 'images-libros/la_villa_imperial.jpg',
            ],
            [
                'titulo' => 'Potosí Global',
                'autor' => 'Rossana Barragán',
                'categoria' => 'Historia',
                'sinopsis' => 'La magnitud y continuidad de los flujos de plata de Potosí que contribuyeron a la primera globalización originaron, paralelamente, una amplia circulación de textos e imágenes.',
                'imagen' => 'images-libros/potosi_global.jpg',
            ],
            [
                'titulo' => 'Clean Code',
                'autor' => 'Robert C. Martin',
                'categoria' => 'Tecnología',
                'sinopsis' => 'Guía sobre las mejores prácticas para escribir código limpio, mantenible y eficiente, esencial para desarrolladores de software.',
                'imagen' => 'images-libros/clean_code.jpg',
            ],
            [
                'titulo' => 'El Código Da Vinci',
                'autor' => 'Dan Brown',
                'categoria' => 'Ficción',
                'sinopsis' => 'Thriller que combina historia del arte, símbolos religiosos y teorías de conspiración en una trama vertiginosa que recorre Europa.',
                'imagen' => 'images-libros/el_codigo_da_vinci.jpg',
            ],
            [
                'titulo' => 'Catre de Fierro',
                'autor' => 'Spedding',
                'categoria' => 'Ficción',
                'sinopsis' => 'es una extensa y compleja relación de la Bolivia de la segunda mitad del siglo XX que destaca por la maestría con que la autora maneja sus personajes y tramas.',
                'imagen' => 'images-libros/catre_de_fierro.jpg',
            ],
            [
                'titulo' => 'Alicia en el País de las Maravillas',
                'autor' => 'Lewis Carroll',
                'categoria' => 'Ficción',
                'sinopsis' => 'Las aventuras de una niña llamada Alicia que cae por una madriguera de conejo y descubre un mundo fantástico lleno de personajes peculiares y situaciones absurdas.',
                'imagen' => 'images-libros/alicia.jpg',
            ],
            [
                'titulo' => 'Moby Dick',
                'autor' => 'Herman Melville',
                'categoria' => 'Ficción',
                'sinopsis' => 'La historia de la caza de una ballena blanca y la obsesión del capitán Ahab por capturarla.',
                'imagen' => 'images-libros/moby_dick.jpg',
            ],
            [
                'titulo' => 'Romeo y Julieta',
                'autor' => 'William Shakespeare',
                'categoria' => 'Romance',
                'sinopsis' => 'La trágica historia de dos jóvenes amantes de familias rivales en Verona.',
                'imagen' => 'images-libros/romeo.jpg',
            ],
            [
                'titulo' => 'Patrones de Diseño',
                'autor' => 'Erich Gamma',
                'categoria' => 'Tecnología',
                'sinopsis' => 'Un libro fundamental sobre patrones de diseño en programación orientada a objetos, que ofrece soluciones probadas a problemas comunes.',
                'imagen' => 'images-libros/patrones_de_disenio.jpg',
            ],
            [
                'titulo' => 'Manual de ciencia de datos de Python',
                'autor' => 'Jake VanderPlas',
                'categoria' => 'Tecnología',
                'sinopsis' => 'Una guía completa para el análisis de datos en Python, cubriendo bibliotecas como NumPy, Pandas y Matplotlib.',
                'imagen' => 'images-libros/python_data_science.jpg',
            ],
            [
                'titulo' => 'Como Funciona Linux',
                'autor' => 'Brian Ward',
                'categoria' => 'Tecnología',
                'sinopsis' => 'Una introducción detallada al sistema operativo Linux, explicando su arquitectura y funcionamiento interno.',
                'imagen' => 'images-libros/how_works_linux.jpg',
            ],
            [
                'titulo' => 'Linux para hackers',
                'autor' => 'OccupyTheWeb',
                'categoria' => 'Tecnología',
                'sinopsis' => 'Una guía práctica para usar Linux en pruebas de penetración y hacking ético.',
                'imagen' => 'images-libros/linux_hackers.jpg',
            ],
            [
                'titulo' => 'Pensar Como Programador',
                'autor' => 'V. Anton Spraul',
                'categoria' => 'Tecnología',
                'sinopsis' => 'Un libro que enseña a desarrollar habilidades de resolución de problemas y pensamiento lógico para la programación.',
                'imagen' => 'images-libros/pensar_como_programador.jpg',
            ],
            [
                'titulo' => 'El Extranjero',
                'autor' => 'Albert Camus',
                'categoria' => 'Filosofía',
                'sinopsis' => 'Una novela existencialista que explora la absurdidad de la vida a través de la historia de Meursault, un hombre indiferente al mundo que lo rodea.',
                'imagen' => 'images-libros/el_extranjero.jpg',
            ],
            [
                'titulo' => 'El retrato de Dorian Gray',
                'autor' => 'Oscar Wilde',
                'categoria' => 'Ficción',
                'sinopsis' => 'Un joven vende su alma por conservar su juventud eterna mientras su retrato envejece en su lugar.',
                'imagen' => 'images-libros/dorian_gray.jpg',
            ],
            [
                'titulo' => 'La Insoportable Levedad del Ser',
                'autor' => 'Milan Kundera',
                'categoria' => 'Filosofía',
                'sinopsis' => 'Una profunda reflexión sobre el amor, la libertad y la existencia en la Europa comunista.',
                'imagen' => 'images-libros/el_ser.jpg',
            ],
            [
                'titulo' => 'Meditaciones',
                'autor' => 'Marco Aurelio',
                'categoria' => 'Filosofía',
                'sinopsis' => 'Reflexiones personales del emperador romano sobre la virtud, la serenidad y la naturaleza humana.',
                'imagen' => 'images-libros/meditaciones.jpg',
            ],
        ];

        foreach ($librosReales as $libro) {
            Libro::create($libro);
        }

        // Generar libros adicionales con el factory
        // Libro::factory()->count(10)->create();
    }
}
