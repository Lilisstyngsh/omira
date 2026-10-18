<div class="history-card history-pdf-preview">
    <div class="history-pdf-preview-heading">
        <h3>Preview PDF History Order</h3>
        <span>Pratinjau sebelum download</span>
    </div>
    <iframe
        src="{{ $previewUrl }}"
        title="Preview PDF Order {{ $order->order_number }}"
        loading="lazy"
        referrerpolicy="same-origin"></iframe>
    <div class="history-pdf-preview-footer">
        <a href="{{ $downloadUrl }}" class="history-pdf-download"><i class="fa-regular fa-file-pdf" aria-hidden="true"></i> Download PDF</a>
    </div>
</div>
