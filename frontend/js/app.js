const API_URL = 'http://localhost:8090'

const botonFiltro = document.getElementById('btn-filtrar')
const botonLimpiar = document.getElementById('btn-limpiar')
const filtroNombre = document.getElementById('filtro-nombre')
const filtroEstado = document.getElementById('filtro-estado')
const filtroFechaEntrada = document.getElementById('filtro-fecha-entrada')
const filtroFechaSalida = document.getElementById('filtro-fecha-salida')

document.addEventListener('DOMContentLoaded', () => {
    cargarReservas()

    //evento del botón filtrar
    botonFiltro.addEventListener('click', () => {
        const filtros = {
            nombre_huesped: filtroNombre.value,
            estado: filtroEstado.value,
            fecha_entrada: filtroFechaEntrada.value,
            fecha_salida: filtroFechaSalida.value
        }

        cargarReservas(filtros)
    })

    //evento del botón limpiar
    botonLimpiar.addEventListener('click', () => {
        filtroNombre.value = ''
        filtroEstado.value = ''
        filtroFechaEntrada.value = ''
        filtroFechaSalida.value = ''
        cargarReservas()
    })

    //evento para rellenar el formulario y crear la reserva
    document.getElementById('form-crear-reserva').addEventListener('submit', async (e) => {
        e.preventDefault()

        const nombreHuesped = document.getElementById('nombre_huesped').value
        const emailHuesped = document.getElementById('email_huesped').value
        const alojamiento = document.getElementById('nombre_alojamiento').value
        const fechaEntrada = document.getElementById('fecha_entrada').value
        const fechaSalida = document.getElementById('fecha_salida').value
        const importe = parseFloat(document.getElementById('importe').value)
        const notas = document.getElementById('notas').value

        if (fechaSalida <= fechaEntrada) {
            mostrarFeedback('La fecha de salida debe ser posterior a la fecha de entrada.', 'danger')
            return
        }

        if (importe <= 0) {
            mostrarFeedback('El importe debe ser mayor de cero.', 'danger')
            return
        }

        const datosReserva = {
            nombre_huesped: nombreHuesped,
            email_huesped: emailHuesped,
            nombre_alojamiento: alojamiento,
            fecha_entrada: fechaEntrada,
            fecha_salida: fechaSalida,
            importe: importe,
            notas: notas
        }

        await crearReservaAPI(datosReserva)
    })
})

async function cargarReservas(filtros = {}) {
    mostrarCargador(true)
    ocultarFeedback()

    const params = new URLSearchParams()
    if (filtros.nombre_huesped) {
        params.append('nombre_huesped', filtros.nombre_huesped)
    }

    if (filtros.estado) {
        params.append('estado', filtros.estado)
    }

    if (filtros.fecha_entrada) {
        params.append('fecha_entrada', filtros.fecha_entrada)
    }

    if (filtros.fecha_salida) {
        params.append('fecha_salida', filtros.fecha_salida)
    }

    const url = `${API_URL}/reservas?${params.toString()}`

    try {
        const respuesta = await fetch(url)

        if (!respuesta.ok) {
            throw new Error(`Error. El servidor respondió con estado ${respuesta.status}`)
        }

        const resultado = await respuesta.json()
        renderizarTabla(resultado.reservas || [])
    } catch (error) {
        console.error('Error al conectar con la API: ', error)
        mostrarFeedback('No se pudo conectar al servidor.', 'danger')
    } finally {
        mostrarCargador(false)
    }
}

async function crearReservaAPI(datos) {
    mostrarCargador(true)
    ocultarFeedback()

    try {
        const respuesta = await fetch(`${API_URL}/reserva/crear`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        })

        const resultado = await respuesta.json()

        if (!respuesta.ok) {
            throw new Error(`Error al procesar la reserva. ${resultado.error}`)
        }

        mostrarFeedback('Reserva creada con éxito', 'success')
        document.getElementById('form-crear-reserva').reset()
        cargarReservas()
    } catch (error) {
        mostrarFeedback(error.message, 'danger')
    } finally {
        mostrarCargador(false)
    }
}
function renderizarTabla(reservas) {
    const tbody = document.getElementById('tabla-reservas-body')
    tbody.innerHTML = ''

    if (reservas.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">No se encontraron reservas.</td></tr>`
        return
    }

    reservas.forEach(reserva => {
        const tr = document.createElement('tr')
        tr.innerHTML = `
        <td>${reserva.id}</td>
        <td>${reserva.nombre_huesped}</td>
        <td>${reserva.email_huesped}</td>
        <td>${reserva.nombre_alojamiento}</td>
        <td>${reserva.fecha_entrada} al ${reserva.fecha_salida}</td>
        <td>${Number(reserva.importe).toFixed(2)}</td>
        <td><span class="badge">${reserva.estado}</span></td>
        <td>${reserva.notas}</td>
        <td>
            ${reserva.estado !== 'cancelada' 
                ? `<button class="btn btn-danger" onclick="cancelarReserva(${reserva.id})">Cancelar</button>`
                : '<span style="color: #94a3b8">Cancelada</span>'
            }
        </td>
        `
        tbody.appendChild(tr)
    });
}

function mostrarCargador(visible) {
    const spinner = document.getElementById('loading-spinner')
    if (visible) {
        spinner.classList.remove('d-none')
    } else {
        spinner.classList.add('d-none')
    }
}

function mostrarFeedback(mensaje, tipo) {
    const alerta = document.getElementById('feedback-alert')
    alerta.className = `alert alert-${tipo}`
    alerta.textContent = mensaje
    alerta.classList.remove('d-none')
}

function ocultarFeedback() {
    const alerta = document.getElementById('feedback-alert')
    alerta.classList.add('d-none')
}