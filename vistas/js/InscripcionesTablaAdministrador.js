document.addEventListener('DOMContentLoaded', function() {
  const adminTabsLinks = document.querySelectorAll('#adminTabs .nav-link');
  const btnAdminNext = document.querySelectorAll('.btn-admin-sig');
  const btnAdminPrev = document.querySelectorAll('.btn-admin-ant');

  if (adminTabsLinks.length > 0) {
    adminTabsLinks.forEach(link => {
      link.addEventListener('click', function() {
        adminTabsLinks.forEach(item => item.classList.remove('active', 'bg-success'));
        adminTabsLinks.forEach(item => item.classList.add('bg-success'));
        this.classList.add('active', 'text-white', 'bg-success');

        const index = Array.from(this.parentElement.parentElement.children).indexOf(this.parentElement);
        const indicador = document.querySelector('.admin-indicador');
        if (indicador) {
          indicador.textContent = index + 1;
        }
      });
    });
  }

  btnAdminNext.forEach(button => {
    button.addEventListener('click', function() {
      const active = document.querySelector('#adminTabs .nav-link.active');
      if (!active) return;
      const next = active.parentElement.nextElementSibling;
      if (next) {
        const nextLink = next.querySelector('.nav-link');
        if (nextLink) {
          nextLink.click();
        }
      }
    });
  });

  btnAdminPrev.forEach(button => {
    button.addEventListener('click', function() {
      const active = document.querySelector('#adminTabs .nav-link.active');
      if (!active) return;
      const prev = active.parentElement.previousElementSibling;
      if (prev) {
        const prevLink = prev.querySelector('.nav-link');
        if (prevLink) {
          prevLink.click();
        }
      }
    });
  });
});