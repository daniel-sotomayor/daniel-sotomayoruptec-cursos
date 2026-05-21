# Manual de Usuario - Analista

## UPTEC Cursos v2.0 - Guía del Analista Académico

---

## Introducción

Bienvenido al sistema **UPTEC Cursos**. Como Analista, eres responsable de la gestión académica: organizar la oferta de cursos, asignar facilitadores y verificar que todo funcione correctamente.

**Tu rol incluye:**
- Crear y gestionar cursos
- Asignar facilitadores a cursos
- Crear planes de evaluación
- Monitorear inscripciones
- Generar reportes académicos

**No incluye:**
- Crear usuarios (solo Admin)
- Eliminar cursos (solo Admin)
- Ver administradores u otros analistas
- Acceder a auditoría o backup

---

## Acceso al Sistema

### 1. Iniciar Sesión

1. Abre tu navegador web
2. Ve a: `http://localhost/uptec-cursos/frontend/login.html`
3. Ingresa tus credenciales:
   - **Correo:** `analista@uptec.edu.ve`
   - **Contraseña:** `analista123` (cámbiala después del primer ingreso)
4. Haz clic en **"Iniciar Sesión"**
5. Serás redirigido al panel de analista

### 2. Cambiar Contraseña (Obligatorio en primer ingreso)

1. Busca tu perfil de usuario (icono 👤 arriba a la derecha)
2. Selecciona **"Cambiar Contraseña"**
3. Ingresa contraseña actual: `analista123`
4. Crea una nueva contraseña segura (mínimo 6 caracteres, con letra y número)
5. Confirma la nueva contraseña
6. Guarda los cambios

---

## Dashboard - Panel del Analista

Tu panel de control muestra información académica relevante:

### Resumen Principal

| Tarjeta | Qué muestra | Para qué sirve |
|---------|-------------|----------------|
| 📚 **Total Cursos** | Cantidad de cursos en el sistema | Tamaño de la oferta académica |
| 📅 **Cursos Planificados** | Cursos sin iniciar | Identificar cursos que necesitan acción |
| 👨‍🏫 **Total Facilitadores** | Docentes disponibles | Capacidad de impartición |
| 📝 **Total Inscripciones** | Estudiantes inscritos | Nivel de participación |

### Gráficos Disponibles

- **Cursos por Área:** Distribución de la oferta según carrera/departamento
- **Cursos Sin Facilitador:** Lista de cursos que necesitan docente asignado

**Acción recomendada:** Si ves cursos sin facilitador, asígnalos lo antes posible.

---

## Gestión de Cursos

### Crear un Nuevo Curso

1. En el menú lateral, haz clic en **"📚 Cursos"**
2. Haz clic en el botón **"+ Nuevo Curso"** (color azul)
3. Completa el formulario paso a paso:

#### Información Básica

| Campo | Qué poner | Ejemplo |
|-------|-----------|---------|
| **Código** | Identificador corto y único | PROG-101, MEC-202, ADM-301 |
| **Nombre** | Nombre completo descriptivo | "Programación Web con PHP" |
| **Descripción** | Contenido, objetivos, requisitos | "Curso práctico de desarrollo web..." |
| **Duración (horas)** | Total de horas académicas | 40, 60, 80 |

#### Programación

| Campo | Qué poner | Tips |
|-------|-----------|------|
| **Fecha Inicio** | Cuándo comienza | Planifica con 2-4 semanas de anticipación |
| **Fecha Fin** | Cuándo termina | Verifica que haya tiempo suficiente |
| **Cupo Máximo** | Máximo de estudiantes | Considera capacidad de aulas/labs |

#### Clasificación

| Campo | Opciones | Qué significa |
|-------|----------|---------------|
| **Área** | Informática, Mecánica, Eléctrica, Administración, Mantenimiento, Transporte Ferroviario | A qué carrera/departamento pertenece |
| **Nivel** | Básico, Intermedio, Avanzado | Prerrequisitos necesarios |
| **Estado** | Planificado, En Curso, Finalizado, Cancelado | Etapa actual del curso |

4. Haz clic en **"Crear Curso"**
5. El curso aparecerá en la lista

### Asignar Facilitador

⚠️ **Importante:** Un curso sin facilitador no puede iniciar.

