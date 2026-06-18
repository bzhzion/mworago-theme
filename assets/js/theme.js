  // ── DARK MODE ──────────────────────────────────────────────────────────────
  const html      = document.documentElement;
  const toggle    = document.getElementById('themeToggle');
  const icoSun    = document.getElementById('ico-sun');
  const icoMoon   = document.getElementById('ico-moon');

  function applyTheme(t) {
    html.setAttribute('data-theme', t);
    icoSun.style.display  = t === 'dark' ? 'block' : 'none';
    icoMoon.style.display = t === 'dark' ? 'none'  : 'block';
  }

  function getTheme() {
    return localStorage.getItem('mworago-theme')
      || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  }

  applyTheme(getTheme());

  toggle.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    localStorage.setItem('mworago-theme', next);
    applyTheme(next);
  });

  matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem('mworago-theme')) applyTheme(e.matches ? 'dark' : 'light');
  });

  // ── MOBILE NAV ─────────────────────────────────────────────────────────────
  const burger    = document.getElementById('burgerBtn');
  const mobileNav = document.getElementById('mobileNav');
  const overlay   = document.getElementById('mobileOverlay');


  if (burger && mobileNav) {
    function openMenu() {
      burger.classList.add('is-open');
      mobileNav.classList.add('is-open');
      overlay.classList.add('is-open');
      burger.setAttribute('aria-expanded', 'true');
      mobileNav.setAttribute('aria-hidden', 'false');
      mobileNav.removeAttribute('inert');
      document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
      burger.classList.remove('is-open');
      mobileNav.classList.remove('is-open');
      overlay.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
      mobileNav.setAttribute('aria-hidden', 'true');
      mobileNav.setAttribute('inert', '');
      document.body.style.overflow = '';
    }
    burger.addEventListener('click', () => {
      burger.classList.contains('is-open') ? closeMenu() : openMenu();
    });
    overlay.addEventListener('click', closeMenu);
  }

  // ── SCROLL REVEAL ──────────────────────────────────────────────────────────
  const obs = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
      if (!e.isIntersecting) return;
      const delay = parseFloat(e.target.style.getPropertyValue('--delay') || 0);
      setTimeout(() => e.target.classList.add('is-visible'), delay * 1000);
      obs.unobserve(e.target);
    });
  }, { threshold: 0.08 });

  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

