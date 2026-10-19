(function () {
  'use strict';
  async function captureAndExport(button) {
    const form = document.getElementById(button.dataset.exportForm);
    if (!form) {
      alert('Library html2canvas belum ditemukan di public/vendor/.');
      return;
    }
    const label = button.innerHTML;
    button.disabled = true;
    button.textContent = 'Menyiapkan grafik...';
    // Dashboard memakai 12 kolom bulan; cukup lebar untuk semua label tanpa membuat jarak renggang.
    const captureWidth = 1160;
    const capturePadding = 16;
    const frame = document.createElement('iframe');
    frame.setAttribute('aria-hidden', 'true');
    Object.assign(frame.style, {
      position: 'fixed', left: '-14000px', top: '0',
      width: '1240px', height: '900px', border: '0', opacity: '0',
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
      wrapper.style.boxSizing = 'border-box';
      wrapper.style.width = (captureWidth + capturePadding * 2) + 'px';
      wrapper.style.padding = capturePadding + 'px';
      wrapper.style.background = '#ffffff';

      const clone = chart.cloneNode(true);
      clone.style.boxSizing = 'border-box';
      clone.style.width = captureWidth + 'px';
      clone.style.maxWidth = 'none';
      clone.style.overflow = 'visible';

      const chartHead = clone.querySelector('.card-head');
      const legend = clone.querySelector('.chart-legend');
      const scroller = clone.querySelector('.chart-scroll');
      if (chartHead) {
        chartHead.style.flexWrap = 'nowrap';
        chartHead.style.alignItems = 'flex-start';
      }
      if (legend) {
        legend.style.flexWrap = 'nowrap';
        legend.style.whiteSpace = 'nowrap';
        legend.style.justifyContent = 'flex-end';
        legend.style.maxWidth = 'none';
      }
      if (scroller) {
        scroller.style.width = '100%';
        scroller.style.overflow = 'visible';
      }
      wrapper.appendChild(clone);
      doc.body.appendChild(wrapper);
      const canvas = await frame.contentWindow.html2canvas(wrapper, {
        backgroundColor: '#ffffff', scale: 2,
        useCORS: false, logging: false,
        width: Math.ceil(wrapper.getBoundingClientRect().width),
        height: Math.ceil(wrapper.getBoundingClientRect().height),
        windowWidth: 1240,
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
