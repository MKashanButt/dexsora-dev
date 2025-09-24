// Sheets Table JavaScript - Lazy Loaded for Performance
// Initialize data from the server (will be set by the component)
let currentData = [];
let headers = [];
let saveTimeout = null;

function addRow() {
    console.log("Adding new row...");
    const newRow = new Array(headers.length).fill("");
    currentData.push(newRow);

    const tbody = document.getElementById("tableBody");
    const rowIndex = currentData.length - 1;

    const newRowElement = document.createElement("tr");
    newRowElement.className =
        "group transition-all duration-500 hover:shadow-lg animate-slide-up";
    newRowElement.setAttribute("data-row", rowIndex);

    let rowHtml = "";

    // Add checkbox column
    rowHtml += `
        <td class="px-6 py-4 border border-gray-200 text-center">
            <input type="checkbox" class="row-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" value="${rowIndex}" onchange="updateRowSelection()">
        </td>
    `;

    headers.forEach((header, headerIndex) => {
        rowHtml += `
            <td class="px-6 py-4 border border-gray-200 relative">
                <div class="relative">
                    <input type="text" 
                           value="" 
                           class="w-full border-0 bg-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none px-2 py-2 focus:bg-white transition-all duration-200 text-gray-700 font-normal placeholder-gray-400"
                           onchange="updateCell(${rowIndex}, ${headerIndex}, this.value)"
                           onblur="saveData()"
                           onfocus="highlightRow(${rowIndex})"
                           onblur="unhighlightRow(${rowIndex})"
                           placeholder="Enter ${header.toLowerCase()}">
                </div>
            </td>
        `;
    });

    rowHtml += `
        <td class="px-6 py-4 border border-gray-200 text-sm font-medium">
            <button type="button" onclick="deleteRow(${rowIndex})" class="text-gray-400 hover:text-red-600 p-1.5 rounded hover:bg-gray-100 transition-all duration-200 group/delete">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </td>
    `;

    newRowElement.innerHTML = rowHtml;
    tbody.appendChild(newRowElement);

    // Hide empty state if it exists
    const emptyState = document.getElementById("emptyState");
    if (emptyState) {
        emptyState.style.display = "none";
    }

    console.log("Row added, current data:", currentData);

    // Auto-save after adding row
    setTimeout(() => saveData(), 100);
    window.location.reload();
}

function deleteRow(rowIndex) {
    console.log("deleteRow called with index:", rowIndex);
    console.log("Current data length:", currentData.length);

    if (confirm("Are you sure you want to delete this row?")) {
        // Remove from currentData array
        currentData.splice(rowIndex, 1);

        // Remove the row element from DOM
        const rowElement = document.querySelector(`[data-row="${rowIndex}"]`);
        console.log("Found row element:", rowElement);
        if (rowElement) {
            rowElement.remove();
            console.log("Row element removed from DOM");
        } else {
            console.log("Row element not found in DOM");
        }

        // Update data-row attributes for remaining rows to fix indexing
        const remainingRows = document.querySelectorAll("[data-row]");
        console.log("Remaining rows found:", remainingRows.length);
        remainingRows.forEach((row, index) => {
            row.setAttribute("data-row", index);

            // Update the delete button onclick
            const deleteButton = row.querySelector(
                'button[onclick^="deleteRow"]'
            );
            if (deleteButton) {
                deleteButton.setAttribute("onclick", `deleteRow(${index})`);
                console.log(`Updated delete button for row ${index}`);
            }

            // Update all input onchange and onblur handlers
            const inputs = row.querySelectorAll("input");
            inputs.forEach((input, inputIndex) => {
                if (input.classList.contains("row-checkbox")) {
                    // Update checkbox value and onchange handler
                    input.setAttribute("value", index);
                    input.setAttribute("onchange", "updateRowSelection()");
                } else {
                    // Update regular input handlers
                    input.setAttribute(
                        "onchange",
                        `updateCell(${index}, ${inputIndex - 1}, this.value)` // -1 because of checkbox column
                    );
                    input.setAttribute("onblur", "saveData()");
                }
            });
        });

        // Update multi-delete button state after row deletion
        if (typeof updateMultiDeleteButton === "function") {
            updateMultiDeleteButton();
        }

        // Show empty state if no rows left
        if (currentData.length === 0) {
            const emptyState = document.getElementById("emptyState");
            if (emptyState) {
                emptyState.style.display = "block";
            }
        }

        console.log("Row deleted, current data:", currentData);
        saveData();
    } else {
        console.log("Delete cancelled by user");
    }
}