1. Ve a **"📚 Cursos"**
2. Busca el curso en la lista (usa el buscador si hay muchos)
3. Haz clic en **"✏️"** (Editar)
4. En el campo **"Facilitador"**, abre la lista desplegable
5. Verás solo facilitadores activos disponibles
6. Selecciona el facilitador apropiado
7. Guarda los cambios

**Consejo:** Verifica que el facilitador tenga disponibilidad de horario antes de asignar.

### Crear Plan de Evaluación

Todo curso necesita un plan de evaluación que sume **exactamente 100%**.

#### Paso 1: Acceder al Plan

1. Ve a detalles del curso (haz clic en el nombre del curso)
2. Busca la sección **"Plan de Evaluación"**
3. Haz clic en **"+ Agregar Evaluación"**

#### Paso 2: Configurar Evaluaciones

Para cada evaluación, completa:

| Campo | Descripción | Ejemplo |
|-------|-------------|---------|
| **Nombre** | Identifica la evaluación | "Parcial 1", "Proyecto Final" |
| **Tipo** | Categoría | Parcial, Final, Proyecto, Asistencia, Trabajo, Otro |
| **Peso** | % del total | 20, 25, 30 (debe sumar 100% entre todas) |
| **Fecha** | Cuándo se aplica | Fecha del examen, entrega, etc. |
| **Orden** | Secuencia | 1, 2, 3 (para organizar visualmente) |

#### Ejemplos de Planes de Evaluación

**Ejemplo 1 - Curso Teórico-Práctico:**
- Parcial 1: 25%
- Parcial 2: 25%
- Proyecto: 30%
- Asistencia: 20%
- **Total: 100%**

**Ejemplo 2 - Curso Práctico:**
- Trabajo 1: 20%
- Trabajo 2: 20%
- Proyecto Final: 40%
- Asistencia: 20%
- **Total: 100%**

#### Paso 3: Verificar Suma

- El sistema muestra el total acumulado
- Si es menor a 100%, agrega más evaluaciones
- Si es mayor a 100%, ajusta los pesos

⚠️ **El curso no puede iniciar hasta tener un plan completo.**

### Editar Información de Curso

1. Busca el curso en la lista
2. Haz clic en **"✏️"**
3. Modifica los campos necesarios
4. Guarda cambios

**Notas importantes:**
- Si el curso ya tiene estudiantes inscritos, sé cuidadoso al cambiar fechas
- Si el curso ya inició, no cambies el estado a "Planificado"
- Puedes cambiar de facilitador si es necesario (notifica a los involucrados)

### Gestionar Horarios (Si aplica)

Algunos cursos tienen horarios específicos:

1. En detalles del curso, busca **"Horarios"**
2. Agrega horarios:
   - Día de la semana (Lunes, Martes...)
   - Hora inicio (08:00)
   - Hora fin (12:00)
   - Aula/Laboratorio (Lab 1, Aula 5...)

---

## Seguimiento de Inscripciones

### Ver Inscripciones

1. Menú lateral → **"📝 Inscripciones"**
2. Verás tabla con:
   - Participante (nombre)
   - Cédula
   - Curso inscrito
   - Fecha de inscripción
   - Estado actual
   - Nota final

### Filtrar Inscripciones

Usa los filtros para encontrar información específica:

| Filtro | Uso |
|--------|-----|
| **Por Curso** | Ver solo inscripciones de un curso específico |
| **Por Estado** | Ver completados, en progreso, abandonados... |
| **Buscar** | Buscar por nombre o cédula de participante |

### Acciones sobre Inscripciones

**Cambiar Estado:**
1. Busca la inscripción
2. Haz clic en el estado actual
3. Selecciona nuevo estado:
   - **Inscrito:** Registrado, aún no inicia
   - **En Progreso:** Actualmente cursando
   - **Completado:** Finalizó y aprobó
   - **Abandonado:** Se retiró del curso
   - **Reprobado:** Finalizó pero no aprobó
4. Agrega observaciones si es relevante

---

## Reportes Académicos

### Generar Reportes

1. Menú lateral → **"📈 Reportes"**
2. Visualiza automáticamente:

| Reporte | Qué muestra | Uso |
|---------|-------------|-----|
| **Cursos por Área** | Distribución de la oferta | Planificar próximo período |
| **Cursos Sin Facilitador** | Lista de pendientes | Asignar docentes urgentemente |
| **Inscripciones por Curso** | Popularidad de cada curso | Decidir si repetir o no |
| **Estadísticas de Aprobación** | % de éxito por curso | Evaluar calidad/dificultad |

