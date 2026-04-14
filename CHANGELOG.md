# Changelog

## [Unreleased] - 2026-04-14

### Added
- Relatório: filtros por status, forma de pagamento, categoria, vencimento (intervalo) e valor (min/max).
- Export CSV: botão para exportar relatório com filtros aplicados.
- Script `scripts/generate_report.php` para gerar CSV via CLI.
- Badges visuais na UI mostrando filtros ativos.

### Changed
- `ReportController`: validação e construção de query filtrada; método `export` para CSV.
- `resources/views/reports/index.blade.php`: UI de filtros e botão de export.

### Notes
- Commit inicial do projeto contendo migrações, seeders, modelos, controllers e views.