function updateCell(rowIndex, headerIndex, value) {
    if (!currentData[rowIndex]) {
        currentData[rowIndex] = new Array(headers.length).fill("");
    }
    currentData[rowIndex][headerIndex] = value;

    // Debounce save to avoid too many API calls
    if (saveTimeout) {
        clearTimeout(saveTimeout);
    }
    saveTimeout = setTimeout(() => saveData(), 500);
}

function saveData() {
    // Clear any existing timeout
    if (saveTimeout) {
        clearTimeout(saveTimeout);
    }

    showSaveStatus("Saving...", "loading");

    const url = window.sheetUpdateUrl; // Will be set by the component
    const csrfToken = window.csrfToken; // Will be set by the component

    fetch(url, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({
            data: currentData,
        }),
    })
        .then((response) => {
            console.log("Response status:", response.status);
            console.log("Response headers:", response.headers);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Response data:", data);
            if (data.success) {
                showSaveStatus("Saved successfully!", "success");
            } else {
                throw new Error("Save failed");
            }
        })
        .catch((error) => {
            console.error("Error saving data:", error);
            showSaveStatus("Error saving data!", "error");
        });
}

function showSaveStatus(message, type) {
    const statusDiv = document.getElementById("saveStatus");
    const messageSpan = document.getElementById("saveMessage");
    const iconDiv = document.getElementById("saveIcon");

    // Set message
    messageSpan.textContent = message;

    // Set icon based on type
    let iconHtml = "";
    if (type === "loading") {
        iconHtml =
            '<div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center"><svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
    } else if (type === "success") {
        iconHtml =
            '<div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center"><svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>';
    } else if (type === "error") {
        iconHtml =
            '<div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center"><svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>';
    }

    iconDiv.innerHTML = iconHtml;

    // Show status
    statusDiv.classList.remove("hidden");

    // Auto-hide after appropriate time
    const hideDelay =
        type === "loading" ? 2000 : type === "success" ? 2000 : 4000;
    setTimeout(() => {
        statusDiv.classList.add("hidden");
    }, hideDelay);
}

// Row highlighting functions
function highlightRow(rowIndex) {
    const row = document.querySelector(`[data-row="${rowIndex}"]`);
    if (row) {
        row.style.boxShadow =
            "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)";
    }
}

function unhighlightRow(rowIndex) {
    const row = document.querySelector(`[data-row="${rowIndex}"]`);
    if (row) {
        row.style.boxShadow = "none";
        row.style.zIndex = "auto";
    }
}

// Enhanced delete row with animation
function deleteRowWithAnimation(rowIndex) {
    const row = document.querySelector(`[data-row="${rowIndex}"]`);
    if (row) {
        // Add exit animation
        row.style.transition = "all 0.3s ease-in";
        row.style.transform = "translateX(100px)";
        row.style.opacity = "0";

        setTimeout(() => {
            deleteRow(rowIndex);
        }, 300);
    } else {
        deleteRow(rowIndex);
    }
}

// Toggle export menu visibility
function toggleExportMenu() {
    const menu = document.getElementById("exportMenu");
    if (menu) {
        const isVisible = menu.classList.contains("opacity-100");
        if (isVisible) {
            menu.classList.remove("opacity-100", "visible");
            menu.classList.add("opacity-0", "invisible");
        } else {
            menu.classList.remove("opacity-0", "invisible");
            menu.classList.add("opacity-100", "visible");
        }
    }
}

