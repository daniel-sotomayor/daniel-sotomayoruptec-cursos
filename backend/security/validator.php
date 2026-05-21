<?php
/**
 * UPTEC - Sistema de Control de Cursos v2.0
 * Modulo de Validacion de Datos
 */

declare(strict_types=1);

if (!defined('UPTEC_ACCESS')) {
    define('UPTEC_ACCESS', true);
}

class Validator
{
    private array $errors = [];

    /** Valida email - cualquier correo válido permitido */
    public function email(string $email, bool $requireInstitutional = false): bool
    {
        if (empty($email)) {
            $this->errors['email'] = 'El correo es requerido';
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'El formato del correo es invalido';
            return false;
        }

        if ($requireInstitutional && !preg_match('/@uptec\.edu\.ve$/i', $email)) {
            $this->errors['email'] = 'Se requiere correo institucional @uptec.edu.ve';
            return false;
        }

        return true;
    }

    /** Valida contrasena segura */
    public function password(string $password, string $field = 'password'): bool
    {
        if (empty($password)) {
            $this->errors[$field] = 'La contrasena es requerida';
            return false;
        }

        if (strlen($password) < 6) {
            $this->errors[$field] = 'La contrasena debe tener al menos 6 caracteres';
            return false;
        }

        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $this->errors[$field] = 'La contrasena debe contener al menos una letra y un numero';
            return false;
        }

        return true;
    }

    /** Valida contrasena con confirmacion */
    public function passwordConfirm(string $password, string $confirm, string $field = 'password'): bool
    {
        if (!$this->password($password, $field)) {
            return false;
        }

        if ($password !== $confirm) {
            $this->errors[$field] = 'Las contrasenas no coinciden';
            return false;
        }

        return true;
    }

    /** Valida nombre/apellido */
    public function name(string $value, string $field, int $minLen = 2, int $maxLen = 100): bool
    {
        $val = trim($value);

        if (empty($val)) {
            $this->errors[$field] = 'Este campo es requerido';
            return false;
        }

        if (strlen($val) < $minLen || strlen($val) > $maxLen) {
            $this->errors[$field] = "Debe tener entre {$minLen} y {$maxLen} caracteres";
            return false;
        }

        if (!preg_match('/^[a-zA-Z\s\x{00C0}-\x{017F}]+$/u', $val)) {
            $this->errors[$field] = 'Solo se permiten letras y espacios';
            return false;
        }

        return true;
    }

    /** Valida cedula - solo numeros */
    public function cedula(string $cedula): bool
    {
        $c = trim($cedula);

        if (empty($c)) {
            $this->errors['cedula'] = 'La cedula es requerida';
            return false;
        }

        if (!preg_match('/^\d{6,9}$/', $c)) {
            $this->errors['cedula'] = 'Formato de cedula invalido. Ejemplo: 32361670';
            return false;
        }

        return true;
    }

    /** Valida telefono - formato sin guion */
    public function telefono(string $telefono): bool
    {
        $t = trim($telefono);

        if (empty($t)) {
            $this->errors['telefono'] = 'El telefono es requerido';
            return false;
        }

        if (!preg_match('/^04\d{9}$/', $t)) {
            $this->errors['telefono'] = 'Formato invalido. Ejemplo: 04242987214';
            return false;
        }

        return true;
    }

    /** Valida numero entero */
    public function integer($value, string $field, ?int $min = null, ?int $max = null): bool
    {
        $val = filter_var($value, FILTER_VALIDATE_INT);

        if ($val === false) {
            $this->errors[$field] = 'Debe ser un numero entero';
            return false;
        }

        if ($min !== null && $val < $min) {
            $this->errors[$field] = "Debe ser mayor o igual a {$min}";
            return false;
        }

        if ($max !== null && $val > $max) {
            $this->errors[$field] = "Debe ser menor o igual a {$max}";
            return false;
        }

        return true;
    }

    /** Valida numero decimal */
    public function decimal($value, string $field, ?float $min = null, ?float $max = null): bool
    {
        $val = filter_var($value, FILTER_VALIDATE_FLOAT);

        if ($val === false) {
            $this->errors[$field] = 'Debe ser un numero decimal';
            return false;
        }

        if ($min !== null && $val < $min) {
            $this->errors[$field] = "Debe ser mayor o igual a {$min}";
            return false;
        }

        if ($max !== null && $val > $max) {
            $this->errors[$field] = "Debe ser menor o igual a {$max}";
            return false;
        }

        return true;
    }

    /** Valida nota academica */
    public function nota($value, string $field = 'nota'): bool
    {
        return $this->decimal($value, $field, 0, 20);
    }

    /** Valida rol valido */
    public function rol(string $rol): bool
    {
        $roles = ['Participante', 'Facilitador', 'Analista', 'Administrador'];

        if (!in_array($rol, $roles, true)) {
            $this->errors['rol'] = 'Rol invalido';
            return false;
        }

        return true;
    }

    /** Valida fecha */
    public function date(string $date, string $field, ?string $minDate = null, ?string $maxDate = null): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);

        if (!$d || $d->format('Y-m-d') !== $date) {
            $this->errors[$field] = 'Formato de fecha invalido (YYYY-MM-DD)';
            return false;
        }

        if ($minDate && $date < $minDate) {
            $this->errors[$field] = "La fecha no puede ser anterior a {$minDate}";
            return false;
        }

        if ($maxDate && $date > $maxDate) {
            $this->errors[$field] = "La fecha no puede ser posterior a {$maxDate}";
            return false;
        }

        return true;
    }

    /** Valida campo requerido */
    public function required($value, string $field): bool
    {
        $val = is_string($value) ? trim($value) : $value;

        if (empty($val)) {
            $this->errors[$field] = 'Este campo es requerido';
            return false;
        }

        return true;
    }

    /** Valida longitud de texto */
    public function length(string $value, string $field, int $min = 1, int $max = 255): bool
    {
        $len = mb_strlen($value);

        if ($len < $min || $len > $max) {
            $this->errors[$field] = "Debe tener entre {$min} y {$max} caracteres";
            return false;
        }

        return true;
    }

    /** Obtiene todos los errores */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /** Verifica si hay errores */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /** Devuelve true si todas las validaciones pasaron */
    public function passes(): bool
    {
        return empty($this->errors);
    }
}
