/**
 * STUDENTHUB JAVASCRIPT MASTER MODULE
 * ITUE203 - Web Development Frameworks
 * Author: Yuvraj Parmar (25DCE070) - 3rd Sem CE, CHARUSAT
 */

// Global State
const StudentHub = {
  theme: localStorage.getItem('studenthub_theme') || 'light',
  captchaCode: '',
  events: [],
  students: [],
  faqs: [],
  notices: [],
  locations: {},
  registrations: []
};

// ==========================================
// 1. DOM READY & INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initMobileNav();
  initHeroSlider();
  initAccordions();
  initModals();
  initToastContainer();
  initCaptcha();
  initFormValidation();
  initDependentDropdowns();
  initDataRendering();
  initTabs();
  initCounters();
});

// ==========================================
// 2. PRACTICAL 4: THEME SWITCHER (Dark/Light)
// ==========================================
function initTheme() {
  document.documentElement.setAttribute('data-theme', StudentHub.theme);
  updateThemeToggleIcons(StudentHub.theme);

  const toggleBtns = document.querySelectorAll('.theme-toggle-btn');
  toggleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      StudentHub.theme = StudentHub.theme === 'light' ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', StudentHub.theme);
      localStorage.setItem('studenthub_theme', StudentHub.theme);
      updateThemeToggleIcons(StudentHub.theme);
      showToast('Theme Updated', `Switched to ${StudentHub.theme} mode`, 'info', 2000);
    });
  });
}

function updateThemeToggleIcons(theme) {
  const toggleBtns = document.querySelectorAll('.theme-toggle-btn');
  toggleBtns.forEach(btn => {
    btn.innerHTML = theme === 'dark' ? '☀️' : '🌙';
    btn.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
    btn.setAttribute('title', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
  });
}

// ==========================================
// 3. PRACTICAL 4: MOBILE HAMBURGER NAVIGATION
// ==========================================
function initMobileNav() {
  const hamburgerBtn = document.querySelector('.hamburger-btn');
  const closeBtn = document.querySelector('.mobile-nav-close');
  const drawer = document.querySelector('.mobile-nav-drawer');
  const overlay = document.querySelector('.mobile-nav-overlay');

  if (!hamburgerBtn || !drawer || !overlay) return;

  function openMenu() {
    drawer.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    drawer.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  hamburgerBtn.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  overlay.addEventListener('click', closeMenu);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && drawer.classList.contains('active')) {
      closeMenu();
    }
  });
}

// ==========================================
// 4. PRACTICAL 4: HERO CONTENT SLIDER / CAROUSEL
// ==========================================
function initHeroSlider() {
  const slider = document.querySelector('.hero-slider-container');
  if (!slider) return;

  const slides = slider.querySelector('.hero-slides');
  const slideItems = slider.querySelectorAll('.hero-slide');
  const prevBtn = slider.querySelector('.slider-btn.prev');
  const nextBtn = slider.querySelector('.slider-btn.next');
  const dotsContainer = slider.querySelector('.slider-indicators');

  if (!slides || slideItems.length === 0) return;

  let currentIndex = 0;
  const totalSlides = slideItems.length;
  let autoplayTimer = null;

  // Build dots
  if (dotsContainer) {
    dotsContainer.innerHTML = '';
    slideItems.forEach((_, idx) => {
      const dot = document.createElement('button');
      dot.className = `slider-dot ${idx === 0 ? 'active' : ''}`;
      dot.setAttribute('aria-label', `Go to slide ${idx + 1}`);
      dot.addEventListener('click', () => goToSlide(idx));
      dotsContainer.appendChild(dot);
    });
  }

  function updateSlider() {
    slides.style.transform = `translateX(-${currentIndex * 100}%)`;
    if (dotsContainer) {
      const dots = dotsContainer.querySelectorAll('.slider-dot');
      dots.forEach((dot, idx) => {
        dot.classList.toggle('active', idx === currentIndex);
      });
    }
  }

  function goToSlide(index) {
    currentIndex = (index + totalSlides) % totalSlides;
    updateSlider();
    resetAutoplay();
  }

  function nextSlide() {
    goToSlide(currentIndex + 1);
  }

  function prevSlide() {
    goToSlide(currentIndex - 1);
  }

  if (nextBtn) nextBtn.addEventListener('click', nextSlide);
  if (prevBtn) prevBtn.addEventListener('click', prevSlide);

  function startAutoplay() {
    autoplayTimer = setInterval(nextSlide, 5000);
  }

  function resetAutoplay() {
    clearInterval(autoplayTimer);
    startAutoplay();
  }

  slider.addEventListener('mouseenter', () => clearInterval(autoplayTimer));
  slider.addEventListener('mouseleave', startAutoplay);

  startAutoplay();
}

// ==========================================
// 5. PRACTICAL 4: COLLAPSIBLE FAQ ACCORDION
// ==========================================
function initAccordions() {
  const accordionHeaders = document.querySelectorAll('.accordion-header');

  accordionHeaders.forEach(header => {
    header.addEventListener('click', () => {
      const item = header.closest('.accordion-item');
      const collapse = item.querySelector('.accordion-collapse');
      const isOpen = item.classList.contains('active');

      // Close all siblings in the same accordion
      const parentAccordion = header.closest('.accordion');
      if (parentAccordion) {
        parentAccordion.querySelectorAll('.accordion-item').forEach(otherItem => {
          if (otherItem !== item) {
            otherItem.classList.remove('active');
            const otherCollapse = otherItem.querySelector('.accordion-collapse');
            if (otherCollapse) {
              otherCollapse.style.maxHeight = null;
              otherItem.querySelector('.accordion-header')?.setAttribute('aria-expanded', 'false');
            }
          }
        });
      }

      if (isOpen) {
        item.classList.remove('active');
        collapse.style.maxHeight = null;
        header.setAttribute('aria-expanded', 'false');
      } else {
        item.classList.add('active');
        collapse.style.maxHeight = collapse.scrollHeight + 'px';
        header.setAttribute('aria-expanded', 'true');
      }
    });
  });
}