// Export table data
function exportTable(format = "csv") {
    try {
        console.log("Exporting table data...");
        console.log("Current data:", currentData);
        console.log("Headers:", headers);

        let content, mimeType, fileExtension;

        switch (format) {
            case "csv":
                content = generateCSV();
                mimeType = "text/csv;charset=utf-8;";
                fileExtension = "csv";
                break;
            case "json":
                content = generateJSON();
                mimeType = "application/json;charset=utf-8;";
                fileExtension = "json";
                break;
            case "excel":
                content = generateExcel();
                mimeType =
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                fileExtension = "xlsx";
                break;
            default:
                content = generateCSV();
                mimeType = "text/csv;charset=utf-8;";
                fileExtension = "csv";
        }

        console.log(`Generated ${format.toUpperCase()} content:`, content);

        if (!content || content.trim() === "") {
            alert(
                "No data to export. Please add some data to the table first."
            );
            return;
        }

        // Create blob with BOM for proper UTF-8 encoding (for CSV)
        let blob;
        if (format === "csv") {
            const BOM = "\uFEFF";
            blob = new Blob([BOM + content], { type: mimeType });
        } else {
            blob = new Blob([content], { type: mimeType });
        }

        // Create download link
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);

        // Generate filename with current date
        const now = new Date();
        const dateStr = now.toISOString().split("T")[0];
        const timeStr = now.toTimeString().split(" ")[0].replace(/:/g, "-");
        const filename = `sheet_${dateStr}_${timeStr}.${fileExtension}`;

        link.setAttribute("href", url);
        link.setAttribute("download", filename);
        link.style.visibility = "hidden";

        // Add to DOM and trigger download
        document.body.appendChild(link);

        // Try to trigger download
        try {
            link.click();
        } catch (clickError) {
            console.warn(
                "Download click failed, trying alternative method:",
                clickError
            );
            // Fallback: open in new window for manual download
            window.open(url, "_blank");
        }

        // Cleanup
        setTimeout(() => {
            if (document.body.contains(link)) {
                document.body.removeChild(link);
            }
            URL.revokeObjectURL(url);
        }, 100);

        // Show success message
        showSaveStatus(
            `${format.toUpperCase()} export completed successfully!`,
            "success"
        );

        console.log(`${format.toUpperCase()} export completed successfully`);
    } catch (error) {
        console.error("Export error:", error);
        alert("Error exporting data: " + error.message);
        showSaveStatus("Export failed!", "error");
    }
}

// Generate CSV content
function generateCSV() {
    try {
        if (!headers || headers.length === 0) {
            throw new Error("No headers found");
        }

        if (!currentData || currentData.length === 0) {
            throw new Error("No data found");
        }

        let csv = "";

        // Add headers row
        const headerRow = headers.map((header) => {
            // Clean and escape header text
            const cleanHeader = header.toString().trim();
            if (
                cleanHeader.includes(",") ||
                cleanHeader.includes('"') ||
                cleanHeader.includes("\n")
            ) {
                return `"${cleanHeader.replace(/"/g, '""')}"`;
            }
            return cleanHeader;
        });
        csv += headerRow.join(",") + "\r\n";

        // Add data rows
        currentData.forEach((row, rowIndex) => {
            if (row && Array.isArray(row)) {
                const rowData = headers.map((header, index) => {
                    const value = row[index] || "";
                    const cleanValue = value.toString().trim();

                    // Escape commas, quotes, and newlines in CSV
                    if (
                        cleanValue.includes(",") ||
                        cleanValue.includes('"') ||
                        cleanValue.includes("\n")
                    ) {
                        return `"${cleanValue.replace(/"/g, '""')}"`;
                    }
                    return cleanValue;
                });
                csv += rowData.join(",") + "\r\n";
            }
        });

        return csv;
    } catch (error) {
        console.error("CSV generation error:", error);
        throw new Error("Failed to generate CSV: " + error.message);
    }
}

// Generate JSON content
function generateJSON() {
    try {
        if (!headers || headers.length === 0) {
            throw new Error("No headers found");
        }

        if (!currentData || currentData.length === 0) {
            throw new Error("No data found");
        }

        const jsonData = {
            sheet_name: window.sheetName || "Sheet", // Will be set by the component
            export_date: new Date().toISOString(),
            headers: headers,
            data: currentData.map((row) => {
                const rowObj = {};
                headers.forEach((header, index) => {
                    rowObj[header] = row[index] || "";
                });
                return rowObj;
            }),
        };

        return JSON.stringify(jsonData, null, 2);
    } catch (error) {
        console.error("JSON generation error:", error);
        throw new Error("Failed to generate JSON: " + error.message);
    }
}

