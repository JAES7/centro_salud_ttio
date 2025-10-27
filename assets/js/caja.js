// assets/js/caja.js (CON FUNCIÓN DE IMPRESIÓN)

document.addEventListener('DOMContentLoaded', function() {

    // --- VARIABLES GLOBALES ---
    const APP_URL = window.APP_URL || 'http://localhost/centro_salud_ttio';

    const selectEspecialidad = document.getElementById('id_especialidad');
    const selectProfesional = document.getElementById('id_profesional');
    const selectServicio = document.getElementById('select_servicio');
    
    const btnAgregarServicio = document.getElementById('btnAgregarServicio');
    const tbodyServicios = document.getElementById('tbodyServicios');
    const inputTotalGeneral = document.getElementById('totalGeneral');
    
    // --- ¡NUEVO! Seleccionamos el botón de imprimir ---
    const btnImprimirTicket = document.getElementById('btnImprimirTicket');

    // --- EVENT LISTENERS (ESCUCHADORES) ---

    // 1. Escuchar cuando se cambia la ESPECIALIDAD
    if (selectEspecialidad) {
        selectEspecialidad.addEventListener('change', function() {
            const especialidadId = this.value; 
            limpiarSelect(selectProfesional, '-- Elija profesional --');
            limpiarSelect(selectServicio, '-- Elija servicio --');
            if (especialidadId) {
                cargarProfesionales(especialidadId);
                cargarServicios(especialidadId);
            }
        });
    }

    // 2. Escuchar cuando se hace clic en "Agregar Servicio"
    if (btnAgregarServicio) {
        btnAgregarServicio.addEventListener('click', function() {
            agregarServicioATabla();
        });
    }

    // 3. Escuchar clics en la tabla de servicios (para el botón "Eliminar")
    if (tbodyServicios) {
        tbodyServicios.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-eliminar-servicio') || e.target.closest('.btn-eliminar-servicio')) {
                e.target.closest('tr').remove();
                actualizarTotalGeneral();
            }
        });
    }
    
    // --- ¡NUEVO! 4. Escuchar clic en el botón "Imprimir Último Ticket" ---
    if (btnImprimirTicket) {
        btnImprimirTicket.addEventListener('click', function() {
            // Leemos el ID del ticket guardado en el 'data-attribute' del botón
            const ticketId = this.dataset.idTicket; 
            if (ticketId) {
                // Si hay un ID, llamamos a la función para abrir la ventana
                abrirVentanaImpresion(ticketId);
            } else {
                alert('No hay un ticket reciente para imprimir. Por favor, guarde una atención primero.');
            }
        });
    }


    // --- FUNCIONES ---

    async function cargarProfesionales(especialidadId) {
        try {
            const respuesta = await fetch(`${APP_URL}/caja/getProfesionales/${especialidadId}`);
            if (!respuesta.ok) throw new Error('Error red profesionales');
            const profesionales = await respuesta.json();
            profesionales.forEach(prof => {
                const option = new Option(prof.nombre_completo, prof.id_profesional);
                selectProfesional.add(option);
            });
        } catch (error) {
            console.error('Error:', error);
            alert('No se pudieron cargar los profesionales.');
        }
    }

    async function cargarServicios(especialidadId) {
        try {
            const respuesta = await fetch(`${APP_URL}/caja/getServicios/${especialidadId}`);
            if (!respuesta.ok) throw new Error('Error red servicios');
            const servicios = await respuesta.json();
            servicios.forEach(serv => {
                const precioFormateado = parseFloat(serv.monto).toFixed(2);
                const textoOpcion = `${serv.descripcion} (S/ ${precioFormateado})`;
                const option = new Option(textoOpcion, serv.id_servicio);
                option.dataset.precio = serv.monto; 
                selectServicio.add(option);
            });
        } catch (error) {
            console.error('Error:', error);
            alert('No se pudieron cargar los servicios.');
        }
    }

    function limpiarSelect(selectElement, defaultText) {
        while (selectElement.options.length > 1) {
            selectElement.remove(1);
        }
        selectElement.options[0].textContent = defaultText;
    }

    function agregarServicioATabla() {
        const servicioSeleccionado = selectServicio.options[selectServicio.selectedIndex];
        const servicioId = servicioSeleccionado.value;
        const servicioTexto = servicioSeleccionado.text; 
        const precioUnitario = parseFloat(servicioSeleccionado.dataset.precio || 0); 
        const cantidadInput = document.getElementById('servicio_cantidad');
        const cantidad = parseInt(cantidadInput.value, 10);

        if (!servicioId) { alert('Seleccione un servicio.'); return; }
        if (isNaN(cantidad) || cantidad < 1) { alert('Cantidad inválida.'); return; }
        if (isNaN(precioUnitario)) { alert('Precio inválido.'); return; }
        
        const servicioExistente = tbodyServicios.querySelector(`input[name="servicios_agregados[${servicioId}][id]"]`);
        if(servicioExistente) {
            alert('Servicio ya agregado.');
            return; 
        }

        const subtotal = cantidad * precioUnitario;
        const nuevaFila = document.createElement('tr');
        nuevaFila.innerHTML = `
            <td>
                ${servicioTexto}
                <input type="hidden" name="servicios_agregados[${servicioId}][id]" value="${servicioId}">
                <input type="hidden" name="servicios_agregados[${servicioId}][descripcion]" value="${servicioTexto}">
            </td>
            <td><input type="number" class="form-control" name="servicios_agregados[${servicioId}][cantidad]" value="${cantidad}" readonly></td>
            <td><input type="text" class="form-control" name="servicios_agregados[${servicioId}][precio]" value="${precioUnitario.toFixed(2)}" readonly></td>
            <td><input type="text" class="form-control" name="servicios_agregados[${servicioId}][subtotal]" value="${subtotal.toFixed(2)}" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm btn-eliminar-servicio"><i class="fas fa-trash"></i></button></td>
        `;
        tbodyServicios.appendChild(nuevaFila);
        actualizarTotalGeneral();
        selectServicio.selectedIndex = 0;
        cantidadInput.value = 1;
    }

    function actualizarTotalGeneral() {
        let total = 0;
        const filas = tbodyServicios.querySelectorAll('tr');
        filas.forEach(fila => {
            const inputSubtotal = fila.querySelector('input[name$="[subtotal]"]');
            if (inputSubtotal) {
                total += parseFloat(inputSubtotal.value) || 0;
            }
        });
        inputTotalGeneral.value = total.toFixed(2);
    }

    // --- ¡NUEVA FUNCIÓN PARA ABRIR VENTANA DE IMPRESIÓN! ---
    /**
     * Abre una nueva ventana pequeña con la URL del ticket
     */
    function abrirVentanaImpresion(ticketId) {
        const urlTicket = `${APP_URL}/caja/imprimir/${ticketId}`;
        // Definimos las propiedades de la ventana pop-up
        const ancho = 400; // Ancho similar a una boleta
        const alto = 600; // Alto suficiente
        const left = (screen.width / 2) - (ancho / 2); // Centrar horizontalmente
        const top = (screen.height / 2) - (alto / 2); // Centrar verticalmente
        
        // Abrimos la ventana
        window.open(urlTicket, 'BoletaWindow', 
            `width=${ancho},height=${alto},top=${top},left=${left},resizable=yes,scrollbars=yes,status=yes`
        );
    }
    // --- FIN DE NUEVA FUNCIÓN ---

});