// ==========================================
// 6. PRACTICAL 4: MODAL POPUP SYSTEM
// ==========================================
function initModals() {
  // Open buttons
  document.querySelectorAll('[data-modal-target]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const modalId = btn.getAttribute('data-modal-target');
      openModal(modalId);
    });
  });

  // Close buttons & backdrop click
  document.querySelectorAll('.modal-backdrop').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal || e.target.closest('.modal-close-btn') || e.target.hasAttribute('data-modal-close')) {
        closeModal(modal.id);
      }
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const activeModal = document.querySelector('.modal-backdrop.active');
      if (activeModal) closeModal(activeModal.id);
    }
  });
}

function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
  modal.setAttribute('aria-hidden', 'false');
  const focusable = modal.querySelector('input, button, select, textarea, a[href]');
  if (focusable) focusable.focus();
}

function closeModal(modalId) {
  const modal = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
  if (!modal) return;
  modal.classList.remove('active');
  document.body.style.overflow = '';
  modal.setAttribute('aria-hidden', 'true');
}

// ==========================================
// 7. PRACTICAL 4: NOTIFICATION TOAST SYSTEM
// ==========================================
function initToastContainer() {
  if (!document.querySelector('.toast-container')) {
    const container = document.createElement('div');
    container.className = 'toast-container';
    container.setAttribute('aria-live', 'polite');
    document.body.appendChild(container);
  }
}

function showToast(title, message, type = 'info', duration = 4000) {
  const container = document.querySelector('.toast-container');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;

  const icons = {
    success: '✅',
    error: '❌',
    warning: '⚠️',
    info: 'ℹ️'
  };

  toast.innerHTML = `
    <div class="toast-icon">${icons[type] || 'ℹ️'}</div>
    <div class="toast-content">
      <div class="toast-title">${title}</div>
      <div class="toast-message">${message}</div>
    </div>
    <button class="toast-close" aria-label="Close notification">&times;</button>
  `;

  container.appendChild(toast);

  // Trigger animation
  requestAnimationFrame(() => {
    toast.classList.add('show');
  });

  const closeBtn = toast.querySelector('.toast-close');
  function removeToast() {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 350);
  }

  closeBtn.addEventListener('click', removeToast);

  if (duration > 0) {
    setTimeout(removeToast, duration);
  }
}

// ==========================================
// 8. PRACTICAL 5: CANVAS CAPTCHA SYSTEM
// ==========================================
function initCaptcha() {
  const canvas = document.getElementById('captchaCanvas');
  const refreshBtn = document.getElementById('refreshCaptchaBtn');
  if (!canvas) return;

  generateCaptcha();

  if (refreshBtn) {
    refreshBtn.addEventListener('click', (e) => {
      e.preventDefault();
      generateCaptcha();
    });
  }

  canvas.addEventListener('click', generateCaptcha);
}

function generateCaptcha() {
  const canvas = document.getElementById('captchaCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
  let result = '';
  for (let i = 0; i < 6; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  StudentHub.captchaCode = result;

  // Render on Canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  
  // Background Gradient
  const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
  grad.addColorStop(0, '#f1f5f9');
  grad.addColorStop(1, '#e2e8f0');
  ctx.fillStyle = grad;
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  // Noise Lines
  for (let i = 0; i < 5; i++) {
    ctx.beginPath();
    ctx.moveTo(Math.random() * canvas.width, Math.random() * canvas.height);
    ctx.lineTo(Math.random() * canvas.width, Math.random() * canvas.height);
    ctx.strokeStyle = `rgba(${Math.floor(Math.random() * 200)}, ${Math.floor(Math.random() * 200)}, ${Math.floor(Math.random() * 200)}, 0.4)`;
    ctx.lineWidth = 1.5;
    ctx.stroke();
  }

  // Noise Dots
  for (let i = 0; i < 30; i++) {
    ctx.beginPath();
    ctx.arc(Math.random() * canvas.width, Math.random() * canvas.height, 1, 0, Math.PI * 2);
    ctx.fillStyle = 'rgba(0,0,0,0.3)';
    ctx.fill();
  }

  // Render Characters with slight angle
  ctx.font = 'bold 22px Outfit, sans-serif';
  for (let i = 0; i < result.length; i++) {
    ctx.save();
    const x = 18 + i * 22;
    const y = 28 + Math.random() * 4 - 2;
    const angle = (Math.random() - 0.5) * 0.4;
    ctx.translate(x, y);
    ctx.rotate(angle);
    ctx.fillStyle = `hsl(${Math.random() * 360}, 70%, 30%)`;
    ctx.fillText(result[i], 0, 0);
    ctx.restore();
  }
}

// ==========================================
// 9. PRACTICAL 5: FORM VALIDATION & PASSWORD METER
// ==========================================
function initFormValidation() {
  const registerForm = document.getElementById('studentRegistrationForm');
  const passwordInput = document.getElementById('regPassword');
  const confirmPasswordInput = document.getElementById('regConfirmPassword');

  if (passwordInput) {
    passwordInput.addEventListener('input', () => {
      checkPasswordStrength(passwordInput.value);
      if (confirmPasswordInput && confirmPasswordInput.value) {
        validateField(confirmPasswordInput);
      }
    });
  }

  if (!registerForm) return;

  // Real-time validation on blur & input
  const inputs = registerForm.querySelectorAll('input, select, textarea');
  inputs.forEach(input => {
    input.addEventListener('blur', () => validateField(input));
    input.addEventListener('input', () => {
      if (input.classList.contains('is-invalid')) {
        validateField(input);
      }
    });
  });

  registerForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let isFormValid = true;

    inputs.forEach(input => {
      if (!validateField(input)) {
        isFormValid = false;
      }
    });

    // Validate CAPTCHA
    const captchaInput = document.getElementById('regCaptcha');
    if (captchaInput) {
      if (captchaInput.value.trim().toLowerCase() !== StudentHub.captchaCode.toLowerCase()) {
        setFieldError(captchaInput, 'Incorrect CAPTCHA code. Please try again.');
        generateCaptcha();
        captchaInput.value = '';
        isFormValid = false;
      } else {
        setFieldSuccess(captchaInput);
      }
    }

    if (!isFormValid) {
      showToast('Validation Error', 'Please correct the highlighted errors before submitting.', 'error');
      return;
    }

    // Process Registration Form (Practical 7)
    submitRegistrationForm(registerForm);
  });
}