// Generate Excel-like content (CSV with better formatting)
function generateExcel() {
    try {
        if (!headers || headers.length === 0) {
            throw new Error("No headers found");
        }

        if (!currentData || currentData.length === 0) {
            throw new Error("No data found");
        }

        let excelContent = "";

        // Add headers row with better formatting
        const headerRow = headers.map((header) => {
            const cleanHeader = header.toString().trim();
            if (
                cleanHeader.includes(",") ||
                cleanHeader.includes('"') ||
                cleanHeader.includes("\n")
            ) {
                return `"${cleanHeader.replace(/"/g, '""')}"`;
            }
            return cleanHeader;
        });
        excelContent += headerRow.join(",") + "\r\n";

        // Add data rows with better formatting
        currentData.forEach((row, rowIndex) => {
            if (row && Array.isArray(row)) {
                const rowData = headers.map((header, index) => {
                    const value = row[index] || "";
                    const cleanValue = value.toString().trim();

                    // Escape commas, quotes, and newlines in CSV
                    if (
                        cleanValue.includes(",") ||
                        cleanValue.includes('"') ||
                        cleanValue.includes("\n")
                    ) {
                        return `"${cleanValue.replace(/"/g, '""')}"`;
                    }
                    return cleanValue;
                });
                excelContent += rowData.join(",") + "\r\n";
            }
        });

        return excelContent;
    } catch (error) {
        console.error("Excel generation error:", error);
        throw new Error("Failed to generate Excel: " + error.message);
    }
}

// Close modal function for HTMX
function closeModal() {
    const modalContainer = document.getElementById("modalContainer");
    if (modalContainer) {
        modalContainer.classList.add("hidden");
        modalContainer.innerHTML = "";
    }
}

// Enhanced cell editing with validation
function updateCellWithValidation(rowIndex, headerIndex, value) {
    // Basic validation - you can extend this
    if (value.length > 1000) {
        alert("Cell value is too long. Maximum 1000 characters allowed.");
        return false;
    }

    updateCell(rowIndex, headerIndex, value);
    return true;
}

// Search and filter functionality
function searchTable(query) {
    const rows = document.querySelectorAll("[data-row]");
    const searchTerm = query.toLowerCase();

    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll("input");
        let hasMatch = false;

        inputs.forEach((input) => {
            if (input.value.toLowerCase().includes(searchTerm)) {
                hasMatch = true;
            }
        });

        if (hasMatch) {
            row.style.display = "";
            row.style.opacity = "1";
        } else {
            row.style.opacity = "0.3";
        }
    });
}

// Initialize the table
function initializeTable(
    sheetData,
    sheetHeaders,
    updateUrl,
    csrfToken,
    sheetName
) {
    // Set global variables
    currentData = sheetData || [];
    headers = sheetHeaders || [];
    window.sheetUpdateUrl = updateUrl;
    window.csrfToken = csrfToken;
    window.sheetName = sheetName;

    console.log(
        "Sheet table initialized with",
        currentData.length,
        "rows and",
        headers.length,
        "headers"
    );
    console.log(
        "All functions loaded:",
        typeof addRow,
        typeof updateCell,
        typeof saveData
    );

    // HTMX event listeners for modal management
    document.body.addEventListener("htmx:beforeRequest", function (evt) {
        console.log("HTMX beforeRequest:", evt.detail);
        if (evt.target.getAttribute("hx-target") === "#modalContainer") {
            const modalContainer = document.getElementById("modalContainer");
            if (modalContainer) {
                modalContainer.classList.remove("hidden");
                console.log("Modal container shown");
            }
        }
    });

    // Handle table clearing via HTMX
    document.body.addEventListener("htmx:afterSwap", function (evt) {
        console.log("HTMX afterSwap:", evt.detail);
        if (evt.target.id === "tableBody") {
            // Update the currentData variable when table is cleared
            currentData = [];
            console.log("Table cleared, currentData reset:", currentData);
        }
    });

    // Handle HTMX errors
    document.body.addEventListener("htmx:responseError", function (evt) {
        console.error("HTMX response error:", evt.detail);
        alert(
            "An error occurred while processing your request. Please try again."
        );
    });

    // Close export menu when clicking outside
    document.addEventListener("click", function (event) {
        const exportMenu = document.getElementById("exportMenu");
        const exportButton = event.target.closest(".relative.group");

        if (exportMenu && !exportButton) {
            exportMenu.classList.remove("opacity-100", "visible");
            exportMenu.classList.add("opacity-0", "invisible");
        }

        // Close modal when clicking outside
        const modalContainer = document.getElementById("modalContainer");
        if (modalContainer && !modalContainer.classList.contains("hidden")) {
            const modalContent = modalContainer.querySelector(".bg-white");
            if (modalContent && !modalContent.contains(event.target)) {
                closeModal();
            }
        }
    });

    // Add keyboard shortcuts
    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case "Enter":
                    e.preventDefault();
                    addRow();
                    break;
                case "s":
                    e.preventDefault();
                    saveData();
                    break;
                case "Delete":
                    e.preventDefault();
                    if (e.shiftKey) {
                        // Clear table functionality is now handled by HTMX
                        console.log(
                            "Clear table shortcut pressed - using HTMX modal"
                        );
                    }
                    break;
            }
        }

        // Additional shortcuts
        if (e.key === "Escape") {
            // Close any open menus or focus states
            const exportMenu = document.getElementById("exportMenu");
            if (exportMenu) {
                exportMenu.classList.remove("opacity-100", "visible");
                exportMenu.classList.add("opacity-0", "invisible");
            }

            // Close clear confirmation modal
            const clearModal = document.getElementById("clearConfirmModal");
            if (clearModal && !clearModal.classList.contains("hidden")) {
                closeModal();
            }
        }
    });

    // Add hover effects for table cells (no scale effects)
    const inputs = document.querySelectorAll('input[type="text"]');
    inputs.forEach((input) => {
        // Hover effects without scaling
        input.addEventListener("mouseenter", function () {
            // Add any other hover effects here if needed
        });

        input.addEventListener("mouseleave", function () {
            // Remove any hover effects here if needed
        });
    });
}

