<?php
/**
 * UPTEC - API Endpoints de Reportes
 * Estadisticas, informes, auditoria, resumen academico
 */

// ============================================
// GET /backend/api/api.php?endpoint=resumen
// Resumen del dashboard por rol
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'resumen') return null;

    $user = Auth::user();

    try {
        $db = getDB();
        $resumen = [];

        // Datos comunes
        $resumen['usuario'] = $user;

        if ($user['rol'] === 'Administrador') {
            // Totales generales
            $stmt = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Participante' AND activo = 1");
            $resumen['total_estudiantes'] = $stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Facilitador' AND activo = 1");
            $resumen['total_facilitadores'] = $stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Analista' AND activo = 1");
            $resumen['total_analistas'] = $stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM cursos WHERE activo = 1");
            $resumen['total_cursos'] = $stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM inscripciones WHERE estado != 'Abandonado'");
            $resumen['total_inscripciones'] = $stmt->fetchColumn();

            // Cursos por estado
            $stmt = $db->query("
                SELECT estado, COUNT(*) AS cantidad
                FROM cursos WHERE activo = 1 GROUP BY estado
            ");
            $resumen['cursos_por_estado'] = $stmt->fetchAll();

            // Cursos recientes
            $stmt = $db->query("
                SELECT c.*, CONCAT(f.nombre, ' ', f.apellidos) AS facilitador
                FROM cursos c
                LEFT JOIN usuarios f ON c.facilitador_id = f.id
                WHERE c.activo = 1
                ORDER BY c.creado_en DESC LIMIT 5
            ");
            $resumen['cursos_recientes'] = $stmt->fetchAll();

            // Usuarios recientes
            $stmt = $db->query("
                SELECT id, cedula, CONCAT(nombre, ' ', apellidos) AS nombre_completo, rol, creado_en
                FROM usuarios ORDER BY creado_en DESC LIMIT 5
            ");
            $resumen['usuarios_recientes'] = $stmt->fetchAll();

            // Logs recientes
            $stmt = $db->query("
                SELECT usuario_nombre, accion, tabla_afectada, fecha_hora
                FROM logs_actividad ORDER BY fecha_hora DESC LIMIT 10
            ");
            $resumen['actividad_reciente'] = $stmt->fetchAll();

            // ============================================
            // NUEVAS METRICAS: Estudiantes y Cursos por Area
            // ============================================

            // Estudiantes por area (carrera)
            $stmt = $db->query("
                SELECT 
                    u.area,
                    COUNT(*) AS total_estudiantes
                FROM usuarios u
                WHERE u.rol = 'Participante' 
                  AND u.activo = 1
                  AND u.area IS NOT NULL
                GROUP BY u.area
                ORDER BY total_estudiantes DESC
            ");
            $resumen['estudiantes_por_area'] = $stmt->fetchAll();

            // Cursos con estudiantes por area
            $stmt = $db->query("
                SELECT 
                    c.area,
                    c.id,
                    c.codigo,
                    c.nombre,
                    c.estado,
                    COUNT(i.id) AS total_estudiantes,
                    CONCAT(f.nombre, ' ', f.apellidos) AS facilitador
                FROM cursos c
                LEFT JOIN inscripciones i ON c.id = i.curso_id AND i.estado != 'Abandonado'
                LEFT JOIN usuarios f ON c.facilitador_id = f.id
                WHERE c.activo = 1
                  AND c.area IS NOT NULL
                GROUP BY c.id, c.area, c.codigo, c.nombre, c.estado, f.nombre, f.apellidos
                ORDER BY c.area, total_estudiantes DESC
            ");
            $resumen['cursos_con_estudiantes_por_area'] = $stmt->fetchAll();

            // Top 5 cursos con mas estudiantes
            $stmt = $db->query("
                SELECT 
                    c.codigo,
                    c.nombre,
                    c.area,
                    COUNT(i.id) AS total_estudiantes,
                    CONCAT(f.nombre, ' ', f.apellidos) AS facilitador
                FROM cursos c
                LEFT JOIN inscripciones i ON c.id = i.curso_id AND i.estado != 'Abandonado'
                LEFT JOIN usuarios f ON c.facilitador_id = f.id
                WHERE c.activo = 1
                GROUP BY c.id, c.codigo, c.nombre, c.area, f.nombre, f.apellidos
                HAVING total_estudiantes > 0
                ORDER BY total_estudiantes DESC
                LIMIT 5
            ");
            $resumen['top_cursos'] = $stmt->fetchAll();
        }

        if ($user['rol'] === 'Analista') {
            $stmt = $db->query("SELECT COUNT(*) FROM cursos WHERE activo = 1");
            $resumen['total_cursos'] = $stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM cursos WHERE estado = 'Planificado' AND activo = 1");
            $resumen['cursos_planificados'] = $stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Facilitador' AND activo = 1");
            $resumen['total_facilitadores'] = $stmt->fetchColumn();

            $stmt = $db->query("
                SELECT COUNT(*) FROM inscripciones i
                JOIN cursos c ON i.curso_id = c.id WHERE c.activo = 1
            ");
            $resumen['total_inscripciones'] = $stmt->fetchColumn();

            // Cursos por area
            $stmt = $db->query("
                SELECT area, COUNT(*) AS cantidad FROM cursos WHERE activo = 1 AND area IS NOT NULL GROUP BY area
            ");
            $resumen['cursos_por_area'] = $stmt->fetchAll();

            // Cursos sin facilitador
            $stmt = $db->query("
                SELECT id, codigo, nombre, estado FROM cursos
                WHERE facilitador_id IS NULL AND activo = 1 AND estado = 'Planificado'
            ");
            $resumen['cursos_sin_facilitador'] = $stmt->fetchAll();
        }

        if ($user['rol'] === 'Facilitador') {
            $stmt = $db->prepare("SELECT COUNT(*) FROM cursos WHERE facilitador_id = ? AND activo = 1");
            $stmt->execute([$user['id']]);
            $resumen['mis_cursos'] = $stmt->fetchColumn();

            $stmt = $db->prepare("
                SELECT COUNT(*) FROM inscripciones i
                JOIN cursos c ON i.curso_id = c.id
                WHERE c.facilitador_id = ? AND i.estado != 'Abandonado'
            ");
            $stmt->execute([$user['id']]);
            $resumen['mis_estudiantes'] = $stmt->fetchColumn();

            $stmt = $db->prepare("
                SELECT c.*,
                       (SELECT COUNT(*) FROM inscripciones WHERE curso_id = c.id AND estado != 'Abandonado') AS total_inscritos
                FROM cursos c
                WHERE c.facilitador_id = ? AND c.activo = 1
                ORDER BY c.fecha_inicio DESC
            ");
            $stmt->execute([$user['id']]);
            $resumen['cursos_activos'] = $stmt->fetchAll();
        }

        if ($user['rol'] === 'Participante') {
            $stmt = $db->prepare("SELECT COUNT(*) FROM inscripciones WHERE usuario_id = ? AND estado != 'Abandonado'");
            $stmt->execute([$user['id']]);
            $resumen['mis_cursos'] = $stmt->fetchColumn();

            $stmt = $db->prepare("
                SELECT COUNT(*) FROM inscripciones WHERE usuario_id = ? AND estado = 'Completado'
            ");
            $stmt->execute([$user['id']]);
            $resumen['cursos_completados'] = $stmt->fetchColumn();

            $stmt = $db->prepare("
                SELECT ROUND(AVG(nota_final), 2) FROM inscripciones
                WHERE usuario_id = ? AND nota_final IS NOT NULL
            ");
            $stmt->execute([$user['id']]);
            $resumen['promedio_general'] = $stmt->fetchColumn() ?: 0;

            $stmt = $db->prepare("
                SELECT i.*, c.codigo, c.nombre, c.area
                FROM inscripciones i
                JOIN cursos c ON i.curso_id = c.id
                WHERE i.usuario_id = ? AND i.estado != 'Abandonado'
                ORDER BY i.fecha_inscripcion DESC
            ");
            $stmt->execute([$user['id']]);
            $resumen['inscripciones'] = $stmt->fetchAll();
        }

        return jsonResponse(true, $resumen);

    } catch (PDOException $e) {
        error_log("[UPTEC] Error en resumen: " . $e->getMessage());
        http_response_code(500);
        return jsonResponse(false, null, 'Error al obtener resumen');
    }

}, [requireAuth()], 'resumen');

// ============================================
// GET /backend/api/api.php?endpoint=estadisticas
// Estadisticas globales (Admin)
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'estadisticas') return null;

    try {
        $db = getDB();
        $stats = [];

        // Promedio general de notas
        $stmt = $db->query("SELECT ROUND(AVG(nota_final), 2) FROM inscripciones WHERE nota_final IS NOT NULL");
        $stats['promedio_general'] = $stmt->fetchColumn() ?: 0;

        // Tasa de aprobacion
        $stmt = $db->query("SELECT COUNT(*) FROM inscripciones WHERE nota_final IS NOT NULL AND nota_final >= 10");
        $aprobados = $stmt->fetchColumn();
        $stmt = $db->query("SELECT COUNT(*) FROM inscripciones WHERE nota_final IS NOT NULL");
        $total = $stmt->fetchColumn();
        $stats['tasa_aprobacion'] = $total > 0 ? round(($aprobados / $total) * 100, 1) : 0;

        // Cursos por area
        $stmt = $db->query("
            SELECT area, COUNT(*) AS total FROM cursos WHERE activo = 1 AND area IS NOT NULL GROUP BY area
        ");
        $stats['cursos_por_area'] = $stmt->fetchAll();

        // Inscripciones por mes
        $stmt = $db->query("
            SELECT DATE_FORMAT(fecha_inscripcion, '%Y-%m') AS mes, COUNT(*) AS total
            FROM inscripciones GROUP BY mes ORDER BY mes DESC LIMIT 6
        ");
        $stats['inscripciones_por_mes'] = $stmt->fetchAll();

        // Top facilitadores
        $stmt = $db->query("
            SELECT CONCAT(u.nombre, ' ', u.apellidos) AS nombre, COUNT(*) AS cursos_impartidos
            FROM cursos c JOIN usuarios u ON c.facilitador_id = u.id
            WHERE c.activo = 1 GROUP BY c.facilitador_id ORDER BY cursos_impartidos DESC LIMIT 5
        ");
        $stats['top_facilitadores'] = $stmt->fetchAll();

        return jsonResponse(true, $stats);

    } catch (PDOException $e) {
        http_response_code(500);
        return jsonResponse(false, null, 'Error al obtener estadisticas');
    }

}, [requireAdmin()], 'estadisticas');

// ============================================
// GET /backend/api/api.php?endpoint=auditoria
// Logs de auditoria completa (Admin)
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'auditoria') return null;

    $accion = $_GET['accion'] ?? '';
    $tabla = $_GET['tabla'] ?? '';
    $fechaDesde = $_GET['fecha_desde'] ?? '';
    $fechaHasta = $_GET['fecha_hasta'] ?? '';

    try {
        $db = getDB();

        $sql = "SELECT * FROM logs_actividad WHERE 1=1";
        $values = [];

        if ($accion) {
            $sql .= " AND accion = ?";
            $values[] = $accion;
        }

        if ($tabla) {
            $sql .= " AND tabla_afectada = ?";
            $values[] = $tabla;
        }

        if ($fechaDesde) {
            $sql .= " AND fecha_hora >= ?";
            $values[] = $fechaDesde . ' 00:00:00';
        }

        if ($fechaHasta) {
            $sql .= " AND fecha_hora <= ?";
            $values[] = $fechaHasta . ' 23:59:59';
        }

        $sql .= " ORDER BY fecha_hora DESC LIMIT 500";

        $stmt = $db->prepare($sql);
        $stmt->execute($values);

        return jsonResponse(true, ['logs' => $stmt->fetchAll(), 'total' => $stmt->rowCount()]);

    } catch (PDOException $e) {
        http_response_code(500);
        return jsonResponse(false, null, 'Error al obtener auditoria');
    }

}, [requireAdmin()], 'auditoria');

// ============================================
// GET /backend/api/api.php?endpoint=certificado
// Generar certificado de aprobacion (participante)
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'certificado') return null;

    $inscripcionId = Sanitizer::int($_GET['id'] ?? 0);
    $user = Auth::user();

    if (!$inscripcionId) {
        http_response_code(400);
        return jsonResponse(false, null, 'ID de inscripcion requerido');
    }

    try {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT i.*, c.codigo, c.nombre AS curso_nombre, c.duracion_horas,
                   CONCAT(f.nombre, ' ', f.apellidos) AS nombre_facilitador,
                   CONCAT(u.nombre, ' ', u.apellidos) AS nombre_estudiante,
                   u.cedula
            FROM inscripciones i
            JOIN cursos c ON i.curso_id = c.id
            JOIN usuarios u ON i.usuario_id = u.id
            LEFT JOIN usuarios f ON c.facilitador_id = f.id
            WHERE i.id = ? AND i.estado = 'Completado'
        ");
        $stmt->execute([$inscripcionId]);
        $certificado = $stmt->fetch();

        if (!$certificado) {
            http_response_code(404);
            return jsonResponse(false, null, 'Certificado no encontrado o curso no completado');
        }

        // Verificar que sea el participante o admin
        if ($user['rol'] === 'Participante' && $certificado['usuario_id'] != $user['id']) {
            http_response_code(403);
            return jsonResponse(false, null, 'No tiene acceso a este certificado');
        }

        return jsonResponse(true, ['certificado' => $certificado]);

    } catch (PDOException $e) {
        http_response_code(500);
        return jsonResponse(false, null, 'Error al generar certificado');
    }

}, [requireAuth()], 'certificado');

// ============================================
// GET /backend/api/api.php?endpoint=mis-certificados
// Lista certificados disponibles del participante
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'mis-certificados') return null;

    $user = Auth::user();

    if (!$user) {
        http_response_code(401);
        return jsonResponse(false, null, 'No autenticado');
    }

    try {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT 
                i.id,
                i.curso_id,
                c.codigo,
                c.nombre AS curso_nombre,
                c.duracion_horas,
                i.fecha_inscripcion,
                i.fecha_completado,
                i.nota_final,
                CONCAT(f.nombre, ' ', f.apellidos) AS nombre_facilitador
            FROM inscripciones i
            JOIN cursos c ON i.curso_id = c.id
            LEFT JOIN usuarios f ON c.facilitador_id = f.id
            WHERE i.usuario_id = ? AND i.estado = 'Completado'
            ORDER BY i.fecha_completado DESC
        ");
        $stmt->execute([$user['id']]);
        $certificados = $stmt->fetchAll();

        return jsonResponse(true, ['certificados' => $certificados]);

    } catch (PDOException $e) {
        http_response_code(500);
        return jsonResponse(false, null, 'Error al obtener certificados');
    }

}, [requireAuth()], 'mis-certificados');

// ============================================
// GET /backend/api/api.php?endpoint=mis-notas-pdf
// Descargar notas del participante en PDF
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'mis-notas-pdf') return null;

    $user = Auth::user();

    if (!$user) {
        http_response_code(401);
        return jsonResponse(false, null, 'No autenticado');
    }

    require_once __DIR__ . '/../libs/SimplePDF.php';

    try {
        $db = getDB();

        // Obtener datos del usuario
        $stmt = $db->prepare("
            SELECT nombre, apellidos, cedula, correo, area
            FROM usuarios WHERE id = ?
        ");
        $stmt->execute([$user['id']]);
        $estudiante = $stmt->fetch();

        // Obtener inscripciones con notas
        $stmt = $db->prepare("
            SELECT 
                c.codigo,
                c.nombre AS curso_nombre,
                c.duracion_horas,
                c.area,
                i.fecha_inscripcion,
                i.fecha_completado,
                i.estado,
                i.nota_final,
                CONCAT(f.nombre, ' ', f.apellidos) AS facilitador
            FROM inscripciones i
            JOIN cursos c ON i.curso_id = c.id
            LEFT JOIN usuarios f ON c.facilitador_id = f.id
            WHERE i.usuario_id = ?
            ORDER BY i.fecha_inscripcion DESC
        ");
        $stmt->execute([$user['id']]);
        $inscripciones = $stmt->fetchAll();

        // Generar PDF
        $pdf = new SimplePDF();
        $pdf->addPage();

        // Encabezado
        $pdf->setFont('Helvetica-Bold', 16);
        $pdf->text(20, 30, 'UPTEC - Sistema de Control de Cursos');
        $pdf->setFont('Helvetica', 12);
        $pdf->text(20, 40, 'Reporte de Notas del Estudiante');

        // Datos del estudiante
        $pdf->setFont('Helvetica-Bold', 12);
        $pdf->text(20, 55, 'Datos del Estudiante:');
        $pdf->setFont('Helvetica', 10);
        $pdf->text(20, 62, "Nombre: {$estudiante['nombre']} {$estudiante['apellidos']}");
        $pdf->text(20, 68, "Cedula: {$estudiante['cedula']}");
        $pdf->text(20, 74, "Correo: {$estudiante['correo']}");
        $pdf->text(20, 80, "Area: " . ($estudiante['area'] ?: 'No especificada'));

        // Tabla de cursos
        $pdf->setFont('Helvetica-Bold', 12);
        $pdf->text(20, 95, 'Cursos Inscritos:');

        $y = 105;
        $pdf->setFont('Helvetica-Bold', 9);
        $pdf->text(20, $y, 'Curso');
        $pdf->text(80, $y, 'Area');
        $pdf->text(110, $y, 'Estado');
        $pdf->text(140, $y, 'Nota');
        $pdf->text(160, $y, 'Facilitador');

        $y += 8;
        $pdf->setFont('Helvetica', 8);

        foreach ($inscripciones as $inscripcion) {
            if ($y > 270) {
                $pdf->addPage();
                $y = 30;
            }

            $pdf->text(20, $y, substr($inscripcion['curso_nombre'], 0, 30));
            $pdf->text(80, $y, substr($inscripcion['area'], 0, 15));
            $pdf->text(110, $y, $inscripcion['estado']);
            $pdf->text(140, $y, $inscripcion['nota_final'] ? number_format($inscripcion['nota_final'], 1) : '--');
            $pdf->text(160, $y, substr($inscripcion['facilitador'], 0, 20));
            $y += 6;
        }

        // Pie de pagina
        $pdf->setFont('Helvetica', 8);
        $pdf->text(20, 285, 'Generado el: ' . date('d/m/Y H:i:s'));

        $filename = "notas_{$estudiante['cedula']}.pdf";
        $pdf->output($filename, 'D');
        exit;

    } catch (Exception $e) {
        error_log("[UPTEC] Error generando PDF de notas: " . $e->getMessage());
        http_response_code(500);
        return jsonResponse(false, null, 'Error al generar PDF');
    }

}, [requireAuth()], 'mis-notas-pdf');

// ============================================
// GET /backend/api/api.php?endpoint=reportes-pdf
// Descargar reportes en PDF (Admin, Analista, Facilitador)
// ============================================
$router->register('GET', '/backend/api/api.php', function($params) {
    $endpoint = $_GET['endpoint'] ?? '';
    if ($endpoint !== 'reportes-pdf') return null;

    $user = Auth::user();

    if (!$user) {
        http_response_code(401);
        return jsonResponse(false, null, 'No autenticado');
    }

    // Solo Admin, Analista y Facilitador pueden descargar reportes
    if (!in_array($user['rol'], ['Administrador', 'Analista', 'Facilitador'])) {
        http_response_code(403);
        return jsonResponse(false, null, 'Permiso denegado');
    }

    require_once __DIR__ . '/../libs/SimplePDF.php';

    try {
        $db = getDB();
        $tipo = $user['rol'];

        // Generar PDF
        $pdf = new SimplePDF();
        $pdf->addPage();

        // Encabezado
        $pdf->setFont('Helvetica-Bold', 16);
        $pdf->text(20, 30, 'UPTEC - Sistema de Control de Cursos');
        $pdf->setFont('Helvetica', 12);
        $pdf->text(20, 40, "Reporte de $tipo - {$user['nombre']} {$user['apellidos']}");

        // Datos segun el rol
        $pdf->setFont('Helvetica-Bold', 12);
        $pdf->text(20, 55, 'Resumen General:');
        $pdf->setFont('Helvetica', 10);

        $y = 65;

        if ($tipo === 'Administrador') {
            // Estadisticas del admin
            $stats = [
                ['Total de Estudiantes', $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Participante'")->fetchColumn()],
                ['Total de Facilitadores', $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Facilitador'")->fetchColumn()],
                ['Total de Analistas', $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Analista'")->fetchColumn()],
                ['Total de Cursos', $db->query("SELECT COUNT(*) FROM cursos")->fetchColumn()],
                ['Cursos Activos', $db->query("SELECT COUNT(*) FROM cursos WHERE estado = 'En Curso'")->fetchColumn()],
                ['Total Inscripciones', $db->query("SELECT COUNT(*) FROM inscripciones")->fetchColumn()],
                ['Inscripciones Completadas', $db->query("SELECT COUNT(*) FROM inscripciones WHERE estado = 'Completado'")->fetchColumn()]
            ];

            foreach ($stats as $stat) {
                $pdf->text(20, $y, $stat[0] . ':');
                $pdf->text(100, $y, (string)$stat[1]);
                $y += 6;
            }

            // Top 5 cursos
            $y += 5;
            $pdf->setFont('Helvetica-Bold', 11);
            $pdf->text(20, $y, 'Top 5 Cursos con mas Estudiantes:');
            $y += 8;

            $stmt = $db->query("
                SELECT c.nombre, COUNT(i.id) as total
                FROM cursos c
                LEFT JOIN inscripciones i ON c.id = i.curso_id
                GROUP BY c.id
                ORDER BY total DESC
                LIMIT 5
            ");
            $topCursos = $stmt->fetchAll();

            $pdf->setFont('Helvetica', 9);
            foreach ($topCursos as $curso) {
                $pdf->text(20, $y, substr($curso['nombre'], 0, 40));
                $pdf->text(100, $y, $curso['total'] . ' estudiantes');
                $y += 5;
            }

        } elseif ($tipo === 'Analista') {
            // Estadisticas del analista
            $stats = [
                ['Total de Cursos', $db->query("SELECT COUNT(*) FROM cursos")->fetchColumn()],
                ['Cursos Planificados', $db->query("SELECT COUNT(*) FROM cursos WHERE estado = 'Planificado'")->fetchColumn()],
                ['Cursos en Curso', $db->query("SELECT COUNT(*) FROM cursos WHERE estado = 'En Curso'")->fetchColumn()],
                ['Cursos Finalizados', $db->query("SELECT COUNT(*) FROM cursos WHERE estado = 'Finalizado'")->fetchColumn()],
                ['Total Facilitadores', $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Facilitador'")->fetchColumn()],
                ['Total Inscripciones', $db->query("SELECT COUNT(*) FROM inscripciones")->fetchColumn()]
            ];

            foreach ($stats as $stat) {
                $pdf->text(20, $y, $stat[0] . ':');
                $pdf->text(100, $y, (string)$stat[1]);
                $y += 6;
            }

            // Cursos sin facilitador
            $y += 5;
            $pdf->setFont('Helvetica-Bold', 11);
            $pdf->text(20, $y, 'Cursos sin Facilitador Asignado:');
            $y += 8;

            $stmt = $db->query("
                SELECT nombre, codigo FROM cursos
                WHERE facilitador_id IS NULL AND estado != 'Cancelado'
            ");
            $cursosSinFacilitador = $stmt->fetchAll();

            $pdf->setFont('Helvetica', 9);
            if (empty($cursosSinFacilitador)) {
                $pdf->text(20, $y, 'Todos los cursos tienen facilitador asignado');
            } else {
                foreach ($cursosSinFacilitador as $curso) {
                    $pdf->text(20, $y, "{$curso['codigo']} - " . substr($curso['nombre'], 0, 35));
                    $y += 5;
                }
            }

        } elseif ($tipo === 'Facilitador') {
            // Estadisticas del facilitador
            $facilitadorId = $user['id'];

            $stats = [
                ['Mis Cursos', $db->prepare("SELECT COUNT(*) FROM cursos WHERE facilitador_id = ?")->execute([$facilitadorId]) ? $db->query("SELECT COUNT(*) FROM cursos WHERE facilitador_id = $facilitadorId")->fetchColumn() : 0],
                ['Cursos Activos', $db->query("SELECT COUNT(*) FROM cursos WHERE facilitador_id = $facilitadorId AND estado = 'En Curso'")->fetchColumn()],
                ['Total Estudiantes', $db->query("SELECT COUNT(*) FROM inscripciones i JOIN cursos c ON i.curso_id = c.id WHERE c.facilitador_id = $facilitadorId")->fetchColumn()]
            ];

            // Corregir consulta de conteo
            $stmt = $db->prepare("SELECT COUNT(*) FROM cursos WHERE facilitador_id = ?");
            $stmt->execute([$facilitadorId]);
            $stats[0][1] = $stmt->fetchColumn();

            $stmt = $db->prepare("SELECT COUNT(*) FROM cursos WHERE facilitador_id = ? AND estado = 'En Curso'");
            $stmt->execute([$facilitadorId]);
            $stats[1][1] = $stmt->fetchColumn();

            $stmt = $db->prepare("
                SELECT COUNT(*) FROM inscripciones i
                JOIN cursos c ON i.curso_id = c.id
                WHERE c.facilitador_id = ? AND i.estado != 'Abandonado'
            ");
            $stmt->execute([$facilitadorId]);
            $stats[2][1] = $stmt->fetchColumn();

            foreach ($stats as $stat) {
                $pdf->text(20, $y, $stat[0] . ':');
                $pdf->text(100, $y, (string)$stat[1]);
                $y += 6;
            }

            // Listado de cursos
            $y += 5;
            $pdf->setFont('Helvetica-Bold', 11);
            $pdf->text(20, $y, 'Mis Cursos:');
            $y += 8;

            $stmt = $db->prepare("
                SELECT c.codigo, c.nombre, c.estado,
                       (SELECT COUNT(*) FROM inscripciones WHERE curso_id = c.id AND estado != 'Abandonado') as inscritos
                FROM cursos c
                WHERE c.facilitador_id = ?
                ORDER BY c.fecha_inicio DESC
            ");
            $stmt->execute([$facilitadorId]);
            $misCursos = $stmt->fetchAll();

            $pdf->setFont('Helvetica', 9);
            $pdf->text(20, $y, 'Codigo');
            $pdf->text(60, $y, 'Nombre');
            $pdf->text(140, $y, 'Estado');
            $pdf->text(170, $y, 'Inscritos');
            $y += 6;

            foreach ($misCursos as $curso) {
                $pdf->text(20, $y, $curso['codigo']);
                $pdf->text(60, $y, substr($curso['nombre'], 0, 30));
                $pdf->text(140, $y, $curso['estado']);
                $pdf->text(170, $y, (string)$curso['inscritos']);
                $y += 5;
            }
        }

        // Pie de pagina
        $pdf->setFont('Helvetica', 8);
        $pdf->text(20, 285, 'Generado el: ' . date('d/m/Y H:i:s') . ' - UPTEC Cursos v2.0');

        $filename = "reporte_{$tipo}_{$user['cedula']}.pdf";
        $pdf->output($filename, 'D');
        exit;

    } catch (Exception $e) {
        error_log("[UPTEC] Error generando PDF de reportes: " . $e->getMessage());
        http_response_code(500);
        return jsonResponse(false, null, 'Error al generar PDF');
    }

}, [requireAnalystOrAbove()], 'reportes-pdf');