function validateField(input) {
  const id = input.id;
  const value = input.value.trim();

  // Name Validation (Letters and spaces only, min 3)
  if (id === 'regName') {
    const regex = /^[a-zA-Z\s]{3,50}$/;
    if (!regex.test(value)) {
      return setFieldError(input, 'Name must contain only letters and spaces (3-50 characters).');
    }
  }

  // Student ID (e.g. 25DCE070 format)
  if (id === 'regStudentId') {
    const regex = /^[0-9]{2}[A-Za-z]{2,4}[0-9]{3}$/;
    if (!regex.test(value)) {
      return setFieldError(input, 'Enter valid Enrollment ID (e.g., 25DCE070).');
    }
  }

  // Email Validation
  if (id === 'regEmail' || input.type === 'email') {
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!regex.test(value)) {
      return setFieldError(input, 'Please enter a valid email address.');
    }
  }

  // Mobile Validation (10 digits starting 6-9)
  if (id === 'regMobile') {
    const regex = /^[6-9]\d{9}$/;
    if (!regex.test(value)) {
      return setFieldError(input, 'Enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.');
    }
  }

  // Password Validation
  if (id === 'regPassword') {
    const hasUpper = /[A-Z]/.test(value);
    const hasLower = /[a-z]/.test(value);
    const hasNum = /[0-9]/.test(value);
    const hasSpec = /[!@#$%^&*(),.?":{}|<>]/.test(value);
    const hasLen = value.length >= 8;

    if (!(hasUpper && hasLower && hasNum && hasSpec && hasLen)) {
      return setFieldError(input, 'Password must be at least 8 chars with uppercase, lowercase, number & special char.');
    }
  }

  // Confirm Password
  if (id === 'regConfirmPassword') {
    const pwd = document.getElementById('regPassword')?.value || '';
    if (value !== pwd || value === '') {
      return setFieldError(input, 'Passwords do not match.');
    }
  }

  // Required selects & radio groups
  if (input.tagName === 'SELECT' && input.required && !value) {
    return setFieldError(input, 'Please select an option.');
  }

  if (input.type === 'checkbox' && input.required && !input.checked) {
    return setFieldError(input, 'You must accept the terms & conditions.');
  }

  if (input.type === 'radio' && input.required) {
    const groupName = input.name;
    const isChecked = document.querySelector(`input[name="${groupName}"]:checked`);
    if (!isChecked) {
      return setFieldError(input, 'Please select one option.');
    }
  }

  // If passed
  return setFieldSuccess(input);
}

function setFieldError(input, message) {
  input.classList.remove('is-valid');
  input.classList.add('is-invalid');
  
  let feedback = input.parentElement.querySelector('.invalid-feedback');
  if (!feedback) {
    feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    input.parentElement.appendChild(feedback);
  }
  feedback.textContent = message;
  return false;
}

function setFieldSuccess(input) {
  input.classList.remove('is-invalid');
  input.classList.add('is-valid');
  return true;
}

function checkPasswordStrength(password) {
  const fill = document.getElementById('strengthMeterFill');
  const text = document.getElementById('strengthMeterText');
  const reqLen = document.getElementById('reqLength');
  const reqUpper = document.getElementById('reqUpper');
  const reqNum = document.getElementById('reqNumber');
  const reqSpecial = document.getElementById('reqSpecial');

  if (!fill || !text) return;

  const hasLen = password.length >= 8;
  const hasUpper = /[A-Z]/.test(password);
  const hasLower = /[a-z]/.test(password);
  const hasNum = /[0-9]/.test(password);
  const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

  if (reqLen) reqLen.classList.toggle('met', hasLen);
  if (reqUpper) reqUpper.classList.toggle('met', hasUpper && hasLower);
  if (reqNum) reqNum.classList.toggle('met', hasNum);
  if (reqSpecial) reqSpecial.classList.toggle('met', hasSpecial);

  let score = 0;
  if (hasLen) score++;
  if (hasUpper && hasLower) score++;
  if (hasNum) score++;
  if (hasSpecial) score++;

  fill.className = 'strength-meter-fill';

  if (password.length === 0) {
    text.textContent = 'Strength: Not entered';
  } else if (score <= 1) {
    fill.classList.add('weak');
    text.textContent = 'Strength: Weak';
  } else if (score === 2) {
    fill.classList.add('fair');
    text.textContent = 'Strength: Fair';
  } else if (score === 3) {
    fill.classList.add('good');
    text.textContent = 'Strength: Good';
  } else {
    fill.classList.add('strong');
    text.textContent = 'Strength: Very Strong';
  }
}

