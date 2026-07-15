import Swal from 'sweetalert2';

// Tema base alineado a la paleta emerald del sistema.
const base = Swal.mixin({
    buttonsStyling: false,
    reverseButtons: true,
    customClass: {
        popup: 'rounded-lg',
        confirmButton:
            'rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none',
        cancelButton:
            'ms-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none',
        denyButton:
            'rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none',
    },
});

// Confirmar borrado. Devuelve Promise<boolean>.
export async function confirmarEliminar(texto = '¿Eliminar este registro?') {
    const r = await base.fire({
        title: '¿Estás seguro?',
        text: texto,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            ...base.params?.customClass,
            confirmButton:
                'rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none',
            cancelButton:
                'ms-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none',
        },
    });
    return r.isConfirmed;
}

// Confirmar acción genérica (aprobar, cancelar, etc.). Devuelve Promise<boolean>.
export async function confirmarAccion(texto, { title = '¿Confirmar?', confirmButtonText = 'Confirmar', icon = 'question' } = {}) {
    const r = await base.fire({
        title,
        text: texto,
        icon,
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText: 'Cancelar',
    });
    return r.isConfirmed;
}

const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
});

export function toastExito(texto) {
    toast.fire({ icon: 'success', title: texto });
}

export function toastError(texto) {
    toast.fire({ icon: 'error', title: texto });
}

export default { confirmarEliminar, confirmarAccion, toastExito, toastError };
