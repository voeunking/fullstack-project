<template>
  <footer class="app-footer">
    <div class="footer-content">
      <div class="footer-section">
        <div class="footer-brand">
          <svg class="logo-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
          </svg>
          <span class="brand-name">KING TECH</span>
        </div>
        <p class="brand-desc">Your trusted source for quality technology products and exceptional service.</p>
        <div class="social-links">
          <a href="#" class="social-link">
            <svg fill="currentColor" viewBox="0 0 24 24" width="20" height="20">
              <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
            </svg>
          </a>
          <a href="#" class="social-link">
            <svg fill="currentColor" viewBox="0 0 24 24" width="20" height="20">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
          </a>
          <a href="#" class="social-link">
            <svg fill="currentColor" viewBox="0 0 24 24" width="20" height="20">
              <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
            </svg>
          </a>
        </div>
      </div>

      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul class="footer-links">
          <li><router-link to="/home">Home</router-link></li>
          <li><router-link to="/about">About Us</router-link></li>
          <li><router-link to="/dashboard/products">Shop</router-link></li>
          <li><router-link to="/contact">Contact</router-link></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>Customer Service</h3>
        <ul class="footer-links">
          <li><router-link to="/dashboard/orders">Track Order</router-link></li>
          <li><router-link to="/dashboard/profile">My Account</router-link></li>
          <li><router-link to="/dashboard/cart">Shopping Cart</router-link></li>
          <li><router-link to="/vendor">Become a Vendor</router-link></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>Contact Info</h3>
        <ul class="contact-info">
          <li>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>123 Tech Street, Silicon Valley, CA 94000</span>
          </li>
          <li>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>support@kingtech.com</span>
          </li>
          <li>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span>+1 (555) 123-4567</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2024 KING TECH. All rights reserved.</p>
      <div class="footer-legal">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.app-footer {
  background: #000000;
  color: white;
  padding: 3rem 2rem 1.5rem;
  margin-top: auto;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 3rem;
  margin-bottom: 2rem;
}

@media (max-width: 900px) {
  .footer-content {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 600px) {
  .footer-content {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
}

.footer-section h3 {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: white;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.logo-icon {
  width: 28px;
  height: 28px;
  color: var(--accent);
}

.brand-name {
  font-size: 1.25rem;
  font-weight: 800;
  color: white;
}

.brand-desc {
  color: #c2c7d4;
  font-size: 0.9rem;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.social-links {
  display: flex;
  gap: 0.75rem;
}

.social-link {
  width: 36px;
  height: 36px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  transition: background 0.15s ease;
}

.social-link:hover {
  background: var(--accent);
}

.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-links li {
  margin-bottom: 0.5rem;
}

.footer-links a {
  color: #c2c7d4;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.15s ease;
}

.footer-links a:hover {
  color: white;
}

.contact-info {
  list-style: none;
  padding: 0;
  margin: 0;
}

.contact-info li {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
  color: #c2c7d4;
  font-size: 0.9rem;
}

.contact-info svg {
  flex-shrink: 0;
  color: var(--accent);
}

.footer-bottom {
  max-width: 1200px;
  margin: 0 auto;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.footer-bottom p {
  color: #c2c7d4;
  font-size: 0.85rem;
  margin: 0;
}

.footer-legal {
  display: flex;
  gap: 1.5rem;
}

.footer-legal a {
  color: #c2c7d4;
  text-decoration: none;
  font-size: 0.85rem;
  transition: color 0.15s ease;
}

.footer-legal a:hover {
  color: white;
}

@media (max-width: 600px) {
  .footer-bottom {
    flex-direction: column;
    text-align: center;
  }

  .footer-legal {
    justify-content: center;
  }
}
</style>
