const API_URL = 'http://localhost:8090'

document.addEventListener('DOMContentLoaded', () => {
    cargarReservas()

    document.getElementById('btn-filtrar').addEventListener('click', () => {
        const filtros = {
            nombre_huesped: document.getElementById('filtro-nombre').value,
            estado: document.getElementById('filtro-estado').value,
            fecha_entrada: document.getElementById('filtro-fecha-entrada').value,
            fecha_salida: document.getElementById('filtro-fecha-salida').value
        }

        cargarReservas(filtros)
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