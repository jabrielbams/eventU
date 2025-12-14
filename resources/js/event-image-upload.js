document.addEventListener("DOMContentLoaded", function () {
    const dropZone = document.getElementById("drop-zone");
    const imageInput = document.getElementById("image-input");
    const imagePreview = document.getElementById("image-preview");
    const dropZoneText = document.getElementById("drop-zone-text");

    if (!dropZone || !imageInput || !imagePreview || !dropZoneText) {
        return; // Elements not found, exit gracefully
    }

    // Click to upload
    dropZone.addEventListener("click", () => imageInput.click());

    // Handle file selection via input
    imageInput.addEventListener("change", function () {
        if (this.files && this.files[0]) {
            showPreview(this.files[0]);
        }
    });

    // Drag and Drop events
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ["dragenter", "dragover"].forEach((eventName) => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ["dragleave", "drop"].forEach((eventName) => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add("bg-gray-200");
        dropZone.classList.remove("bg-gray-50");
    }

    function unhighlight(e) {
        dropZone.classList.remove("bg-gray-200");
        dropZone.classList.add("bg-gray-50");
    }

    dropZone.addEventListener("drop", handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files && files[0]) {
            imageInput.files = files;
            showPreview(files[0]);
        }
    }

    function showPreview(file) {
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                imagePreview.src = reader.result;
                imagePreview.classList.remove("hidden");
                dropZoneText.classList.add("hidden");
            };
        }
    }
});