// Export functions to global scope for inline event handlers
window.addRow = addRow;
window.deleteRow = deleteRow;
window.updateCell = updateCell;
window.saveData = saveData;
window.highlightRow = highlightRow;
window.unhighlightRow = unhighlightRow;
window.deleteRowWithAnimation = deleteRowWithAnimation;
window.toggleExportMenu = toggleExportMenu;
window.exportTable = exportTable;
window.closeModal = closeModal;
window.updateCellWithValidation = updateCellWithValidation;
window.searchTable = searchTable;
window.initializeTable = initializeTable;

// Multi-select functionality
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById("selectAll");
    const rowCheckboxes = document.querySelectorAll(".row-checkbox");
    const multiDeleteBtn = document.getElementById("multiDeleteBtn");
    const selectedCountSpan = document.getElementById("selectedCount");

    rowCheckboxes.forEach((checkbox) => {
        checkbox.checked = selectAllCheckbox.checked;
    });

    updateMultiDeleteButton();
}

function updateRowSelection() {
    updateMultiDeleteButton();

    // Update select all checkbox state
    const rowCheckboxes = document.querySelectorAll(".row-checkbox");
    const selectAllCheckbox = document.getElementById("selectAll");
    const allChecked = Array.from(rowCheckboxes).every(
        (checkbox) => checkbox.checked
    );
    const someChecked = Array.from(rowCheckboxes).some(
        (checkbox) => checkbox.checked
    );

    selectAllCheckbox.checked = allChecked;
    selectAllCheckbox.indeterminate = someChecked && !allChecked;
}

function updateMultiDeleteButton() {
    const rowCheckboxes = document.querySelectorAll(".row-checkbox:checked");
    const multiDeleteBtn = document.getElementById("multiDeleteBtn");
    const selectedCountSpan = document.getElementById("selectedCount");

    if (rowCheckboxes.length > 0) {
        multiDeleteBtn.classList.remove("hidden");
        selectedCountSpan.textContent = rowCheckboxes.length;
    } else {
        multiDeleteBtn.classList.add("hidden");
        selectedCountSpan.textContent = "0";
    }
}

function deleteSelectedRows() {
    const selectedCheckboxes = document.querySelectorAll(
        ".row-checkbox:checked"
    );
    if (selectedCheckboxes.length === 0) return;

    if (
        confirm(
            `Are you sure you want to delete ${selectedCheckboxes.length} selected row(s)?`
        )
    ) {
        const selectedRows = Array.from(selectedCheckboxes).map((checkbox) =>
            parseInt(checkbox.value)
        );

        // Call the existing deleteRow function for each selected row
        selectedRows.forEach((rowIndex) => {
            deleteRow(rowIndex);
        });

        // Reset select all checkbox
        document.getElementById("selectAll").checked = false;
        document.getElementById("selectAll").indeterminate = false;

        // Hide multi-delete button
        updateMultiDeleteButton();
    }
}

// Export multi-select functions to global scope
window.toggleSelectAll = toggleSelectAll;
window.updateRowSelection = updateRowSelection;
window.updateMultiDeleteButton = updateMultiDeleteButton;
window.deleteSelectedRows = deleteSelectedRows;