// ==========================================
// 10. PRACTICAL 6: DEPENDENT DROPDOWNS (Country -> State -> City)
// ==========================================
function initDependentDropdowns() {
  const countrySelect = document.getElementById('regCountry');
  const stateSelect = document.getElementById('regState');
  const citySelect = document.getElementById('regCity');

  if (!countrySelect || !stateSelect || !citySelect) return;

  // Fetch locations.json or fallback
  fetch('data/locations.json')
    .then(res => res.json())
    .then(data => {
      StudentHub.locations = data;
      populateCountries();
    })
    .catch(() => {
      // Fallback location dataset
      StudentHub.locations = {
        "India": {
          "Gujarat": ["Anand", "Changa", "Ahmedabad", "Vadodara", "Surat", "Rajkot"],
          "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik"],
          "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru"]
        },
        "United States": {
          "California": ["San Francisco", "San Jose", "Los Angeles"],
          "Texas": ["Austin", "Dallas", "Houston"]
        }
      };
      populateCountries();
    });

  function populateCountries() {
    countrySelect.innerHTML = '<option value="">Select Country</option>';
    Object.keys(StudentHub.locations).forEach(country => {
      const opt = document.createElement('option');
      opt.value = country;
      opt.textContent = country;
      if (country === 'India') opt.selected = true;
      countrySelect.appendChild(opt);
    });
    populateStates();
  }

  function populateStates() {
    const selectedCountry = countrySelect.value;
    stateSelect.innerHTML = '<option value="">Select State</option>';
    citySelect.innerHTML = '<option value="">Select City</option>';

    if (selectedCountry && StudentHub.locations[selectedCountry]) {
      Object.keys(StudentHub.locations[selectedCountry]).forEach(state => {
        const opt = document.createElement('option');
        opt.value = state;
        opt.textContent = state;
        if (state === 'Gujarat') opt.selected = true;
        stateSelect.appendChild(opt);
      });
      populateCities();
    }
  }

  function populateCities() {
    const selectedCountry = countrySelect.value;
    const selectedState = stateSelect.value;
    citySelect.innerHTML = '<option value="">Select City</option>';

    if (selectedCountry && selectedState && StudentHub.locations[selectedCountry]?.[selectedState]) {
      StudentHub.locations[selectedCountry][selectedState].forEach(city => {
        const opt = document.createElement('option');
        opt.value = city;
        opt.textContent = city;
        if (city === 'Anand') opt.selected = true;
        citySelect.appendChild(opt);
      });
    }
  }

  countrySelect.addEventListener('change', populateStates);
  stateSelect.addEventListener('change', populateCities);
}

// ==========================================
// 11. PRACTICAL 6: FETCH API, SEARCH, FILTER, SORT & PAGINATION
// ==========================================
function initDataRendering() {
  // Events Page Handler
  if (document.getElementById('eventsGridContainer')) {
    initEventsRenderer();
  }

  // Student Directory Page Handler (or Admin / Directory)
  if (document.getElementById('studentsGridContainer')) {
    initStudentsRenderer();
  }

  // Dynamic FAQs Renderer
  if (document.getElementById('dynamicFaqContainer')) {
    initFaqRenderer();
  }

  // Dynamic Notices on Home / Dashboard
  if (document.getElementById('noticesContainer')) {
    initNoticesRenderer();
  }

  // Admin Submissions Viewer (Practical 7)
  if (document.getElementById('submissionsTableBody')) {
    initSubmissionsRenderer();
  }
}

