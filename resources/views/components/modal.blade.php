{{-- Modal Component --}}
<div id="modalOverlay" class="modal-overlay" onclick="closeModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Konfirmasi</h3>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
        <div class="modal-icon" id="modalIcon">
    <i data-lucide="alert-triangle"></i>
</div>
        <p class="modal-message" id="modalMessage">Apakah Anda yakin ingin melanjutkan?</p>
        </div>
        <div class="modal-footer" id="modalFooter">
            <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Batal</button>
            <button class="modal-btn modal-btn-confirm" id="modalConfirmBtn" onclick="confirmAction()">Konfirmasi</button>
        </div>
    </div>
</div>

{{-- Success Toast Notification --}}
<div id="toastContainer" class="toast-container"></div>

<style>
    /* Modal Overlay */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s ease;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-container {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }
    
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #F8B803 0%, #E6A500 100%);
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #4A2D0F;
        margin: 0;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #4A2D0F;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        line-height: 1;
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .modal-body {
        padding: 2rem 1.5rem;
        text-align: center;
    }
    
     .modal-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        border: 4px solid #FEF3C7;
        margin-bottom: 1.5rem;
        animation: scaleIn 0.3s ease;
        box-shadow: 0 8px 20px rgba(248, 184, 3, 0.2);
    }
    
    .modal-icon svg {
        width: 40px;
        height: 40px;
        color: #F59E0B;
        stroke-width: 2.5;
    }
    
    .modal-message {
        font-size: 1rem;
        color: #1F2937;
        line-height: 1.6;
        margin: 0;
    }
    
    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid #E5E7EB;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        background: #F9FAFB;
    }
    
    .modal-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .modal-btn-cancel {
        background: #E5E7EB;
        color: #374151;
    }
    
    .modal-btn-cancel:hover {
        background: #D1D5DB;
    }
    
    .modal-btn-confirm {
        background: #F8B803;
        color: #4A2D0F;
    }
    
    .modal-btn-confirm:hover {
        background: #E6A500;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(248, 184, 3, 0.4);
    }
    
    .modal-btn-danger {
        background: #DC2626;
        color: white;
    }
    
    .modal-btn-danger:hover {
        background: #B91C1C;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
    }
    
    /* Toast Notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .toast {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 300px;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid;
    }
    
    .toast.success {
        border-left-color: #10B981;
    }
    
    .toast.error {
        border-left-color: #DC2626;
    }
    
    .toast.info {
        border-left-color: #3B82F6;
    }
    
    .toast.warning {
        border-left-color: #F59E0B;
    }
    
    .toast-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .toast-content {
        flex: 1;
    }
    
    .toast-title {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }
    
    .toast-message {
        color: #6B7280;
        font-size: 0.85rem;
    }
    
    .toast-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #9CA3AF;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }
    
    .toast-close:hover {
        color: #374151;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0.5);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .modal-container {
            width: 95%;
            margin: 1rem;
        }
        
        .modal-body {
            padding: 1.5rem 1rem;
        }
        
        .modal-icon {
            font-size: 3rem;
        }
        
        .toast {
            min-width: 280px;
            max-width: calc(100vw - 40px);
        }
        
        .toast-container {
            right: 10px;
            top: 10px;
        }
    }
</style>

<script>
    // Modal State
    let modalState = {
        type: 'confirm',
        title: '',
        message: '',
        icon: '',
        confirmText: 'Konfirmasi',
        onConfirm: null,
        isDanger: false
    };
    
    // Show Modal
    function showModal(options) {
        modalState = {
            type: options.type || 'confirm',
            title: options.title || 'Konfirmasi',
            message: options.message || 'Apakah Anda yakin ingin melanjutkan?',
            icon: options.icon || 'alert-triangle',
            confirmText: options.confirmText || 'Konfirmasi',
            onConfirm: options.onConfirm || null,
            isDanger: options.isDanger || false
        };
        
        const overlay = document.getElementById('modalOverlay');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const icon = document.getElementById('modalIcon');
        const confirmBtn = document.getElementById('modalConfirmBtn');
        
        title.textContent = modalState.title;
        message.textContent = modalState.message;
icon.innerHTML = `<i data-lucide="${modalState.icon}"></i>`;
lucide.createIcons();
        confirmBtn.textContent = modalState.confirmText;
        
        if (modalState.isDanger) {
            confirmBtn.classList.add('modal-btn-danger');
            confirmBtn.classList.remove('modal-btn-confirm');
        } else {
            confirmBtn.classList.remove('modal-btn-danger');
            confirmBtn.classList.add('modal-btn-confirm');
        }
        
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Close Modal
    function closeModal() {
        const overlay = document.getElementById('modalOverlay');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        modalState.onConfirm = null;
    }
    
    // Confirm Action
    function confirmAction() {
        if (modalState.onConfirm) {
            modalState.onConfirm();
        }
        closeModal();
    }
    
    // Show Delete Confirmation
    function showDeleteConfirmation(itemName, itemId, onConfirm) {
        showModal({
            type: 'delete',
            title: 'Hapus Data',
            message: `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`,
            icon: '🗑️',
            confirmText: 'Hapus',
            isDanger: true,
            onConfirm: onConfirm
        });
    }
    
    // Toast Notification
    function showToast(type, title, message, duration = 3000) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icons = {
            success: '✅',
            error: '❌',
            info: 'ℹ️',
            warning: '⚠️'
        };
        
        toast.innerHTML = `
            <div class="toast-icon">${icons[type] || icons.info}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after duration
        setTimeout(() => {
            toast.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, duration);
    }
    
    // Success Toast
    function showSuccessToast(title, message) {
        showToast('success', title, message);
    }
    
    // Error Toast
    function showErrorToast(title, message) {
        showToast('error', title, message);
    }
    
    // Info Toast
    function showInfoToast(title, message) {
        showToast('info', title, message);
    }
    
    // Warning Toast
    function showWarningToast(title, message) {
        showToast('warning', title, message);
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>

