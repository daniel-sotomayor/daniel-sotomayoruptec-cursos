# Manual de Usuario - Facilitador

## UPTEC Cursos v2.0 - Guía del Docente/Facilitador

---

## Introducción

Bienvenido al sistema **UPTEC Cursos**. Como Facilitador, eres el docente responsable de impartir cursos, gestionar estudiantes y registrar sus calificaciones.

**Tu rol incluye:**
- Ver tus cursos asignados
- Ver lista de estudiantes inscritos en tus cursos
- Registrar calificaciones
- Ver plan de evaluación de cada curso
- Generar reportes de tus cursos

**No incluye:**
- Crear o editar cursos (eso lo hace el analista)
- Inscribir estudiantes (ellos se inscriben o lo hace admin)
- Ver cursos de otros facilitadores
- Ver estudiantes que no son tuyos

---

## Acceso al Sistema

### 1. Primera Vez: Registro

Si eres nuevo facilitador, debes registrarte:

1. Ve a: `http://localhost/uptec-cursos/frontend/login.html`
2. Haz clic en **"¿Eres nuevo? Crear cuenta"**
3. Selecciona el rol **"👨‍🏫 Facilitador"**
4. Completa el formulario:

   | Campo | Ejemplo | Importante |
   |-------|---------|------------|
   | **Cédula** | V11111111 | Tu número real |
   | **Nombre** | Juan | Tu primer nombre |
   | **Apellidos** | Pérez García | Ambos apellidos |
   | **Correo** | jperez@uptec.edu.ve | Tu correo institucional |
   | **Teléfono** | 04121234567 | Número actual |
   | **Contraseña** | (crea una) | Mínimo 6 caracteres, letra+número |

5. Confirma la contraseña
6. Haz clic en **"Crear Cuenta"**
7. Espera a que un administrador active tu cuenta (o contacta al analista)

### 2. Iniciar Sesión

Una vez aprobado:

1. Ve a la página de login
2. Ingresa tu correo y contraseña
3. Serás redirigido a tu panel de facilitador

### 3. Cambiar Contraseña

1. Haz clic en tu nombre (esquina superior derecha)
2. Selecciona **"Cambiar Contraseña"**
3. Ingresa contraseña actual
4. Crea nueva contraseña segura
5. Guarda cambios

---

## Dashboard - Panel del Facilitador

Tu panel muestra información de tus cursos:

### Resumen Principal

| Tarjeta | Qué muestra | Significado |
|---------|-------------|-------------|
| 📚 **Mis Cursos** | Cantidad de cursos donde eres facilitador | Tu carga académica actual |
| 👨‍🎓 **Mis Estudiantes** | Total de estudiantes en todos tus cursos | Total de alumnos a tu cargo |

### Cursos Activos

Lista de tus cursos con información rápida:
- Nombre del curso
- Código
- Cantidad de estudiantes inscritos
- Estado actual

**Acción rápida:** Haz clic en cualquier curso para ir a calificaciones.

---

## Mis Cursos

### Ver Tus Cursos Asignados

1. Menú lateral → **"📚 Mis Cursos"**
2. Verás lista de todos tus cursos:
   - Los que están en curso actualmente
   - Los que están planificados (próximos)
   - Los que ya finalizaron

### Información de Cada Curso

Haz clic en un curso para ver detalles:

| Sección | Información |
|---------|-------------|
| **Datos Generales** | Código, nombre, descripción, fechas |
| **Estudiantes Inscritos** | Lista con nombres, cédulas, contactos |
| **Plan de Evaluación** | Evaluaciones y sus pesos |
| **Horarios** | Días, horas, aula (si aplica) |

---

## Estudiantes

### Ver Estudiantes de un Curso

1. Menú lateral → **"👨‍🎓 Estudiantes"**
2. Selecciona el curso de la lista desplegable
3. Verás tabla con:

| Columna | Información |
|-----------|-------------|
| **Cédula** | Identificación del estudiante |
| **Nombre** | Nombre completo |
| **Correo** | Email de contacto |
| **Estado** | Inscrito, En Progreso, Completado... |
| **Nota Final** | Calificación final (si aplica) |

