    <!-- Modal trigger button -->
    <button class="btn btn-primary btn-sm" onclick="upload_modal.showModal()">
        <i data-lucide="upload"></i>
        Upload AWB
    </button>

    <!-- DaisyUI Modal -->
    <dialog id="upload_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Upload Nomor Resi (AWB)</h3>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('awb.upload.submit') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Pilih File Excel (AWB)</span>
                    </label>
                    <input type="file" name="file" id="file" class="file-input file-input-bordered w-full"
                        required>
                    @error('file')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn" onclick="upload_modal.close()">Close</button>
                </div>
            </form>
        </div>
    </dialog>
