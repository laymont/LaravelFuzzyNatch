# Changelog

All notable changes to the `laravel-fuzzy-match` package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.2] - 2026-05-07

### Cambiado
- Actualizado `illuminate/support` a `^12.0|^13.0` para soporte de Laravel 12 y 13.
- Actualizado `orchestra/testbench` a `^10.0|^11.0` para compatibilidad con ambas versiones.
- Actualizado documentación para reflejar compatibilidad con Laravel 12 y 13 y requisitos de PHP.

## [1.0.1] - 2026-05-07

### Cambiado
- Actualizado `illuminate/support` a `^13.0` para soporte de Laravel 13.

## [1.0.0] - 2026-05-07

### Nuevo
- Estructura base del paquete.
- Algoritmos: Levenshtein, SimilarText, JaroWinkler.
- FuzzyMatchService con orquestación de algoritmos.
- FuzzyMatchServiceProvider para integración Laravel.
- Facade FuzzyMatch para acceso conveniente.
- Configuración publishable.
- Tests unitarios para algoritmos y servicio.
