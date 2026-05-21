/**
 * UPTEC - Sistema de Control de Cursos v2.0
 * Dashboard Core - Funcionalidades compartidas de vistas
 *
 * Maneja sidebar, header, notificaciones, modales, tabla de datos
 */

(function() {
    'use strict';

    // ============================================
    // TOAST NOTIFICATIONS
    // ============================================
    const Toast = {
        container: null,

        init() {
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.className = 'toast-container';
                document.body.appendChild(this.container);
            }
        },

        show(message, type = 'success', duration = 4000) {
            this.init();

            const icons = {
                success: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38a169" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>',
                error: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e53e3e" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                warning: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dd6b20" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                info: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3182ce" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            };

            const toast = document.createElement('div');
            toast.className = `toast toast--${type}`;
            toast.innerHTML = `
                <span class="alert__icon">${icons[type]}</span>
                <span class="alert__text">${message}</span>
            `;

            this.container.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out forwards';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    };

    // ============================================
    // MODAL
    // ============================================
    const Modal = {
        open(content, options = {}) {
            const size = options.size || '';
            const title = options.title || '';

            // Cerrar modal existente
            this.close();

            const overlay = document.createElement('div');
            overlay.className = 'modal-overlay active';
            overlay.id = 'active-modal';
            overlay.innerHTML = `
                <div class="modal ${size}">
                    <div class="modal__header">
                        <h3 class="modal__title">${title}</h3>
                        <button class="modal__close" onclick="Modal.close()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <div class="modal__body">${content}</div>
                    ${options.footer ? `<div class="modal__footer">${options.footer}</div>` : ''}
                </div>
            `;

            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) this.close();
            });

            document.body.appendChild(overlay);
            document.body.style.overflow = 'hidden';
        },

        close() {
            const modal = document.getElementById('active-modal');
            if (modal) {
                modal.remove();
                document.body.style.overflow = '';
            }
        }
    };

    // ============================================
    // CONFIRM DIALOG
    // ============================================
    const ConfirmDialog = {
        open(message, onConfirm, onCancel) {
            const content = `
                <div class="text-center" style="padding: 1rem 0;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                    <p style="font-size: 1.0625rem; color: var(--color-text-primary);">${message}</p>
                </div>
            `;

            const footer = `
                <button class="btn btn--ghost" onclick="Modal.close(); ${onCancel ? 'window._onCancelFn()' : ''}">Cancelar</button>
                <button class="btn btn--danger" id="confirm-btn">Confirmar</button>
            `;

            if (onCancel) window._onCancelFn = onCancel;

            Modal.open(content, { title: 'Confirmar accion', footer });

            setTimeout(() => {
                const btn = document.getElementById('confirm-btn');
                if (btn) {
                    btn.addEventListener('click', () => {
                        Modal.close();
                        if (onConfirm) onConfirm();
                    });
                }
            }, 50);
        }
    };

    // ============================================
    // SIDEBAR
    // ============================================
    const Sidebar = {
        init(rol) {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            const menus = {
                'Administrador': [
                    { label: 'Dashboard', icon: '📊', href: '/uptec-cursos/frontend/views/administrador/dashboard.html', section: 'Principal' },
                    { label: 'Usuarios', icon: '👥', href: '/uptec-cursos/frontend/views/administrador/usuarios.html', section: 'Gestion' },
                    { label: 'Cursos', icon: '📚', href: '/uptec-cursos/frontend/views/administrador/cursos.html' },
                    { label: 'Inscripciones', icon: '📝', href: '/uptec-cursos/frontend/views/administrador/inscripciones.html' },
                    { label: 'Calificaciones', icon: '📋', href: '/uptec-cursos/frontend/views/administrador/calificaciones.html' },
                    { label: 'Reportes', icon: '📈', href: '/uptec-cursos/frontend/views/administrador/reportes.html', section: 'Reportes' },
                    { label: 'Auditoria', icon: '🔍', href: '/uptec-cursos/frontend/views/administrador/auditoria.html' },
                    { label: 'Respaldo', icon: '💾', href: '/uptec-cursos/frontend/views/administrador/backup.html' }
                ],
                'Analista': [
                    { label: 'Dashboard', icon: '📊', href: '/uptec-cursos/frontend/views/analista/dashboard.html', section: 'Principal' },
                    { label: 'Cursos', icon: '📚', href: '/uptec-cursos/frontend/views/analista/cursos.html', section: 'Gestion' },
                    { label: 'Facilitadores', icon: '👨‍🏫', href: '/uptec-cursos/frontend/views/analista/facilitadores.html' },
                    { label: 'Usuarios', icon: '👥', href: '/uptec-cursos/frontend/views/administrador/usuarios.html' },
                    { label: 'Verificacion', icon: '✅', href: '/uptec-cursos/frontend/views/analista/verificacion.html' },
                    { label: 'Reportes', icon: '📈', href: '/uptec-cursos/frontend/views/analista/reportes.html', section: 'Reportes' }
                ],
                'Facilitador': [
                    { label: 'Dashboard', icon: '📊', href: '/uptec-cursos/frontend/views/facilitador/dashboard.html', section: 'Principal' },
                    { label: 'Mis Cursos', icon: '📚', href: '/uptec-cursos/frontend/views/facilitador/mis-cursos.html', section: 'Gestion' },
                    { label: 'Estudiantes', icon: '👨‍🎓', href: '/uptec-cursos/frontend/views/facilitador/estudiantes.html' },
                    { label: 'Calificaciones', icon: '📋', href: '/uptec-cursos/frontend/views/facilitador/calificaciones.html' },
                    { label: 'Reportes', icon: '📈', href: '/uptec-cursos/frontend/views/facilitador/reportes.html', section: 'Reportes' }
                ],
                'Participante': [
                    { label: 'Dashboard', icon: '📊', href: '/uptec-cursos/frontend/views/participante/dashboard.html', section: 'Principal' },
                    { label: 'Mis Cursos', icon: '📚', href: '/uptec-cursos/frontend/views/participante/mis-cursos.html', section: 'Gestion' },
                    { label: 'Inscribirme', icon: '📝', href: '/uptec-cursos/frontend/views/participante/inscribir.html' },
                    { label: 'Mis Notas', icon: '📋', href: '/uptec-cursos/frontend/views/participante/mis-notas.html' },
                    { label: 'Certificados', icon: '🏆', href: '/uptec-cursos/frontend/views/participante/certificados.html', section: 'Reportes' }
                ]
            };

            const items = menus[rol] || [];
            let html = `
                <div class="sidebar__brand">
                    <div class="sidebar__brand-icon">frontend\img\logo-uptec-grande.png</div>
                    <div>
                        <div class="sidebar__brand-text">UPTEC</div>
                        <div class="sidebar__brand-sub">Cursos</div>
                    </div>
                </div>
                <ul class="sidebar__nav">
            `;

            let currentSection = '';
            items.forEach(item => {
                if (item.section && item.section !== currentSection) {
                    currentSection = item.section;
                    html += `<li class="sidebar__section">${item.section}</li>`;
                }
                const isActive = window.location.pathname.includes(item.href);
                html += `
                    <li>
                        <a href="${item.href}" class="sidebar__link ${isActive ? 'active' : ''}">
                            <span class="sidebar__link-icon">${item.icon}</span>
                            <span>${item.label}</span>
                        </a>
                    </li>
                `;
            });

            html += `</ul>
                <div class="sidebar__footer">
                    <div>v2.0 UPTEC</div>
                    <div style="font-size: 0.75rem; opacity: 0.7;">Caracas, Venezuela</div>
                </div>
            `;

            sidebar.innerHTML = html;
        }
    };

    // ============================================
    // HEADER
    // ============================================
    const Header = {
        init() {
            const headerUser = document.getElementById('header-user');
            const headerRole = document.getElementById('header-role');
            const headerAvatar = document.getElementById('header-avatar');

            if (AUTH.user) {
                if (headerUser) headerUser.textContent = AUTH.user.nombre + ' ' + AUTH.user.apellidos;
                if (headerRole) headerRole.textContent = AUTH.user.rol;
                if (headerAvatar) headerAvatar.textContent = (AUTH.user.nombre[0] + AUTH.user.apellidos[0]).toUpperCase();
            }

            // Logout
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => AUTH.logout());
            }

            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    document.getElementById('sidebar').classList.toggle('open');
                    document.getElementById('sidebar-overlay').classList.toggle('open');
                });
            }

            const sidebarOverlay = document.getElementById('sidebar-overlay');
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', () => {
                    document.getElementById('sidebar').classList.remove('open');
                    sidebarOverlay.classList.remove('open');
                });
            }
        }
    };

    // ============================================
    // TABLAS
    // ============================================
    const DataTable = {
        render(containerId, data, columns, options = {}) {
            const container = document.getElementById(containerId);
            if (!container) return;

            if (!data || data.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state__icon">📭</div>
                        <div class="empty-state__title">No hay datos</div>
                        <div class="empty-state__text">${options.emptyText || 'No se encontraron registros'}</div>
                    </div>
                `;
                return;
            }

            let html = '<div class="table-container"><table class="table">';

            // Header
            html += '<thead><tr>';
            columns.forEach(col => {
                html += `<th>${col.label}</th>`;
            });
            if (options.actions) html += '<th>Acciones</th>';
            html += '</tr></thead>';

            // Body
            html += '<tbody>';
            data.forEach((row, idx) => {
                html += '<tr>';
                columns.forEach(col => {
                    let value = this._getValue(row, col.key);
                    if (col.format) value = col.format(value, row);
                    if (col.badge) {
                        const badgeClass = col.badge[value] || 'badge--neutral';
                        value = `<span class="badge ${badgeClass}">${value || '-'}</span>`;
                    }
                    html += `<td>${value !== null && value !== undefined ? value : '-'}</td>`;
                });
                if (options.actions) {
                    html += '<td>';
                    options.actions.forEach(action => {
                        const btnClass = action.class || 'btn btn--sm btn--ghost';
                        html += `<button class="${btnClass}" onclick="${action.onClick(row, idx)}" title="${action.title || ''}">${action.icon || action.label}</button> `;
                    });
                    html += '</td>';
                }
                html += '</tr>';
            });
            html += '</tbody></table></div>';

            container.innerHTML = html;
        },

        _getValue(obj, path) {
            return path.split('.').reduce((o, p) => o && o[p], obj);
        }
    };

    // ============================================
    // SPINNER / LOADING
    // ============================================
    const Loading = {
        show(containerId) {
            const container = document.getElementById(containerId);
            if (container) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="spinner spinner--lg" style="margin: 0 auto 1rem;"></div>
                        <div class="empty-state__text">Cargando...</div>
                    </div>
                `;
            }
        },

        hide(containerId) {
            // El contenido se reemplaza por el render normal
        }
    };

    // ============================================
    // UTILIDADES
    // ============================================
    const Utils = {
        formatDate(date) {
            if (!date) return '-';
            const d = new Date(date);
            return d.toLocaleDateString('es-VE', { year: 'numeric', month: 'short', day: 'numeric' });
        },

        formatDateTime(date) {
            if (!date) return '-';
            const d = new Date(date);
            return d.toLocaleDateString('es-VE', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
        },

        formatNumber(num) {
            if (num === null || num === undefined) return '-';
            return Number(num).toLocaleString('es-VE');
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // ============================================
    // INICIALIZACION
    // ============================================
    async function initDashboard() {
        // Inicializar API y auth
        await API.init();
        const isAuth = await AUTH.check();

        if (!isAuth) {
            window.location.href = '/uptec-cursos/frontend/login.html';
            return;
        }

        // Inicializar sidebar y header
        Sidebar.init(AUTH.getRole());
        Header.init();
    }

    // Exponer globalmente
    window.Toast = Toast;
    window.Modal = Modal;
    window.ConfirmDialog = ConfirmDialog;
    window.Sidebar = Sidebar;
    window.Header = Header;
    window.DataTable = DataTable;
    window.Loading = Loading;
    window.Utils = Utils;
    window.initDashboard = initDashboard;

})();
