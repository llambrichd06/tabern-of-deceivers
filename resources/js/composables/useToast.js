/**
 * Composable para gestión centralizada de notificaciones Toast
 * Simplifica el uso de PrimeVue Toast en todo el proyecto
 */

import { useToast as usePrimeToast } from 'primevue/usetoast'

/**
 * Configuración por defecto de los toasts
 */
const DEFAULT_CONFIG = {
  life: 3000, // 3 segundos
  closable: true
}

/**
 * Composable useToast
 * Proporciona métodos simplificados para mostrar notificaciones
 */
export function useToast() {
  const toast = usePrimeToast()

  /**
   * Mostrar notificación de éxito
   * @param {string} message - Mensaje principal
   * @param {string} detail - Mensaje detallado (opcional)
   * @param {number} life - Duración en ms (opcional)
   */
  const success = (message, detail = null, life = DEFAULT_CONFIG.life) => {
    toast.add({
      severity: 'success',
      summary: message,
      detail: detail,
      life: life,
      closable: DEFAULT_CONFIG.closable
    })
  }

  /**
   * Mostrar notificación de error
   * @param {string} message - Mensaje principal
   * @param {string} detail - Mensaje detallado (opcional)
   * @param {number} life - Duración en ms (opcional, por defecto 5s para errores)
   */
  const error = (message, detail = null, life = 5000) => {
    toast.add({
      severity: 'error',
      summary: message,
      detail: detail,
      life: life,
      closable: DEFAULT_CONFIG.closable
    })
  }

  /**
   * Mostrar notificación de advertencia
   * @param {string} message - Mensaje principal
   * @param {string} detail - Mensaje detallado (opcional)
   * @param {number} life - Duración en ms (opcional)
   */
  const warning = (message, detail = null, life = DEFAULT_CONFIG.life) => {
    toast.add({
      severity: 'warn',
      summary: message,
      detail: detail,
      life: life,
      closable: DEFAULT_CONFIG.closable
    })
  }

  /**
   * Mostrar notificación informativa
   * @param {string} message - Mensaje principal
   * @param {string} detail - Mensaje detallado (opcional)
   * @param {number} life - Duración en ms (opcional)
   */
  const info = (message, detail = null, life = DEFAULT_CONFIG.life) => {
    toast.add({
      severity: 'info',
      summary: message,
      detail: detail,
      life: life,
      closable: DEFAULT_CONFIG.closable
    })
  }

  /**
   * Mostrar notificación personalizada
   * @param {Object} options - Opciones completas del toast
   */
  const show = options => {
    toast.add({
      ...DEFAULT_CONFIG,
      ...options
    })
  }

  /**
   * Limpiar todas las notificaciones
   */
  const clear = () => {
    toast.removeAllGroups()
  }

  // Métodos específicos para operaciones CRUD comunes
  const crud = {
    created: (entity = 'Registro') => {
      success('Created', `${entity} created correctly`)
    },
    updated: (entity = 'Registro') => {
      success('Updated', `${entity} updated correctly`)
    },
    deleted: (entity = 'Registro') => {
      success('Deleted', `${entity} deleted correctly`)
    },
    loaded: (entity = 'Datos') => {
      success('Loaded', `${entity} loaded correctly`)
    },
    saved: (entity = 'Datos') => {
      success('Saved', `${entity} saved correctly`)
    },
    error: (action = 'realizar la operación') => {
      error('Error', `Unable to ${action}`)
    }
  }

  return {
    // Métodos básicos
    success,
    error,
    warning,
    info,
    show,
    clear,
    // Métodos CRUD
    crud
  }
}