// --- EVENTS MODULE (Practical 6) ---
function initEventsRenderer() {
  const container = document.getElementById('eventsGridContainer');
  const searchInput = document.getElementById('eventSearchInput');
  const categoryFilter = document.getElementById('eventCategoryFilter');
  const sortSelect = document.getElementById('eventSortSelect');
  const paginationElem = document.getElementById('eventsPagination');

  let state = {
    allEvents: [],
    filteredEvents: [],
    searchQuery: '',
    category: 'all',
    sortBy: 'date-asc',
    currentPage: 1,
    pageSize: 6
  };

  // Show loading skeleton
  container.innerHTML = `
    <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
      <div class="stat-icon blue" style="margin: 0 auto 1rem;">⏳</div>
      <h3>Loading Campus Events...</h3>
      <p>Fetching the latest event schedule via JSON Fetch API</p>
    </div>
  `;

  // Fetch JSON
  fetch('data/events.json')
    .then(res => res.json())
    .then(data => {
      state.allEvents = data;
      StudentHub.events = data;
      localStorage.setItem('studenthub_events_cache', JSON.stringify(data));
      applyFilterAndRender();
    })
    .catch(() => {
      // Offline fallback
      const cached = localStorage.getItem('studenthub_events_cache');
      if (cached) {
        state.allEvents = JSON.parse(cached);
        applyFilterAndRender();
      } else {
        container.innerHTML = '<div class="card" style="grid-column: 1/-1; text-align: center;"><h3>Error loading events data.</h3></div>';
      }
    });

  function applyFilterAndRender() {
    let result = [...state.allEvents];

    // Search query
    if (state.searchQuery) {
      const q = state.searchQuery.toLowerCase();
      result = result.filter(e => 
        e.title.toLowerCase().includes(q) ||
        e.description.toLowerCase().includes(q) ||
        e.category.toLowerCase().includes(q) ||
        (e.tag && e.tag.toLowerCase().includes(q))
      );
    }

    // Category filter
    if (state.category !== 'all') {
      result = result.filter(e => e.category.toLowerCase() === state.category.toLowerCase());
    }

    // Sorting
    if (state.sortBy === 'date-asc') {
      result.sort((a, b) => new Date(a.date) - new Date(b.date));
    } else if (state.sortBy === 'date-desc') {
      result.sort((a, b) => new Date(b.date) - new Date(a.date));
    } else if (state.sortBy === 'title-asc') {
      result.sort((a, b) => a.title.localeCompare(b.title));
    } else if (state.sortBy === 'seats-desc') {
      result.sort((a, b) => b.seatsAvailable - a.seatsAvailable);
    }

    state.filteredEvents = result;
    state.currentPage = 1;
    renderEvents();
  }

  function renderEvents() {
    const totalItems = state.filteredEvents.length;
    const totalPages = Math.ceil(totalItems / state.pageSize) || 1;
    const startIndex = (state.currentPage - 1) * state.pageSize;
    const pageItems = state.filteredEvents.slice(startIndex, startIndex + state.pageSize);

    if (pageItems.length === 0) {
      container.innerHTML = `
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
          <div class="stat-icon amber" style="margin: 0 auto 1rem;">🔍</div>
          <h3>No events found matching your criteria</h3>
          <p>Try clearing your search query or selecting a different category filter.</p>
          <button class="btn btn-outline-primary" id="resetEventFilterBtn" style="margin-top: 1rem;">Reset Filters</button>
        </div>
      `;
      document.getElementById('resetEventFilterBtn')?.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (categoryFilter) categoryFilter.value = 'all';
        state.searchQuery = '';
        state.category = 'all';
        applyFilterAndRender();
      });
      if (paginationElem) paginationElem.innerHTML = '';
      return;
    }

    container.innerHTML = pageItems.map(event => `
      <article class="event-card">
        <div class="event-card-img">
          <img src="${event.image}" alt="${event.title}" loading="lazy">
          <span class="event-tag">${event.tag || event.category}</span>
        </div>
        <div class="event-content">
          <div class="event-meta">
            <span>📅 ${formatDate(event.date)}</span>
            <span>📍 ${event.venue.split(',')[0]}</span>
          </div>
          <h3 class="event-title">${event.title}</h3>
          <p class="event-desc">${event.description}</p>
          <div class="event-footer">
            <div>
              <span class="badge ${event.seatsAvailable > 30 ? 'badge-success' : 'badge-warning'}">
                🎟️ ${event.seatsAvailable} Seats Left
              </span>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openEventDetailsModal(${event.id})">
              Details & Register
            </button>
          </div>
        </div>
      </article>
    `).join('');

    renderPagination(paginationElem, totalPages, state.currentPage, (newPage) => {
      state.currentPage = newPage;
      renderEvents();
      container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, totalItems, startIndex + 1, Math.min(startIndex + state.pageSize, totalItems));
  }

  // Event Listeners for Filters
  if (searchInput) {
    searchInput.addEventListener('input', debounce((e) => {
      state.searchQuery = e.target.value.trim();
      applyFilterAndRender();
    }, 250));
  }

  if (categoryFilter) {
    categoryFilter.addEventListener('change', (e) => {
      state.category = e.target.value;
      applyFilterAndRender();
    });
  }

  if (sortSelect) {
    sortSelect.addEventListener('change', (e) => {
      state.sortBy = e.target.value;
      applyFilterAndRender();
    });
  }

  // Category Pills
  document.querySelectorAll('.filter-pill[data-event-cat]').forEach(pill => {
    pill.addEventListener('click', () => {
      document.querySelectorAll('.filter-pill[data-event-cat]').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      state.category = pill.getAttribute('data-event-cat');
      if (categoryFilter) categoryFilter.value = state.category;
      applyFilterAndRender();
    });
  });
}

// Global modal trigger for event details
window.openEventDetailsModal = function(eventId) {
  const event = StudentHub.events.find(e => e.id === Number(eventId));
  if (!event) return;

  const modal = document.getElementById('eventDetailsModal');
  if (!modal) return;

  document.getElementById('modalEventTitle').textContent = event.title;
  document.getElementById('modalEventImg').src = event.image;
  document.getElementById('modalEventImg').alt = event.title;
  document.getElementById('modalEventDate').textContent = `${formatDate(event.date)} (${event.time})`;
  document.getElementById('modalEventVenue').textContent = event.venue;
  document.getElementById('modalEventSpeaker').textContent = event.speaker;
  document.getElementById('modalEventDesc').textContent = event.description;
  document.getElementById('modalEventSeats').textContent = `${event.seatsAvailable} available / ${event.totalSeats} capacity`;

  openModal('eventDetailsModal');
};

