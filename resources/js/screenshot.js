/**
 * eSIR Screenshot Module
 * Fitur screenshot untuk semua halaman - Langsung download tanpa popup
 */

import html2canvas from 'html2canvas';

// Konfigurasi default
const screenshotConfig = {
    mode: 'auto',
    filename: 'eSIR_{page}_{date}_{time}',
    quality: 1.0,
    format: 'png',
    backgroundWhite: true,
    fullPage: false,
    scale: 2,
    debug: false
};

// Fungsi utama untuk mengambil screenshot
async function takeScreenshot(options = {}) {
    const config = { ...screenshotConfig, ...options };
    const element = document.body;
    const pageInfo = getPageInfo();
    
    try {
        log('📸 Memulai screenshot...', config);
        
        // Langsung ambil tanpa konfirmasi
        showLoading(true);
        
        const canvas = await html2canvas(element, {
            scale: config.scale,
            useCORS: true,
            allowTaint: true,
            backgroundColor: config.backgroundWhite ? '#ffffff' : null,
            logging: config.debug,
            scrollY: -window.scrollY,
            windowWidth: document.documentElement.scrollWidth,
            windowHeight: document.documentElement.scrollHeight,
            ignoreElements: (node) => {
                return node.id === 'screenshot-loader' || 
                       node.id === 'screenshot-btn' || 
                       node.id === 'screenshot-done';
            }
        });
        
        const filename = generateFilename(config.filename, pageInfo);
        const imageBlob = await canvasToBlob(canvas, config.format, config.quality);
        
        // Langsung download
        downloadImage(imageBlob, filename);
        log(`✅ Screenshot berhasil: ${filename}`);
        
        showLoading(false);
        showDoneNotification(filename);
        return { canvas, blob: imageBlob, filename, pageInfo };
        
    } catch (error) {
        log(`❌ Error: ${error.message}`);
        showLoading(false);
    }
}

// Getting page info automatically
function getPageInfo() {
    const titleSelectors = ['h1.page-title', 'h1.card-title', 'h1', '.navbar-brand', 'title'];
    let title = '';
    for (const selector of titleSelectors) {
        const el = document.querySelector(selector);
        if (el) {
            title = el.textContent?.trim() || el.innerText?.trim();
            if (title) break;
        }
    }
    if (!title) title = document.title || 'Page';
    title = title.replace(/[^a-zA-Z0-9]/g, '_').substring(0, 30);
    return { title, url: window.location.href, path: window.location.pathname };
}

// Generating filename
function generateFilename(template, pageInfo) {
    const now = new Date();
    const date = now.toISOString().split('T')[0];
    const time = now.toTimeString().split(' ')[0].replace(/:/g, '-');
    return template.replace('{page}', pageInfo.title || 'screenshot').replace('{date}', date).replace('{time}', time);
}

// Canvas to blob
function canvasToBlob(canvas, format, quality) {
    return new Promise((resolve) => {
        const mimeType = format === 'jpeg' ? 'image/jpeg' : 'image/png';
        canvas.toBlob(resolve, mimeType, quality);
    });
}

// Download image
function downloadImage(blob, filename) {
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `${filename}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

// Loading indicator
function showLoading(show) {
    const existing = document.getElementById('screenshot-loader');
    if (existing) existing.remove();
    
    if (show) {
        const loader = document.createElement('div');
        loader.id = 'screenshot-loader';
        loader.setAttribute('data-html2canvas-ignore', 'true');
        loader.innerHTML = `
            <div style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);display:flex;justify-content:center;align-items:center;z-index:99999;">
                <div style="background:white;padding:20px 40px;border-radius:10px;text-align:center;">
                    <div style="width:40px;height:40px;border:4px solid #4361ee;border-top-color:transparent;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 10px;"></div>
                    <p style="margin:0;font-weight:600;">Mengambil Screenshot...</p>
                </div>
            </div>
            <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
        `;
        document.body.appendChild(loader);
    }
}

// Success notification
function showDoneNotification(filename) {
    const existing = document.getElementById('screenshot-done');
    if (existing) existing.remove();
    
    const notif = document.createElement('div');
    notif.id = 'screenshot-done';
    notif.setAttribute('data-html2canvas-ignore', 'true');
    notif.innerHTML = `
        <div style="position:fixed;bottom:80px;right:20px;background:#06d6a0;color:white;padding:15px 25px;border-radius:10px;box-shadow:0 4px 14px rgba(0,0,0,0.2);z-index:99999;font-weight:600;animation:fadeIn 0.3s ease;">
            ✅ ${filename}.png tersimpan!
        </div>
        <style>@keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }</style>
    `;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
}

// Logging
function log(message, data = null) {
    if (screenshotConfig.debug) console.log(`[Screenshot] ${message}`, data || '');
}

// =========================================
// TOMBOL FLOATING
// =========================================

function initScreenshotButton() {
    if (document.getElementById('screenshot-btn')) return;
    
    const button = document.createElement('button');
    button.id = 'screenshot-btn';
    button.setAttribute('data-html2canvas-ignore', 'true');
    button.innerHTML = '📸';
    button.title = 'Ambil Screenshot (Tanpa Popup)';
    button.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #4361ee;
        color: white;
        border: none;
        box-shadow: 0 4px 14px 0 rgba(67, 97, 238, 0.4);
        cursor: pointer;
        z-index: 9999;
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
    `;
    
    button.addEventListener('click', () => takeScreenshot());
    button.addEventListener('mouseenter', () => { button.style.transform = 'scale(1.1)'; });
    button.addEventListener('mouseleave', () => { button.style.transform = 'scale(1)'; });
    
    document.body.appendChild(button);
    log('📸 Tombol screenshot siap');
}

// =========================================
// KEYBOARD SHORTCUT
// =========================================

document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.shiftKey && e.key === 'S') {
        e.preventDefault();
        takeScreenshot();
    }
});

// =========================================
// AUTO INIT
// =========================================

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScreenshotButton);
} else {
    initScreenshotButton();
}

log('📸 Screenshot module loaded');
