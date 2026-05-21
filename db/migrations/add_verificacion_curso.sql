-- ============================================
-- MIGRACION: Agregar campos de verificacion a cursos
-- ============================================

USE uptec_cursos;

-- Agregar columna verificado_por
ALTER TABLE cursos
ADD COLUMN verificado_por INT UNSIGNED NULL COMMENT 'Usuario que verifico el curso' AFTER analista_id,
ADD CONSTRAINT fk_curso_verificado_por
    FOREIGN KEY (verificado_por) REFERENCES usuarios(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE;

-- Agregar columna fecha_verificacion
ALTER TABLE cursos
ADD COLUMN fecha_verificacion DATETIME NULL COMMENT 'Fecha de verificacion del curso' AFTER verificado_por;

-- Actualizar el ENUM de estado para incluir 'Verificado'
ALTER TABLE cursos
MODIFY COLUMN estado ENUM('Planificado', 'En Curso', 'Finalizado', 'Cancelado', 'Verificado')
    DEFAULT 'Planificado' COMMENT 'Estado actual del curso';

-- Agregar indice para verificado_por
ALTER TABLE cursos
ADD INDEX idx_verificado (verificado_por);

-- ============================================
-- FIN DE MIGRACION
-- ============================================