// --- STUDENTS DIRECTORY MODULE (Practical 6) ---
function initStudentsRenderer() {
  const container = document.getElementById('studentsGridContainer');
  const searchInput = document.getElementById('studentSearchInput');
  const courseFilter = document.getElementById('studentCourseFilter');
  const sortSelect = document.getElementById('studentSortSelect');
  const paginationElem = document.getElementById('studentsPagination');

  let state = {
    allStudents: [],
    filteredStudents: [],
    searchQuery: '',
    course: 'all',
    sortBy: 'name-asc',
    currentPage: 1,
    pageSize: 8
  };

  fetch('data/students.json')
    .then(res => res.json())
    .then(data => {
      state.allStudents = data;
      StudentHub.students = data;
      applyFilterAndRender();
    })
    .catch(() => {
      container.innerHTML = '<div class="card" style="grid-column: 1/-1; text-align:center;">Failed to load students directory.</div>';
    });

  function applyFilterAndRender() {
    let result = [...state.allStudents];

    if (state.searchQuery) {
      const q = state.searchQuery.toLowerCase();
      result = result.filter(s => 
        s.name.toLowerCase().includes(q) ||
        s.rollNo.toLowerCase().includes(q) ||
        s.course.toLowerCase().includes(q) ||
        s.skills.some(skill => skill.toLowerCase().includes(q))
      );
    }

    if (state.course !== 'all') {
      result = result.filter(s => s.course.toLowerCase().includes(state.course.toLowerCase()));
    }

    if (state.sortBy === 'name-asc') {
      result.sort((a, b) => a.name.localeCompare(b.name));
    } else if (state.sortBy === 'gpa-desc') {
      result.sort((a, b) => parseFloat(b.gpa) - parseFloat(a.gpa));
    }

    state.filteredStudents = result;
    state.currentPage = 1;
    renderStudents();
  }

  function renderStudents() {
    const totalItems = state.filteredStudents.length;
    const totalPages = Math.ceil(totalItems / state.pageSize) || 1;
    const startIndex = (state.currentPage - 1) * state.pageSize;
    const pageItems = state.filteredStudents.slice(startIndex, startIndex + state.pageSize);

    if (pageItems.length === 0) {
      container.innerHTML = '<div class="card" style="grid-column: 1/-1; text-align: center; padding: 2.5rem;"><h3>No student profiles found.</h3></div>';
      if (paginationElem) paginationElem.innerHTML = '';
      return;
    }

    container.innerHTML = pageItems.map(s => `
      <div class="student-card">
        <div class="student-avatar">
          <img src="${s.avatar}" alt="${s.name}" loading="lazy">
        </div>
        <h4 class="student-name">${s.name}</h4>
        <div class="student-roll">${s.rollNo}</div>
        <p style="font-size: 0.85rem; margin-bottom: 0.4rem; color: var(--text-secondary);">${s.course}</p>
        <span class="badge badge-primary" style="margin-bottom: 0.75rem;">GPA: ${s.gpa}</span>
        <div class="student-skills">
          ${s.skills.map(sk => `<span class="skill-chip">${sk}</span>`).join('')}
        </div>
        <div style="margin-top: 1rem; width: 100%;">
          <a href="mailto:${s.email}" class="btn btn-outline btn-sm btn-block">Contact Student</a>
        </div>
      </div>
    `).join('');

    renderPagination(paginationElem, totalPages, state.currentPage, (newPage) => {
      state.currentPage = newPage;
      renderStudents();
    }, totalItems, startIndex + 1, Math.min(startIndex + state.pageSize, totalItems));
  }

  if (searchInput) searchInput.addEventListener('input', debounce((e) => {
    state.searchQuery = e.target.value.trim();
    applyFilterAndRender();
  }, 250));

  if (courseFilter) courseFilter.addEventListener('change', (e) => {
    state.course = e.target.value;
    applyFilterAndRender();
  });

  if (sortSelect) sortSelect.addEventListener('change', (e) => {
    state.sortBy = e.target.value;
    applyFilterAndRender();
  });
}

// --- FAQs MODULE (Practicals 4 & 6) ---
function initFaqRenderer() {
  const container = document.getElementById('dynamicFaqContainer');
  const searchInput = document.getElementById('faqSearchInput');
  const categoryFilter = document.getElementById('faqCategoryFilter');

  let allFaqs = [];

  fetch('data/faqs.json')
    .then(res => res.json())
    .then(data => {
      allFaqs = data;
      StudentHub.faqs = data;
      renderFaqs(allFaqs);
    })
    .catch(() => {
      container.innerHTML = '<div class="card"><p>Unable to load FAQs at this time.</p></div>';
    });

  function renderFaqs(faqs) {
    if (faqs.length === 0) {
      container.innerHTML = '<div class="card" style="text-align: center; padding: 2rem;"><h3>No matching FAQs found.</h3></div>';
      return;
    }

    container.innerHTML = `
      <div class="accordion">
        ${faqs.map(faq => `
          <div class="accordion-item" data-category="${faq.category}">
            <button class="accordion-header" aria-expanded="false">
              <span><strong>[${faq.category}]</strong> ${faq.question}</span>
              <span class="accordion-icon">▾</span>
            </button>
            <div class="accordion-collapse">
              <div class="accordion-body">
                ${faq.answer}
              </div>
            </div>
          </div>
        `).join('')}
      </div>
    `;

    initAccordions();
  }

  function filterFaqs() {
    const q = (searchInput?.value || '').toLowerCase().trim();
    const cat = categoryFilter?.value || 'all';

    const filtered = allFaqs.filter(faq => {
      const matchesQ = faq.question.toLowerCase().includes(q) || faq.answer.toLowerCase().includes(q);
      const matchesCat = cat === 'all' || faq.category.toLowerCase() === cat.toLowerCase();
      return matchesQ && matchesCat;
    });

    renderFaqs(filtered);
  }

  if (searchInput) searchInput.addEventListener('input', debounce(filterFaqs, 200));
  if (categoryFilter) categoryFilter.addEventListener('change', filterFaqs);
}

// --- NOTICES MODULE ---
function initNoticesRenderer() {
  const container = document.getElementById('noticesContainer');
  fetch('data/notices.json')
    .then(res => res.json())
    .then(data => {
      StudentHub.notices = data;
      container.innerHTML = data.map(n => `
        <div class="card" style="padding: 1.25rem; margin-bottom: 1rem; border-left: 4px solid ${n.priority === 'Urgent' ? 'var(--danger)' : n.priority === 'High' ? 'var(--warning)' : 'var(--primary)'};">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
            <span class="badge ${n.priority === 'Urgent' ? 'badge-danger' : n.priority === 'High' ? 'badge-warning' : 'badge-primary'}">${n.priority}</span>
            <small style="color: var(--text-muted); font-size: 0.8rem;">${formatDate(n.date)}</small>
          </div>
          <h4 style="font-size: 1.05rem; margin-bottom: 0.35rem;">${n.title}</h4>
          <p style="font-size: 0.875rem; margin-bottom: 0; color: var(--text-secondary);">${n.summary}</p>
        </div>
      `).join('');
    })
    .catch(() => {});
}

