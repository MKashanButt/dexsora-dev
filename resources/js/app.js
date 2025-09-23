// DexSora Work Management App
import './bootstrap';

// Global app object
window.DexSora = {
    // Initialize the app
    init() {
        this.setupEventListeners();
        this.setupTooltips();
    },

    // Setup global event listeners
    setupEventListeners() {
        // Add any global event listeners here
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DexSora app initialized');
        });
    },

    // Setup tooltips
    setupTooltips() {
        // Add tooltip functionality if needed
    },

    // Show notification
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
};

// Initialize the app
DexSora.init();
