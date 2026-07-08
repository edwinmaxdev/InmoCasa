/* =====================================================
   InmoCasa - Validaciones globales
   public/js/validaciones.js
===================================================== */

/* ---- Validaciones de formato ---- */

/**
 * Verifica si un valor está vacío
 */
function isEmpty(valor) {
    return !valor || valor.trim() === '';
}

/**
 * Verifica si un email es válido
 */
function isEmailValido(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/**
 * Verifica si una cédula ecuatoriana tiene 10 dígitos
 */
function isCedulaValida(cedula) {
    return /^\d{10}$/.test(cedula);
}

/**
 * Verifica si un teléfono es numérico (7 a 15 dígitos)
 */
function isTelefonoValido(telefono) {
    return /^\d{7,15}$/.test(telefono);
}

/**
 * Verifica si un número es mayor a 0
 */
function isMontoValido(valor) {
    return !isNaN(valor) && parseFloat(valor) > 0;
}

/**
 * Verifica si una fecha es válida
 */
function isFechaValida(fecha) {
    return fecha !== '' && !isNaN(Date.parse(fecha));
}

/**
 * Verifica si fecha2 es mayor a fecha1
 */
function isFechaFinMayor(fechaInicio, fechaFin) {
    return fechaFin > fechaInicio;
}

/* ---- Helpers de UI ---- */

/**
 * Marca un campo como inválido y muestra mensaje de error
 */
function marcarError(inputId, mensajeId, mensaje) {
    const input = document.getElementById(inputId);
    const msg   = document.getElementById(mensajeId);
    if (input) input.classList.add('input-error');
    if (msg)   msg.textContent = mensaje;
}

/**
 * Limpia todos los errores del formulario
 */
function limpiarErrores() {
    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
}

/**
 * Muestra/oculta contraseña
 */
function togglePassword(inputId, iconId) {
    const input   = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        if (eyeIcon) {
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    } else {
        input.type = 'password';
        if (eyeIcon) {
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
}

/**
 * Filtra una tabla por texto y opcionalmente por estado
 */
function filtrarTabla(tablaId, buscadorId, filtroId = null) {
    const buscar = document.getElementById(buscadorId)?.value.toLowerCase() ?? '';
    const filtro = filtroId ? document.getElementById(filtroId)?.value.toLowerCase() ?? '' : '';
    const filas  = document.querySelectorAll(`#${tablaId} tbody tr`);

    filas.forEach(fila => {
        const texto      = fila.textContent.toLowerCase();
        const badgeTexto = fila.querySelector('.badge')?.textContent.trim().toLowerCase() ?? '';
        const coincideBuscar = texto.includes(buscar);
        const coincideFiltro = filtro === '' || badgeTexto === filtro;
        fila.style.display = coincideBuscar && coincideFiltro ? '' : 'none';
    });
}

/* ---- Validaciones específicas por módulo ---- */

/**
 * Valida el formulario de login
 */
function validarLogin() {
    limpiarErrores();
    let valido = true;

    const email    = document.getElementById('email');
    const password = document.getElementById('password');
    const rol      = document.getElementById('rolSeleccionado');

    if (!email || isEmpty(email.value)) {
        marcarError('email', 'err_email', 'El email es obligatorio');
        valido = false;
    } else if (!isEmailValido(email.value)) {
        marcarError('email', 'err_email', 'El email no es válido');
        valido = false;
    }

    if (!password || isEmpty(password.value)) {
        marcarError('password', 'err_password', 'La contraseña es obligatoria');
        valido = false;
    }

    if (rol && isEmpty(rol.value)) {
        document.getElementById('rolError')?.classList.add('visible');
        valido = false;
    }

    return valido;
}

/**
 * Valida formularios de propietarios e inquilinos
 */
function validarPersona() {
    limpiarErrores();
    let valido = true;

    const nombre   = document.getElementById('nombre');
    const cedula   = document.getElementById('cedula');
    const email    = document.getElementById('email');
    const telefono = document.getElementById('telefono');

    if (!nombre || isEmpty(nombre.value)) {
        marcarError('nombre', 'err_nombre', 'El nombre es obligatorio');
        valido = false;
    }

    if (!cedula || isEmpty(cedula.value)) {
        marcarError('cedula', 'err_cedula', 'La cédula es obligatoria');
        valido = false;
    } else if (!isCedulaValida(cedula.value)) {
        marcarError('cedula', 'err_cedula', 'La cédula debe tener 10 dígitos');
        valido = false;
    }

    if (!email || isEmpty(email.value)) {
        marcarError('email', 'err_email', 'El email es obligatorio');
        valido = false;
    } else if (!isEmailValido(email.value)) {
        marcarError('email', 'err_email', 'El email no es válido');
        valido = false;
    }

    if (telefono && !isEmpty(telefono.value) && !isTelefonoValido(telefono.value)) {
        marcarError('telefono', 'err_telefono', 'El teléfono debe ser numérico (7-15 dígitos)');
        valido = false;
    }

    return valido;
}

/**
 * Valida formulario de propiedades
 */
function validarPropiedad() {
    limpiarErrores();
    let valido = true;

    const direccion    = document.getElementById('direccion');
    const precio       = document.getElementById('precio');
    const metros2      = document.getElementById('metros2');
    const tipo_id      = document.getElementById('tipo_id');
    const propietario  = document.getElementById('propietario_id');

    if (!direccion || isEmpty(direccion.value)) {
        marcarError('direccion', 'err_direccion', 'La dirección es obligatoria');
        valido = false;
    }

    if (!precio || !isMontoValido(precio.value)) {
        marcarError('precio', 'err_precio', 'El precio debe ser mayor a 0');
        valido = false;
    }

    if (!metros2 || !isMontoValido(metros2.value)) {
        marcarError('metros2', 'err_metros2', 'Los metros cuadrados deben ser mayor a 0');
        valido = false;
    }

    if (!tipo_id || isEmpty(tipo_id.value)) {
        marcarError('tipo_id', 'err_tipo', 'El tipo de inmueble es obligatorio');
        valido = false;
    }

    if (!propietario || isEmpty(propietario.value)) {
        marcarError('propietario_id', 'err_propietario', 'El propietario es obligatorio');
        valido = false;
    }

    return valido;
}

/**
 * Valida formulario de contratos
 */
function validarContrato() {
    limpiarErrores();
    let valido = true;

    const propiedad   = document.getElementById('propiedad_id');
    const inquilino   = document.getElementById('inquilino_id');
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin    = document.getElementById('fecha_fin');
    const monto       = document.getElementById('monto_mensual');

    if (!propiedad || isEmpty(propiedad.value)) {
        marcarError('propiedad_id', 'err_propiedad', 'La propiedad es obligatoria');
        valido = false;
    }

    if (!inquilino || isEmpty(inquilino.value)) {
        marcarError('inquilino_id', 'err_inquilino', 'El inquilino es obligatorio');
        valido = false;
    }

    if (!fechaInicio || isEmpty(fechaInicio.value)) {
        marcarError('fecha_inicio', 'err_fecha_inicio', 'La fecha de inicio es obligatoria');
        valido = false;
    }

    if (!fechaFin || isEmpty(fechaFin.value)) {
        marcarError('fecha_fin', 'err_fecha_fin', 'La fecha de fin es obligatoria');
        valido = false;
    }

    if (fechaInicio?.value && fechaFin?.value && !isFechaFinMayor(fechaInicio.value, fechaFin.value)) {
        marcarError('fecha_fin', 'err_fecha_fin', 'La fecha de fin debe ser mayor a la de inicio');
        valido = false;
    }

    if (!monto || !isMontoValido(monto.value)) {
        marcarError('monto_mensual', 'err_monto', 'El monto debe ser mayor a 0');
        valido = false;
    }

    return valido;
}

/**
 * Valida formulario de pagos
 */
function validarPago() {
    limpiarErrores();
    let valido = true;

    const contrato = document.getElementById('contrato_id');
    const mes      = document.getElementById('mes_correspondiente');
    const monto    = document.getElementById('monto');
    const estado   = document.getElementById('estado');
    const fecha    = document.getElementById('fecha_pago');

    if (!contrato || isEmpty(contrato.value)) {
        marcarError('contrato_id', 'err_contrato', 'El contrato es obligatorio');
        valido = false;
    }

    if (!mes || isEmpty(mes.value)) {
        marcarError('mes_correspondiente', 'err_mes', 'El mes correspondiente es obligatorio');
        valido = false;
    }

    if (!monto || !isMontoValido(monto.value)) {
        marcarError('monto', 'err_monto', 'El monto debe ser mayor a 0');
        valido = false;
    }

    if (estado?.value === 'Pagado' && (!fecha || isEmpty(fecha.value))) {
        marcarError('fecha_pago', 'err_fecha', 'La fecha de pago es obligatoria cuando el estado es Pagado');
        valido = false;
    }

    return valido;
}

/**
 * Valida formulario de usuarios
 */
function validarUsuario(esEdicion = false) {
    limpiarErrores();
    let valido = true;

    const nombre   = document.getElementById('nombre');
    const email    = document.getElementById('email');
    const rol      = document.getElementById('rol');
    const password = document.getElementById('password');

    if (!nombre || isEmpty(nombre.value)) {
        marcarError('nombre', 'err_nombre', 'El nombre es obligatorio');
        valido = false;
    }

    if (!email || isEmpty(email.value)) {
        marcarError('email', 'err_email', 'El email es obligatorio');
        valido = false;
    } else if (!isEmailValido(email.value)) {
        marcarError('email', 'err_email', 'El email no es válido');
        valido = false;
    }

    if (!rol || isEmpty(rol.value)) {
        marcarError('rol', 'err_rol', 'Selecciona un rol');
        valido = false;
    }

    // En creación la contraseña es obligatoria, en edición es opcional
    if (!esEdicion && (!password || isEmpty(password.value))) {
        marcarError('password', 'err_password', 'La contraseña es obligatoria');
        valido = false;
    } else if (password?.value && password.value.length < 6) {
        marcarError('password', 'err_password', 'Mínimo 6 caracteres');
        valido = false;
    }

    if (rol?.value === 'Propietario') {
        const propietario = document.getElementById('propietario_id');
        if (!propietario || isEmpty(propietario.value)) {
            marcarError('propietario_id', 'err_propietario', 'Debes vincular un propietario');
            valido = false;
        }
    }

    if (rol?.value === 'Inquilino') {
        const inquilino = document.getElementById('inquilino_id');
        if (!inquilino || isEmpty(inquilino.value)) {
            marcarError('inquilino_id', 'err_inquilino', 'Debes vincular un inquilino');
            valido = false;
        }
    }

    return valido;
}