@extends('layout.index')

@section('title', 'Document Management')

@section('content')

{!! renderBreadcrumb() !!}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<style>
  /* Existing styles remain */
  .dropzone-overlay {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 5;
    width: 100%;
    height: 100%;
    background: rgba(13, 110, 253, 0.1);
    border: 2px dashed #0d6efd;
    border-radius: 0.375rem;
    align-items: center;
    justify-content: center;
    color: #0d6efd;
    font-weight: bold;
  }

  .document-item .card {
    border: 1px solid;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    cursor: pointer;
    min-height: 100%;
  }

  .document-item .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
  }

  .icon-wrapper {
    padding: 20px;
    border-radius: 50%;
    display: inline-block;
  }

  .folder-mapping {
    font-size: 10pt;
  }

  .toggle-arrow::before {
    content: "▶";
    /* default: collapsed */
    display: inline-block;
    transition: transform 0.2s ease;
    margin-right: 5px;
  }

  .toggle-arrow[aria-expanded="true"]::before {
    content: "▼";
    /* expanded */
  }

  @media (max-width: 576px) {
    .document-item .card {
      min-height: auto;
    }
  }

  .pdf-bg {
    background-color: #dc3545;
  }

  .docx-bg {
    background-color: #0d6efd;
  }

  .xlsx-bg {
    background-color: #198754;
  }

  .pptx-bg {
    background-color: #ffc107;
    color: #212529 !important;
  }

  .folder-bg {
    background-color: #fd7e14;
  }

  .default-bg {
    background-color: #6c757d;
  }

  /* Custom style for the dedicated upload area */
  #uploadArea {
    border: 2px dashed #0d6efd;
    border-radius: 0.375rem;
    padding: 30px;
    text-align: center;
    color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05);
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
    /* Ensure Dropzone's default styling doesn't override this negatively */
    min-height: 150px;
    /* Give it some minimum height */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
    /* Needed for Dropzone's internal elements */
  }

  /* Dropzone.js specific styling adjustments */
  .dropzone {
    border: none;
    /* We manage border with #uploadArea */
    background: none;
    /* We manage background with #uploadArea */
    min-height: auto;
    /* Override Dropzone's default min-height */
    padding: 0;
    /* Override Dropzone's default padding */
    color: inherit;
    /* Inherit color from parent */
  }

  .dropzone .dz-message {
    margin: 0;
    /* Remove default margin */
    font-size: 1rem;
    /* Adjust font size */
    color: inherit;
    /* Inherit color */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;
    /* Make message take full height of dropzone */
  }

  .dropzone.dz-drag-hover #uploadArea {
    background-color: rgba(13, 110, 253, 0.15);
    border-color: #0b5ed7;
  }

  /* Hide default Dropzone elements we don't need */
  .dropzone .dz-preview {
    display: none !important;
    /* Hide file previews */
  }

</style>

