(function () {
  'use strict';
  async function captureAndExport(button) {
    const form = document.getElementById(button.dataset.exportForm);
    if (!form) {
      alert('Library html2canvas belum ditemukan di public/vendor/.');
      return;
    }
    // Export bulanan/rentang tanggal hanya perlu tabel, tanpa capture Dashboard.
    const periodType = form.querySelector('[name="period_type"]')?.value || 'monthly';
    if (periodType !== 'yearly') {
      const chartInput = form.querySelector('[name="chart_image"]');
      if (chartInput) chartInput.value = '';
      form.submit();
      return;
    }
    const label = button.innerHTML;
    button.disabled = true;
    button.textContent = 'Menyiapkan grafik...';
    const frame = document.createElement('iframe');
    frame.setAttribute('aria-hidden', 'true');
    // Ukuran khusus export, bukan lebar viewport dashboard yang dapat sangat besar.
    const captureWidth = 1180;
    Object.assign(frame.style, {
      position: 'fixed', left: '-14000px', top: '0',
      width: captureWidth + 'px', height: '1200px', border: '0', opacity: '0',
      pointerEvents: 'none'
    });
    const params = new URLSearchParams(new FormData(form));
    params.delete('_token');
    params.delete('chart_image');
    frame.src = form.dataset.previewUrl + '?' + params.toString();
    try {
      const loaded = new Promise((resolve, reject) => {
        frame.addEventListener('load', resolve, { once: true });
        frame.addEventListener('error', reject, { once: true });
      });
      document.body.appendChild(frame);
      await loaded;
      const doc = frame.contentDocument;
      if (!doc) throw new Error('Halaman grafik tidak dapat diakses.');
      if (doc.fonts && doc.fonts.ready) await doc.fonts.ready;
      const chart = doc.querySelector('.chart-card');
      if (!chart) throw new Error('Card grafik Dashboard tidak ditemukan.');
      // Jangan memperlebar DOM asli; pengaturan ukuran hanya pada clone export.
      // Execute html2canvas in the iframe's own document/window context.
      // This avoids passing an element from a different browsing context.
      const script = doc.createElement('script');
      script.src = document.querySelector('script[src*="html2canvas.min.js"]').src;
      await new Promise((resolve, reject) => {
        script.onload = resolve;
        script.onerror = reject;
        doc.head.appendChild(script);
      });
      if (typeof frame.contentWindow.html2canvas !== 'function') {
        throw new Error('html2canvas tidak termuat di halaman grafik.');
      }
      const wrapper = doc.createElement('div');
      wrapper.style.position = 'fixed';
      wrapper.style.left = '-20000px';
      wrapper.style.top = '0';
      wrapper.style.padding = '24px';
      wrapper.style.background = '#ffffff';
      wrapper.style.display = 'inline-block';
      const clone = chart.cloneNode(true);
      clone.style.boxSizing = 'border-box';
      clone.style.width = captureWidth + 'px';
      clone.style.maxWidth = 'none';
      clone.style.overflow = 'visible';
      const cloneScroller = clone.querySelector('.chart-scroll');
      if (cloneScroller) {
        cloneScroller.style.width = '100%';
        cloneScroller.style.overflow = 'visible';
      }
      // Bar chart mengikuti lebar card yang proporsional, tanpa overflow horizontal.
      const cloneBarChart = clone.querySelector('.bar-chart');
      if (cloneBarChart) cloneBarChart.style.width = '100%';
      wrapper.appendChild(clone);
      doc.body.appendChild(wrapper);
      const canvas = await frame.contentWindow.html2canvas(wrapper, {
        backgroundColor: '#ffffff', scale: 2,
        useCORS: false, logging: false,
        width: Math.ceil(wrapper.scrollWidth), height: Math.ceil(wrapper.scrollHeight),
        windowWidth: Math.max(captureWidth, Math.ceil(wrapper.scrollWidth) + 48),
      });
      wrapper.remove();
      const dataUrl = canvas.toDataURL('image/png');
      if (!dataUrl || dataUrl.length < 1000) throw new Error('Gambar grafik kosong.');
      form.querySelector('[name="chart_image"]').value = dataUrl;
      form.submit();
    } catch (error) {
      console.error('OMIRA Excel capture', error);
      alert('Export grafik gagal: ' + (error.message || 'Kesalahan tidak diketahui'));
    } finally {
      frame.remove();
      button.disabled = false;
      button.innerHTML = label;
    }
  }
  document.addEventListener('click', function (event) {
    const btn = event.target.closest('[data-export-form]');
    if (btn) { event.preventDefault(); captureAndExport(btn); }
  });
})();
