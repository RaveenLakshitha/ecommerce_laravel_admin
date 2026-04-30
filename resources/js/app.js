// resources/js/app.js (or bootstrap.js)

import './bootstrap';

// 1. jQuery first
import $ from 'jquery';
window.$ = window.jQuery = $;

import select2 from 'select2';
select2($);
import 'select2/dist/css/select2.min.css';

// 2. DataTables core + extensions
import DataTable from 'datatables.net-dt';
import 'datatables.net-responsive-dt';
import 'datatables.net-buttons-dt';

// HTML5 export + Print
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';

// 3. CSS
import 'datatables.net-dt/css/dataTables.dataTables.css';
import 'datatables.net-responsive-dt/css/responsive.dataTables.css';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';

// 4. Excel (JSZip)
import JSZip from 'jszip';
window.JSZip = JSZip;

// 5. PDF (pdfMake) — IMPORTANT: default imports + explicit vfs assignment
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

// Assign fonts immediately
pdfMake.vfs = pdfFonts.pdfMake.vfs;

// Expose globally BEFORE DataTables runs
window.pdfMake = pdfMake;

// 6. Make DataTable global
window.DataTable = DataTable;

// Global DataTables Error Handling
// This suppresses the default browser alert() and shows a premium notification instead.
DataTable.ext.errMode = function (settings, helpPage, message) {
    console.error('DataTables Error:', message);
    
    // Check if it's an Ajax error
    if (message.indexOf('Ajax error') !== -1) {
        if (typeof window.showNotification === 'function') {
            window.showNotification(
                'Data Sync Error', 
                'The system encountered an error while communicating with the server. Please check your connection or refresh the page.', 
                'error'
            );
        }
    }
};

// Global DataTable Refresh Utility
window.refreshAllTables = function() {
    $('.dataTable').DataTable().ajax.reload(null, false);
};

// Optional: nicer buttons layout
DataTable.Buttons.defaults.dom.container.className = 'dt-buttons flex gap-2';

// 7. Alpine
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();