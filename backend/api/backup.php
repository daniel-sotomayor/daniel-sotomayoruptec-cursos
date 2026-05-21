<?php
/**
 * UPTEC - API Endpoints de Respaldo (Backup)
 * Exportar base de datos a SQL, exportar tablas especificas
 * Solo Administrador
 */

// ============================================
// GET /backend/api/api.php?endpoint=backup
// Realizar respaldo completo de la base de datos
// Descarga como archivo SQL
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'backup') return null;

    $tablas = $_GET['tablas'] ?? 'all';
    $formato = $_GET['formato'] ?? 'sql';

    $db = getDB();
    $user = Auth::user();

    try {
        $dbName = 'uptec_cursos';
        $backupFile = 'uptec_backup_' . date('Y-m-d_H-i-s') . '.sql';

        // Obtener lista de tablas
        if ($tablas === 'all') {
            $stmt = $db->query("SHOW TABLES");
            $tableList = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $tableList = explode(',', $tablas);
        }

        $output = "--\n";
        $output .= "-- UPTEC - Respaldo de Base de Datos\n";
        $output .= "-- Universidad Politecnica Territorial de Caracas Mariscal Sucre\n";
        $output .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Generado por: " . ($user['nombre'] ?? 'Sistema') . " " . ($user['apellidos'] ?? '') . "\n";
        $output .= "--\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tableList as $table) {
            $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

            $output .= "--\n-- Estructura de tabla: {$table}\n--\n\n";

            // DROP TABLE
            $output .= "DROP TABLE IF EXISTS `{$table}`;\n";

            // CREATE TABLE
            $stmt = $db->query("SHOW CREATE TABLE {$table}");
            $createTable = $stmt->fetch();
            $output .= $createTable['Create Table'] . ";\n\n";

            // Datos
            $stmt = $db->query("SELECT * FROM {$table}");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                $output .= "--\n-- Datos de tabla: {$table}\n--\n\n";

                // Obtener columnas
                $columns = array_keys($rows[0]);
                $columnStr = '`' . implode('`, `', $columns) . '`';

                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = $db->quote($value);
                        }
                    }
                    $output .= "INSERT INTO `{$table}` ({$columnStr}) VALUES (" . implode(', ', $values) . ");\n";
                }

                $output .= "\n";
            }
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Registrar actividad
        Auth::logActivity($user['id'], $user['nombre'] . ' ' . $user['apellidos'], 'BACKUP', 'database', null, null, [
            'tablas' => $tablas,
            'formato' => $formato,
            'archivo' => $backupFile
        ]);

        // Enviar como descarga
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $backupFile . '"');
        header('Content-Length: ' . strlen($output));
        header('Cache-Control: no-cache, must-revalidate');

        echo $output;
        exit;

    } catch (PDOException $e) {
        error_log("[UPTEC] Error en backup: " . $e->getMessage());
        http_response_code(500);
        return jsonResponse(false, null, 'Error al generar respaldo');
    }

}, [requireAdmin()], 'backup');

// ============================================
// GET /backend/api/api.php?endpoint=tablas
// Listar tablas disponibles para backup
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'tablas') return null;

    try {
        $db = getDB();
        $stmt = $db->query("SHOW TABLES");
        $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Obtener conteo de registros por tabla
        $result = [];
        foreach ($tablas as $tabla) {
            $stmt = $db->query("SELECT COUNT(*) FROM {$tabla}");
            $count = $stmt->fetchColumn();
            $result[] = ['nombre' => $tabla, 'registros' => (int)$count];
        }

        return jsonResponse(true, ['tablas' => $result]);

    } catch (PDOException $e) {
        http_response_code(500);
        return jsonResponse(false, null, 'Error al obtener tablas');
    }

}, [requireAdmin()], 'tablas');