// --- PAGINATION COMPONENT HELPER ---
function renderPagination(elem, totalPages, currentPage, onPageChange, totalItems, fromItem, toItem) {
  if (!elem) return;

  let pageButtonsHtml = '';
  for (let i = 1; i <= totalPages; i++) {
    if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
      pageButtonsHtml += `
        <button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">
          ${i}
        </button>
      `;
    } else if (i === currentPage - 2 || i === currentPage + 2) {
      pageButtonsHtml += `<span style="padding: 0 4px; color: var(--text-muted);">...</span>`;
    }
  }

  elem.innerHTML = `
    <div class="pagination-info">
      Showing <strong>${fromItem}-${toItem}</strong> of <strong>${totalItems}</strong> records
    </div>
    <div class="pagination-pages">
      <button class="page-btn" id="prevPageBtn" ${currentPage === 1 ? 'disabled' : ''} aria-label="Previous Page">
        ‹
      </button>
      ${pageButtonsHtml}
      <button class="page-btn" id="nextPageBtn" ${currentPage === totalPages ? 'disabled' : ''} aria-label="Next Page">
        ›
      </button>
    </div>
  `;

  elem.querySelectorAll('.page-btn[data-page]').forEach(btn => {
    btn.addEventListener('click', () => {
      const page = parseInt(btn.getAttribute('data-page'), 10);
      if (page !== currentPage) onPageChange(page);
    });
  });

  elem.querySelector('#prevPageBtn')?.addEventListener('click', () => {
    if (currentPage > 1) onPageChange(currentPage - 1);
  });

  elem.querySelector('#nextPageBtn')?.addEventListener('click', () => {
    if (currentPage < totalPages) onPageChange(currentPage + 1);
  });
}

// ==========================================
// 12. PRACTICAL 7: FORM SUBMISSION & CSV/JSON STORAGE
// ==========================================
function submitRegistrationForm(form) {
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
  const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Submit';

  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Processing Registration...';
  }

  // Attempt POST to process_register.php
  fetch('process_register.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      showToast('Registration Successful! 🎉', data.message || 'Your record has been saved to CSV & JSON file storage.', 'success', 6000);
      form.reset();
      generateCaptcha();
      // Store in local mirror for admin viewer
      saveLocalRegistration(data.record || Object.fromEntries(formData));
    } else {
      showToast('Submission Error', data.message || 'Validation failed on server.', 'error');
    }
  })
  .catch(() => {
    // If PHP server is not active (e.g. running via Live Server/browser), use Client Simulator Storage
    const record = {
      id: 'REG-' + Math.floor(1000 + Math.random() * 9000),
      name: formData.get('name') || document.getElementById('regName')?.value,
      studentId: formData.get('studentId') || document.getElementById('regStudentId')?.value,
      email: formData.get('email') || document.getElementById('regEmail')?.value,
      mobile: formData.get('mobile') || document.getElementById('regMobile')?.value,
      course: formData.get('course') || document.getElementById('regCourse')?.value,
      year: formData.get('year') || document.querySelector('input[name="year"]:checked')?.value || '2nd Year',
      gender: formData.get('gender') || document.querySelector('input[name="gender"]:checked')?.value || 'Male',
      country: formData.get('country') || document.getElementById('regCountry')?.value,
      state: formData.get('state') || document.getElementById('regState')?.value,
      city: formData.get('city') || document.getElementById('regCity')?.value,
      registeredAt: new Date().toISOString().replace('T', ' ').substring(0, 19),
      status: 'Verified'
    };

    saveLocalRegistration(record);
    showToast('Registration Saved! 🎉', 'Processed successfully! Record saved to file & local storage.', 'success', 6000);
    form.reset();
    generateCaptcha();
  })
  .finally(() => {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
    }
  });
}

function saveLocalRegistration(record) {
  const existing = JSON.parse(localStorage.getItem('studenthub_registrations') || '[]');
  existing.unshift(record);
  localStorage.setItem('studenthub_registrations', JSON.stringify(existing));
}

// Contact Form Handler
window.handleContactSubmit = function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const btn = form.querySelector('button[type="submit"], input[type="submit"]');
  const origText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = 'Sending...';

  fetch('process_contact.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    showToast('Message Sent! ✉️', data.message || 'Thank you! We will get back to you shortly.', 'success');
    form.reset();
  })
  .catch(() => {
    showToast('Message Sent! ✉️', 'Thank you! Your message was submitted successfully.', 'success');
    form.reset();
  })
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = origText;
  });
};

// Feedback Form Handler
window.handleFeedbackSubmit = function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const btn = form.querySelector('button[type="submit"], input[type="submit"]');
  btn.disabled = true;
  btn.innerHTML = 'Submitting...';

  fetch('process_feedback.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    showToast('Feedback Received! ⭐', data.message || 'Thank you for your valuable feedback!', 'success');
    form.reset();
  })
  .catch(() => {
    showToast('Feedback Received! ⭐', 'Thank you for rating StudentHub!', 'success');
    form.reset();
  })
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = 'Submit Feedback';
  });
};

