<div class="container">
    <h2>Upload Nomor Resi (AWB)</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('awb.upload.submit') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="file" class="form-label">Pilih File Excel (AWB)</label>
            <input type="file" name="file" id="file" class="form-control" required>
            @error('file')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