### Exportar Datos

- Usa el botón **"📥 Exportar"** para descargar reportes en formato útil
- Comparte con dirección académica

---

## Flujo de Trabajo Recomendado

### Semana a Semana

**Lunes:**
- Revisa dashboard: cursos sin facilitador
- Contacta docentes para confirmar asignaciones

**Martes-Miércoles:**
- Crea nuevos cursos para próximo período
- Define planes de evaluación

**Jueves:**
- Revisa inscripciones de cursos próximos a iniciar
- Verifica que haya cupos disponibles

**Viernes:**
- Genera reportes semanales
- Revisa estadísticas de cursos en curso

### Mensual

- Reporte de oferta académica a dirección
- Análisis de cursos más/menos demandados
- Sugerencias de nuevos cursos

### Por Período Académico

**Antes de iniciar:**
1. Crear todos los cursos del período
2. Asignar todos los facilitadores
3. Crear planes de evaluación completos
4. Publicar oferta para inscripciones

**Durante el período:**
1. Monitorear inscripciones
2. Apoyar a facilitadores con dudas
3. Resolver problemas de cupos

**Al finalizar:**
1. Verificar que estados estén actualizados
2. Generar reportes de cierre
3. Evaluar resultados para próximo período

---

## Consejos Prácticos

### Planificación de Cursos

1. **Diversifica áreas:** Asegúrate de ofrecer cursos en todas las carreras
2. **Diferentes niveles:** Básicos para nuevos, avanzados para experimentados
3. **Horarios variados:** Considera que algunos trabajan y estudian
4. **Cupos razonables:** 15-25 para teoría, 10-15 para laboratorios

### Comunicación con Facilitadores

1. **Confirma disponibilidad** antes de asignar
2. **Proporciona información completa** del curso
3. **Notifica cambios** inmediatamente (fechas, cancelaciones)
4. **Solicita feedback** al finalizar para mejorar

### Gestión de Cupos

- Si un curso llena rápido: considerar abrir otro grupo
- Si un curso tiene poca demanda: evaluar si ofrecerlo nuevamente
- Mantener lista de espera para cursos populares

---

## Solución de Problemas

### "No puedo crear un curso"

Verifica:
- ¿El código es único? (no se repite)
- ¿Las fechas son lógicas? (inicio antes que fin)
- ¿El cupo es mayor a 0?

### "No aparece el facilitador en la lista"

Posibles causas:
- El facilitador está inactivo (contactar administrador)
- El facilitador no existe en el sistema (debe crearlo admin)
- Error del sistema (recargar página)

### "El plan de evaluación no suma 100%"

- Revisa que no haya errores de tipeo
- Usa calculadora: suma manualmente todos los pesos
- Ajusta los valores hasta llegar exactamente a 100

### "No veo todas las inscripciones"

- Quita los filtros aplicados
- Verifica que estás en la sección correcta
- Recarga la página (F5)

### "Los reportes no cargan"

- Espera un momento (puede tardar si hay muchos datos)
- Verifica tu conexión a internet
- Intenta nuevamente más tarde

---

## Preguntas Frecuentes

**¿Puedo eliminar un curso?**
No, solo el administrador puede hacerlo. Si necesitas eliminar uno, contacta al admin.

**¿Puedo crear facilitadores nuevos?**
No, solo el administrador puede crear usuarios. Pide al admin que lo cree.

**¿Puedo ver notas de todos los estudiantes?**
Sí, puedes ver inscripciones y sus notas finales, pero no modificarlas (eso lo hace el facilitador).

**¿Qué hago si un curso se cancela?**
Cambia el estado a "Cancelado" y notifica a los inscritos.

**¿Puedo cambiar el facilitador de un curso en curso?**
Sí, pero notifica a ambos facilitadores (entrante y saliente) y a los estudiantes.

---

## Contacto

Si tienes dudas sobre el sistema o necesitas funcionalidades nuevas:

1. Documenta lo que necesitas
2. Habla con el administrador del sistema
3. O contacta al equipo de desarrollo de UPTEC

---

**Fin del Manual del Analista**

*Sistema UPTEC Cursos v2.0*