### Contactar Estudiantes

- Usa el correo mostrado para comunicaciones oficiales
- Puedes enviar comunicados a todo el grupo desde tu correo institucional

---

## Calificaciones - Tarea Principal

### Entender el Sistema de Notas

**Escala:** 0 a 20 puntos

| Rango | Significado |
|-------|-------------|
| 18-20 | Excelente |
| 15-17 | Muy Bueno |
| 12-14 | Bueno |
| 10-11 | Aprobado |
| 0-9 | Reprobado |

**Cálculo:** El sistema pondera automáticamente según los pesos del plan de evaluación.

### Ver Plan de Evaluación

Antes de calificar, revisa el plan:

1. En tu curso, busca **"Plan de Evaluación"**
2. Verás lista como:
   - Parcial 1: 20%
   - Parcial 2: 25%
   - Proyecto: 30%
   - Asistencia: 25%
   - **Total: 100%**

### Registrar Calificación

#### Paso 1: Acceder al Módulo

1. Menú lateral → **"📊 Calificaciones"**
2. Selecciona el curso de la lista desplegable

#### Paso 2: Seleccionar Estudiante

Verás lista de estudiantes inscritos.
Haz clic en **"+ Calificar"** junto al estudiante.

#### Paso 3: Completar Datos

| Campo | Qué poner | Ejemplo |
|-------|-----------|---------|
| **Tipo** | Selecciona del plan | Parcial, Proyecto, etc. |
| **Descripción** | Opcional | "Examen de unidad 1" |
| **Nota** | 0 a 20 | 16.5 |
| **Peso** | % (ya viene del plan) | 20 |
| **Fecha** | Cuándis se evaluó | 2026-02-15 |

#### Paso 4: Guardar

Haz clic en **"Guardar Calificación"**

**Nota:** Puedes registrar múltiples calificaciones para el mismo estudiante (una por cada evaluación del plan).

### Ver Calificaciones Registradas

En la pantalla de calificaciones verás:
- Estudiante
- Evaluaciones registradas
- Nota de cada evaluación
- Nota final calculada automáticamente

### Editar Calificación

¿Te equivocaste al registrar?

1. Busca la calificación en la lista
2. Haz clic en **"✏️"** (editar)
3. Corrige la nota
4. Guarda cambios
5. La nota final se recalcula automáticamente

### Cómo se Calcula la Nota Final

**Ejemplo práctico:**

Estudiante: Pedro Hernández

| Evaluación | Nota | Peso | Ponderado |
|------------|------|------|-----------|
| Parcial 1 | 16 | 20% | 16 × 0.20 = 3.2 |
| Parcial 2 | 14 | 25% | 14 × 0.25 = 3.5 |
| Proyecto | 18 | 30% | 18 × 0.30 = 5.4 |
| Asistencia | 20 | 25% | 20 × 0.25 = 5.0 |
| **TOTAL** | | **100%** | **17.1** |

**Nota Final: 17.1** (Muy Bueno)

El sistema hace esto automáticamente, pero es útil entenderlo.

### Estados Automáticos

Cuando completes todas las evaluaciones:

| Condición | Estado asignado |
|-----------|-----------------|
| Nota final ≥ 10 | **Completado** (aprobó) |
| Nota final < 10 | **Reprobado** |

---

## Reportes de Tus Cursos

### Generar Reportes

1. Menú lateral → **"📈 Reportes"**
2. Selecciona el curso
3. Verás estadísticas:

| Reporte | Información |
|---------|-------------|
| **Lista de Estudiantes** | Todos los inscritos |
| **Aprobados vs Reprobados** | Cantidad y porcentajes |
| **Promedio del Curso** | Nota media del grupo |
| **Rango de Notas** | Mínima, máxima, distribución |

### Usar los Reportes

- **Evaluar desempeño:** ¿Fue el curso muy difícil/fácil?
- **Identificar problemas:** ¿Muchos reprobaron? ¿Qué evaluación fue más difícil?
- **Mejorar próximas ediciones:** Ajusta el plan de evaluación si es necesario