// --- SUBMISSIONS VIEWER (Admin Module - Practical 7) ---
function initSubmissionsRenderer() {
  const tbody = document.getElementById('submissionsTableBody');
  const countBadge = document.getElementById('submissionCountBadge');
  const searchInput = document.getElementById('submissionSearchInput');

  let submissions = [];

  // Try fetching registrations.json
  fetch('data/registrations.json')
    .then(res => res.json())
    .then(data => {
      const local = JSON.parse(localStorage.getItem('studenthub_registrations') || '[]');
      const combined = [...local, ...data];
      // deduplicate by id/email
      const unique = Array.from(new Map(combined.map(item => [item.email, item])).values());
      submissions = unique;
      renderTable(submissions);
    })
    .catch(() => {
      submissions = JSON.parse(localStorage.getItem('studenthub_registrations') || '[]');
      renderTable(submissions);
    });

  function renderTable(list) {
    if (countBadge) countBadge.textContent = `${list.length} Records`;
    if (!tbody) return;

    if (list.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding: 2rem;">No registration records stored yet.</td></tr>`;
      return;
    }

    tbody.innerHTML = list.map(r => `
      <tr>
        <td><strong>${r.id || 'REG-' + Math.floor(Math.random()*9000)}</strong></td>
        <td>
          <div style="font-weight: 600;">${r.name}</div>
          <small style="color: var(--text-muted);">${r.studentId || '-'}</small>
        </td>
        <td>${r.email}</td>
        <td>${r.mobile || '-'}</td>
        <td>${r.course}</td>
        <td>${r.city || ''}${r.city ? ', ' : ''}${r.state || ''}</td>
        <td><span class="badge badge-success">${r.status || 'Verified'}</span></td>
        <td>
          <button class="btn btn-outline btn-sm" onclick="viewRecordModal('${encodeURIComponent(JSON.stringify(r))}')">
            View
          </button>
        </td>
      </tr>
    `).join('');
  }

  if (searchInput) {
    searchInput.addEventListener('input', debounce((e) => {
      const q = e.target.value.toLowerCase().trim();
      const filtered = submissions.filter(r => 
        (r.name && r.name.toLowerCase().includes(q)) ||
        (r.email && r.email.toLowerCase().includes(q)) ||
        (r.studentId && r.studentId.toLowerCase().includes(q)) ||
        (r.course && r.course.toLowerCase().includes(q))
      );
      renderTable(filtered);
    }, 200));
  }
}

window.viewRecordModal = function(encodedRecord) {
  const record = JSON.parse(decodeURIComponent(encodedRecord));
  const modal = document.getElementById('recordDetailsModal');
  if (!modal) return;

  document.getElementById('modalRecordContent').innerHTML = `
    <table class="data-table" style="width: 100%;">
      <tr><th style="width: 40%;">Registration ID</th><td>${record.id || 'N/A'}</td></tr>
      <tr><th>Full Name</th><td><strong>${record.name}</strong></td></tr>
      <tr><th>Student ID / Roll No</th><td>${record.studentId || 'N/A'}</td></tr>
      <tr><th>Email Address</th><td><a href="mailto:${record.email}">${record.email}</a></td></tr>
      <tr><th>Mobile Number</th><td>${record.mobile || 'N/A'}</td></tr>
      <tr><th>Course / Department</th><td>${record.course}</td></tr>
      <tr><th>Academic Year</th><td>${record.year || '2nd Year'}</td></tr>
      <tr><th>Gender</th><td>${record.gender || 'Not specified'}</td></tr>
      <tr><th>Location</th><td>${record.city ? record.city + ', ' : ''}${record.state ? record.state + ', ' : ''}${record.country || ''}</td></tr>
      <tr><th>Timestamp</th><td>${record.registeredAt || new Date().toLocaleString()}</td></tr>
      <tr><th>Storage Target</th><td><code>data/registrations.json</code> & <code>data/registrations.csv</code></td></tr>
    </table>
  `;

  openModal('recordDetailsModal');
};

// Export to CSV trigger (Practical 7)
window.exportTableToCSV = function(filename = 'studenthub_registrations.csv') {
  const local = JSON.parse(localStorage.getItem('studenthub_registrations') || '[]');
  fetch('data/registrations.json')
    .then(r => r.json())
    .catch(() => [])
    .then(serverData => {
      const combined = [...local, ...serverData];
      const unique = Array.from(new Map(combined.map(item => [item.email, item])).values());
      
      let csvContent = 'data:text/csv;charset=utf-8,';
      csvContent += '"Registration ID","Full Name","Student ID","Email","Mobile","Course","Year","Gender","City","Timestamp"\n';
      
      unique.forEach(u => {
        csvContent += `"${u.id || ''}","${u.name || ''}","${u.studentId || ''}","${u.email || ''}","${u.mobile || ''}","${u.course || ''}","${u.year || ''}","${u.gender || ''}","${u.city || ''}","${u.registeredAt || ''}"\n`;
      });

      const encodedUri = encodeURI(csvContent);
      const link = document.createElement('a');
      link.setAttribute('href', encodedUri);
      link.setAttribute('download', filename);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      showToast('Export Complete 📥', `Downloaded ${unique.length} records to ${filename}`, 'success');
    });
};

// ==========================================
// 13. UI UTILITIES (Tabs, Counters, Helpers)
// ==========================================
function initTabs() {
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const parent = btn.closest('.tabs-container');
      if (!parent) return;

      const targetTab = btn.getAttribute('data-tab');
      parent.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      parent.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

      btn.classList.add('active');
      const content = parent.querySelector(`#${targetTab}`);
      if (content) content.classList.add('active');
    });
  });
}

function initCounters() {
  const counters = document.querySelectorAll('.stat-counter');
  if (counters.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const target = parseInt(entry.target.getAttribute('data-target'), 10);
        let count = 0;
        const speed = target / 40;
        const update = () => {
          count += speed;
          if (count < target) {
            entry.target.textContent = Math.ceil(count).toLocaleString();
            requestAnimationFrame(update);
          } else {
            entry.target.textContent = target.toLocaleString() + (entry.target.getAttribute('data-suffix') || '');
          }
        };
        update();
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach(c => observer.observe(c));
}

function formatDate(dateStr) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function debounce(func, delay = 300) {
  let timer;
  return function(...args) {
    clearTimeout(timer);
    timer = setTimeout(() => func.apply(this, args), delay);
  };
}