<section class="dashboard">
  <div class="container-fluid">

    <div class="row">
      <div class="col-xl-3">
        <h4>Document List</h4>

        <div class="border rounded p-3 folder-mapping">
          <!-- Level 1: ABC -->
          <p class="mb-1">
            <a class="d-block toggle-arrow fw-bold text-decoration-none" data-bs-toggle="collapse" href="#collapseABC"
              role="button" aria-expanded="false" aria-controls="collapseABC">
              📁 ABC
            </a>
          </p>
          <div class="collapse" id="collapseABC">
            <div class="ps-4">

              <!-- Level 2: Legal -->
              <p class="mb-1">
                <a class="d-block toggle-arrow fw-semibold text-decoration-none text-secondary"
                  data-bs-toggle="collapse" href="#collapseLegal" role="button" aria-expanded="false"
                  aria-controls="collapseLegal">
                  📁 Legal
                </a>
              </p>
              <div class="collapse" id="collapseLegal">
                <div class="ps-4">

                  <!-- Level 3: Sub Legal Folder -->
                  <p class="mb-1">
                    <a class="d-block toggle-arrow text-decoration-none text-secondary" data-bs-toggle="collapse"
                      href="#collapseSubLegal" role="button" aria-expanded="false" aria-controls="collapseSubLegal">
                      📁 Sub Legal Folder
                    </a>
                  </p>
                  <div class="collapse" id="collapseSubLegal">
                    <div class="ps-4">

                      <!-- Level 4: Arsip -->
                      <p class="mb-1">
                        <a class="d-block toggle-arrow text-decoration-none text-secondary" data-bs-toggle="collapse"
                          href="#collapseArsipLegal" role="button" aria-expanded="false"
                          aria-controls="collapseArsipLegal">
                          📁 Arsip Legal
                        </a>
                      </p>
                      <div class="collapse" id="collapseArsipLegal">
                        <div class="ps-4">

                          <!-- Level 5: Files -->
                          <p class="mb-1 text-muted">📄 Legal_File_A.pdf</p>
                          <p class="mb-1 text-muted">📄 Legal_File_B.docx</p>

                        </div>
                      </div>

                    </div>
                  </div>

                </div>
              </div>

              <!-- Level 2: FAT -->
              <p class="mb-1">
                <a class="d-block toggle-arrow fw-semibold text-decoration-none text-secondary"
                  data-bs-toggle="collapse" href="#collapseFAT" role="button" aria-expanded="false"
                  aria-controls="collapseFAT">
                  📁 FAT
                </a>
              </p>
              <div class="collapse" id="collapseFAT">
                <div class="ps-4">

                  <!-- Level 3: Sub FAT Folder -->
                  <p class="mb-1">
                    <a class="d-block toggle-arrow text-decoration-none text-secondary" data-bs-toggle="collapse"
                      href="#collapseSubFAT" role="button" aria-expanded="false" aria-controls="collapseSubFAT">
                      📁 Sub FAT Folder
                    </a>
                  </p>
                  <div class="collapse" id="collapseSubFAT">
                    <div class="ps-4">

                      <!-- Level 4: Arsip FAT -->
                      <p class="mb-1">
                        <a class="d-block toggle-arrow text-decoration-none text-secondary" data-bs-toggle="collapse"
                          href="#collapseArsipFAT" role="button" aria-expanded="false" aria-controls="collapseArsipFAT">
                          📁 Arsip FAT
                        </a>
                      </p>
                      <div class="collapse" id="collapseArsipFAT">
                        <div class="ps-4">

                          <!-- Level 5: Files -->
                          <p class="mb-1 text-muted">📄 FAT_File_A.xlsx</p>
                          <p class="mb-1 text-muted">📄 FAT_File_B.pdf</p>

                        </div>
                      </div>

                    </div>
                  </div>

                </div>
              </div>

              <!-- Level 2: Dokumen Lain -->
              <p class="mb-1 text-muted">📄 Dokumen_Lain_1.pdf</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-9">
        <div class="card shadow-sm mb-4">
          <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3 py-3">
              <div class="input-group d-md-flex mb-3" style="max-width: 400px;">
                <input type="text" class="form-control" placeholder="Cari dokumen, folder, atau pengguna...">
                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
              </div>
              <div class="d-flex align-items-center mb-0">
                <select class="form-select form-select-sm me-2" id="sortSelect">
                  <option value="date-desc">Terbaru</option>
                  <option value="date-asc">Terlama</option>
                  <option value="name-asc">Nama (A-Z)</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm me-2 active" data-view-mode="grid"><i
                    class="bi bi-grid"></i></button>
                <button class="btn btn-outline-secondary btn-sm me-2" data-view-mode="list"><i
                    class="bi bi-list"></i></button>
                <button class="btn btn-primary btn-sm" id="newFolderBtn" data-bs-toggle="modal"
                  data-bs-target="#newFolderModal"><i class="bi bi-folder-plus"></i></button>
              </div>
            </div>

            {{-- <nav aria-label="breadcrumb">
              <ol class="breadcrumb" id="breadcrumbsContainer">
                <li class="breadcrumb-item active"><a href="#" data-path="/" class="fw-bold">Root</a></li>
              </ol>
            </nav> --}}

            <div class="py-1 row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3" id="fileExplorerContainer">

              <div class="col">
                <div id="uploadArea" class="dropzone h-100">
                  <div class="dz-message d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-cloud-arrow-up display-4 mb-2"></i>
                    <p class="mb-0">Drag & Drop file di sini atau klik untuk upload</p>
                  </div>
                </div>
              </div>

              <div class="col document-item folder" data-name="Dokumen Proyek">
                <div class="card h-100 shadow-sm border-warning">
                  <div class="card-body text-center d-flex flex-column justify-content-between align-items-center">
                    <div class="icon-wrapper bg-warning text-dark"><i class="bi bi-folder"></i></div>
                    <h6 class="card-title text-truncate w-100">Dokumen Proyek</h6>
                    <small class="text-muted">3 item</small>
                    <div class="d-flex mt-2">
                      <button class="btn btn-sm btn-outline-primary me-1 open-folder-btn"><i
                          class="bi bi-arrow-right"></i></button>
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col document-item folder" data-name="Laporan Penjualan">
                <div class="card h-100 shadow-sm border-warning">
                  <div class="card-body text-center d-flex flex-column justify-content-between align-items-center">
                    <div class="icon-wrapper bg-warning text-dark"><i class="bi bi-folder"></i></div>
                    <h6 class="card-title text-truncate w-100">Laporan Penjualan</h6>
                    <small class="text-muted">1 item</small>
                    <div class="d-flex mt-2">
                      <button class="btn btn-sm btn-outline-primary me-1 open-folder-btn"><i
                          class="bi bi-arrow-right"></i></button>
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<div class="modal fade" id="newFolderModal" tabindex="-1" aria-labelledby="newFolderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buat Folder Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="newFolderForm">
          <div class="mb-3">
            <label for="folderName" class="form-label">Nama Folder</label>
            <input type="text" class="form-control" id="folderName" required placeholder="Contoh: Laporan Keuangan">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Buat Folder</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  // Disable auto-discovery for Dropzone to prevent it from auto-initializing on all elements with class "dropzone"
  Dropzone.autoDiscover = false;

  // --- Global Variables / Initial Setup ---
  let currentActiveFolderPath = '/'; // Initialize to root

  // --- DOM Element References ---
  const uploadArea = document.getElementById('uploadArea');
  const breadcrumbsContainer = document.getElementById('breadcrumbsContainer');
  const openFolderButtons = document.querySelectorAll('.open-folder-btn');
  const newFolderForm = document.getElementById('newFolderForm');

  // --- Dropzone.js Initialization ---
  if (uploadArea) {
    // Initialize Dropzone on your custom uploadArea
    const myDropzone = new Dropzone("#uploadArea", {
      url: "YOUR_UPLOAD_ENDPOINT", // *** IMPORTANT: Replace with your actual Laravel upload route ***
      paramName: "file", // The name of the form field that will contain the file
      maxFilesize: 25, // MB
      acceptedFiles: "image/*,application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx", // Allowed file types
      addRemoveLinks: false, // Don't show remove links (we're not displaying previews)
      dictDefaultMessage: "", // Clear default message, we use our own HTML
      dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
      // dictFileTooBig: "File is too big (kasih echo laravel) (filesize MiB). Max filesize: maxFilesizeMiB.",
      dictInvalidFileType: "You can't upload files of this type.",
      dictResponseError: "Server responded with kasih echo ini statusCode code.",
      dictCancelUpload: "Cancel upload",
      dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
      dictRemoveFile: "Remove file",
      dictMaxFilesExceeded: "You can not upload any more files.",
      previewTemplate: "<div style='display:none'></div>", // Hide previews
      autoProcessQueue: true, // Automatically process files after they are added
      uploadMultiple: true, // Allow multiple files to be uploaded in one request if dropped together
      parallelUploads: 5, // How many files to upload simultaneously
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
      },
      // You can add more options as needed: https://www.dropzonejs.com/#configuration-options
    });

    // Dropzone Event Listeners
    myDropzone.on("sending", function (file, xhr, formData) {
      // This event is triggered just before the file is sent.
      // Append current folder path to the form data.
      formData.append('folder_path', currentActiveFolderPath);
      console.log(`Sending file: ${file.name} to ${currentActiveFolderPath}`);
    });

    myDropzone.on("success", function (file, response) {
      console.log(`Upload successful for: ${file.name}`, response);
      alert(`File "${file.name}" uploaded successfully!`);
      // TODO: Update UI here. You might want to:
      // - Reload the current folder's contents
      // - Dynamically add a new card for the uploaded file
      // - Or simply remove the file from Dropzone's internal list (myDropzone.removeFile(file);)
      myDropzone.removeFile(file); // Remove file from Dropzone's internal queue after success
    });

    myDropzone.on("error", function (file, errorMessage, xhr) {
      console.error(`Upload error for: ${file.name}`, errorMessage, xhr);
      alert(`Upload failed for "${file.name}": ${errorMessage}`);
      myDropzone.removeFile(file); // Remove file from Dropzone's internal queue after error
    });

    myDropzone.on("addedfile", function (file) {
      // Optional: You can show a custom progress indicator or message here
      console.log(`File added to queue: ${file.name}`);
    });

    // Handle folder drag and drop (Dropzone's default behavior for folders can be limited)
    // If you drag a folder, Dropzone typically treats it as multiple files.
    // For more advanced folder structure handling, you might need a custom solution
    // or check Dropzone plugins. The previous webkitGetAsEntry logic is more for that.
    // Dropzone generally handles dropping multiple files or files inside a folder
    // as individual file uploads.
    myDropzone.on("drop", function (e) {
      console.log("Dropzone 'drop' event fired.");
      // Dropzone handles preventing default behavior internally.
      // e.dataTransfer.items will contain the file entries
      // Dropzone automatically processes these as individual files.
    });

  } else {
    console.error("Element with ID 'uploadArea' not found. Dropzone.js cannot be initialized.");
  }

  // --- Other Event Listeners (Remaining as before) ---

  // Handle Breadcrumb navigation to update currentActiveFolderPath
  if (breadcrumbsContainer) {
    breadcrumbsContainer.addEventListener('click', function (e) {
      if (e.target.tagName === 'A' && e.target.dataset.path) {
        e.preventDefault();
        currentActiveFolderPath = e.target.dataset.path;
        console.log('Current active folder (from breadcrumb):', currentActiveFolderPath);
        // TODO: In a real app, you'd load the contents of this folder here.
        // loadFolderContents(currentActiveFolderPath);
      }
    });
  }

  // Handle "Open Folder" button clicks to update currentActiveFolderPath
  openFolderButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      const folderCard = e.target.closest('.document-item.folder');
      if (folderCard) {
        const folderName = folderCard.dataset.name;
        currentActiveFolderPath =
          `${currentActiveFolderPath === '/' ? '' : currentActiveFolderPath}/${folderName}`;
        console.log('Navigated to folder (from open button):', currentActiveFolderPath);
        // TODO: In a real app, you'd load the contents of this folder here.
        // loadFolderContents(currentActiveFolderPath);
      }
    });
  });

  // Handle new folder creation (simple client-side example)
  if (newFolderForm) {
    newFolderForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const folderNameInput = document.getElementById('folderName');
      const newFolderName = folderNameInput.value.trim();

      if (newFolderName) {
        console.log(`Creating new folder: ${newFolderName} in ${currentActiveFolderPath}`);
        // TODO: Send an AJAX request to your Laravel backend to create the folder
        alert(`Simulasi: Folder "${newFolderName}" dibuat di ${currentActiveFolderPath}`);
        $('#newFolderModal').modal('hide'); // Close modal
        folderNameInput.value = ''; // Clear input
      } else {
        alert('Nama folder tidak boleh kosong.');
      }
    });
  }

</script>
@endsection