---

## Flujo de Trabajo Semanal

### Durante el Curso

**Semana 1 (Inicio):**
1. Verifica lista de estudiantes inscritos
2. Confirma que tienen tus datos de contacto
3. Revisa el plan de evaluación

**Semanas intermedias:**
1. Imparte el contenido del curso
2. Cuando haya evaluaciones, regístralas en el sistema
3. No esperes al final: registra calificaciones semanalmente

**Semana Final:**
1. Asegúrate de tener todas las calificaciones registradas
2. Verifica que todas sumen el 100% del plan
3. Revisa que las notas finales se hayan calculado
4. Comunica resultados a estudiantes

### Después del Curso

1. Genera reporte final
2. Analiza resultados
3. Sugiere mejoras al analista para próximas ediciones

---

## Consejos para Facilitadores

### Registrar Calificaciones

✅ **Haz esto:**
- Registra calificaciones dentro de 48 horas de la evaluación
- Verifica que la nota sea correcta antes de guardar
- Si usas decimales, usa punto (16.5) no coma (16,5)

❌ **Evita esto:**
- Dejar todas las calificaciones para el final del curso
- Registrar notas de más de 20 o menos de 0
- Cambiar calificaciones sin justificación

### Comunicación con Estudiantes

- Sé claro sobre el plan de evaluación desde el día 1
- Comunica fechas de evaluaciones con anticipación
- Notifica cuando registres calificaciones importantes
- Sé accesible para dudas

### Gestión de Cursos

- Llega puntual según el horario establecido
- Si necesitas cambiar algo (fecha, aula), avisa al analista con tiempo
- Mantene lista de asistencia (puede influir en evaluación)

---

## Solución de Problemas

### "No veo mis cursos"

- ¿Ya te asignaron cursos? (El analista debe hacerlo)
- ¿Tu cuenta está activa? (Verifica con administrador)
- Recarga la página (F5)

### "No puedo registrar calificación"

Verifica:
- ¿El estudiante está inscrito en tu curso?
- ¿La nota está entre 0 y 20?
- ¿El peso es correcto?
- ¿Ya registraste esa evaluación para ese estudiante?

### "La nota final no aparece"

- Revisa que el plan de evaluación sume exactamente 100%
- Verifica que todas las evaluaciones tengan calificación
- Recarga la página

### "No aparece un estudiante en mi lista"

Posibles causas:
- El estudiante no se inscribió aún
- Se inscribió a otro grupo/sección
- Su inscripción fue cancelada

### "Me equivoqué en una nota ya guardada"

1. Busca la calificación
2. Haz clic en editar (✏️)
3. Corrige la nota
4. Guarda
5. La nota final se actualiza automáticamente

---

## Preguntas Frecuentes

**¿Cuándo puedo registrar calificaciones?**
En cualquier momento durante el curso. Se recomienda hacerlo pronto después de cada evaluación.

**¿Puedo registrar calificación de un estudiante que abandonó?**
Sí, registra hasta donde llegó. Las evaluaciones no hechas quedarán sin nota.

**¿El estudiante puede ver sus notas?**
Sí, los participantes tienen acceso a ver sus calificaciones en su panel.

**¿Qué pasa si no registro todas las calificaciones?**
La nota final se calculará con lo que hayas registrado, pero no reflejará el desempeño completo.

**¿Puedo dar notas con decimales?**
Sí, por ejemplo: 16.5, 14.75, etc.

**¿Quién decide si un estudiante aprueba?**
El sistema automáticamente: nota final ≥ 10 = aprueba, < 10 = reprueba.

**¿Puedo cambiar el plan de evaluación?**
No, eso lo define el analista al crear el curso. Si hay problemas, habla con el analista.

---

## Contacto

Si tienes problemas técnicos:

1. Intenta recargar la página
2. Verifica tu conexión a internet
3. Contacta al analista académico
4. Si persiste, habla con el administrador del sistema

---

**Fin del Manual del Facilitador**

*Sistema UPTEC Cursos v2.0*
