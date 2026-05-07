# laymont/laravel-fuzzy-match

Laravel package for fuzzy matching (string similarity) using Levenshtein, SimilarText, and Jaro-Winkler algorithms.

## Objetivo

Este paquete proporciona algoritmos de coincidencia aproximada de strings (fuzzy matching) para detectar nombres similares y evitar duplicados en entidades como consignatarios y otras entidades en sistemas de control de contenedores marítimos.

## Características

- **Algoritmos implementados**:
  - Levenshtein (distancia de edición) - Prioridad Alta
  - SimilarText (porcentaje de similitud) - Prioridad Media
  - Jaro-Winkler (para nombres con transposiciones) - Prioridad Baja
- **Facade conveniente** para uso simple
- **Configuración flexible** para ajustar algoritmos y umbrales
- **Sin dependencias externas** (usa funciones nativas de PHP donde sea posible)
- **Compatible con Laravel 13+ y PHP 8.2+**

## Instalación

```bash
composer require laymont/laravel-fuzzy-match
```

## Configuración

Publica la configuración:

```bash
php artisan vendor:publish --provider="Laymont\FuzzyMatch\FuzzyMatchServiceProvider"
```

Esto creará `config/fuzzy-match.php` con las siguientes opciones:

```php
return [
    // Algoritmo por defecto: 'levenshtein', 'similar_text', 'jaro_winkler'
    'default_algorithm' => env('FUZZY_MATCH_DEFAULT_ALGORITHM', 'levenshtein'),

    // Umbral para determinar similitud
    // - Para Levenshtein: distancia máxima (menor es mejor)
    // - Para algoritmos de similitud: puntuación mínima (mayor es mejor)
    'threshold' => env('FUZZY_MATCH_THRESHOLD', 3),

    // Si el matching debe ser case sensitive
    'case_sensitive' => env('FUZZY_MATCH_CASE_SENSITIVE', false),

    // Algoritmos habilitados
    'algorithms' => [
        'levenshtein' => true,
        'similar_text' => true,
        'jaro_winkler' => false,
    ],
];
```

## Uso

### Facade

```php
use Laymont\FuzzyMatch\Facades\FuzzyMatch;

// Buscar nombres similares
$similar = FuzzyMatch::findSimilar('MAERSK LINE', [
    'algorithm' => 'levenshtein',
    'threshold' => 3,
    'case_sensitive' => false,
]);

// Resultado esperado:
// [
//     ['id' => 0, 'name' => 'MAERSK LINE', 'distance' => 0],
//     ['id' => 1, 'name' => 'MAERSK LINES', 'distance' => 1],
// ]

// Calcular distancia específica
$distance = FuzzyMatch::calculateDistance('MAERSK LINE', 'MAERSK LINES', 'levenshtein');
// Resultado: 1
```

### Service

```php
use Laymont\FuzzyMatch\Services\FuzzyMatchService;

$service = new FuzzyMatchService();
$results = $service->findSimilar('MAERSK LINE', [
    'algorithm' => 'levenshtein',
    'threshold' => 3,
]);
```

### Uso en Form Request (Ayaguna)

```php
use Laymont\FuzzyMatch\Facades\FuzzyMatch;
use Illuminate\Validation\Rule;

public function rules()
{
    $name = $this->input('name');

    $similar = FuzzyMatch::findSimilar($name, [
        'threshold' => 3,
    ]);

    return [
        'name' => [
            'required',
            'string',
            Rule::unique('consignees')->ignore($this->id),
            function ($attribute, $value, $fail) use ($similar) {
                if (!empty($similar)) {
                    $fail("Existe un consignatario similar: {$similar[0]['name']}");
                }
            },
        ],
    ];
}
```

## Algoritmos

### Levenshtein (Prioridad Alta)

- **Descripción:** Distancia de edición entre dos strings
- **PHP nativo:** `levenshtein()`
- **Mide:** Inserciones, eliminaciones, sustituciones
- **Uso:** Detección de errores tipográficos leves

### SimilarText (Prioridad Media)

- **Descripción:** Porcentaje de similitud
- **PHP nativo:** `similar_text()`
- **Ventaja:** Más rápido pero menos preciso
- **Uso:** Detección rápida de similitudes

### Jaro-Winkler (Prioridad Baja)

- **Descripción:** Para nombres con transposiciones
- **Implementación:** Manual (no nativo en PHP)
- **Ventaja:** Más preciso para nombres
- **Uso:** Detección de nombres con letras intercambiadas

## Compatibilidad

- PHP: `^8.2`
- Laravel: `^13.0`

## Testing

El paquete incluye tests unitarios para cada algoritmo y el servicio principal.

```bash
./vendor/bin/pest
```

## Contribución

1. Haz un fork del repositorio.
2. Crea una rama: `git checkout -b feature/nueva-funcionalidad`.
3. Realiza cambios y hace commit: `git commit -m "Agregando..."`.
4. Sube: `git push origin feature/nueva-funcionalidad`.
5. Abre un Pull Request.

## Donaciones

Si encuentras útil este paquete y deseas apoyar su desarrollo y mantenimiento, puedes considerar hacer una donación.

### Zinli

- **ID de usuario:** 3-002-58546608-36
- **Recargar:** https://recargas.zinli.com/4nVRQUniFdK8DBfPzzfyzR

### Visa Prepagada Zinli

- **Número:** 4850460061276928

### Binance Pay

- **Binance Pay ID:** 206414132

¡Gracias por tu apoyo!

## Licencia

MIT.
