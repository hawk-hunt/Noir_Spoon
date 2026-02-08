/**
 * Main JavaScript file for common functionality
 * Enhanced with advanced theme and layout management
 */

;(function () {
  'use strict'

  // Set assets path and layout path variables
  window.assetsPath = document.documentElement.getAttribute('data-assets-path')
  window.layoutPath = ''
  window.commonAssetsPath = ''

  const root = document.documentElement
  const layoutPath = 'free-landing-page'
  const localStorageKey = `${layoutPath}-theme`

  // Function to get current system theme preference
  const getSystemPreference = () => window.matchMedia('(prefers-color-scheme: dark)').matches

  // Function to resolve theme based on selected theme and layout configuration
  const resolveTheme = theme => {
    // Use more robust access pattern
    const layoutConfig = window.THEME_CONFIG && window.THEME_CONFIG[layoutPath] ? window.THEME_CONFIG[layoutPath] : null

    if (theme === 'system') {
      if (layoutConfig && layoutConfig.system) {
        const prefersDark = getSystemPreference()
        const resolvedTheme = prefersDark ? layoutConfig.system.dark : layoutConfig.system.light

        return resolvedTheme
      }
      // Fallback for layouts without system config - use layout's light/dark themes if available
      if (layoutConfig) {
        const prefersDark = getSystemPreference()
        const resolvedTheme = prefersDark ? layoutConfig.dark || 'dark' : layoutConfig.light || 'light'

        return resolvedTheme
      }
      // Final fallback if no layout config exists
      const resolvedTheme = getSystemPreference() ? 'dark' : 'light'

      return resolvedTheme
    }

    // Check if layout has theme mapping
    if (layoutConfig) {
      const resolvedTheme = layoutConfig[theme] || theme || layoutConfig.default || 'light'

      return resolvedTheme
    }

    return theme
  }

  // Apply selected theme and update dropdown UI
  const applyTheme = themeValue => {
    const finalTheme = resolveTheme(themeValue)
    root.setAttribute('data-theme', finalTheme)
    localStorage.setItem(localStorageKey, themeValue)

    // Update dropdown active state
    document.querySelectorAll('[data-theme-value]').forEach(btn => {
      const isActive = btn.getAttribute('data-theme-value') === themeValue
      btn.classList.toggle('dropdown-active', isActive)
      btn.setAttribute('aria-pressed', isActive)
    })

    // Toggle icon visibility
    const activeBtn = document.querySelector(`[data-theme-value="${themeValue}"]`)
    const iconName = activeBtn?.getAttribute('data-icon') || 'sun-moon'
    document.querySelectorAll('.theme-icon').forEach(iconEl => {
      if (iconEl.classList.contains(`icon-[tabler--${iconName}]`)) {
        iconEl.classList.remove('hidden')
      } else {
        iconEl.classList.add('hidden')
      }
    })
  }

  // Get saved theme or default to 'system'
  const savedTheme = localStorage.getItem(localStorageKey) || 'system'
  applyTheme(savedTheme)

  // Bind dropdown click handlers
  document.querySelectorAll('[data-theme-value]').forEach(btn => {
    btn.addEventListener('click', () => {
      const selectedTheme = btn.getAttribute('data-theme-value')
      applyTheme(selectedTheme)
    })
  })

  // Listen to system theme changes (live update if in 'system' mode)
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    const currentTheme = localStorage.getItem(localStorageKey)
    if (currentTheme === 'system') {
      // Force recalculation of the system theme fallback
      applyTheme('system')
    }
  })
})()

// Scroll to top button
document.addEventListener('DOMContentLoaded', () => {
  const scrollToTopBtn = document.getElementById('scrollToTopBtn')
  // Only proceed if button exists
  if (scrollToTopBtn) {
    scrollToTopBtn.classList.add('hidden')

    scrollToTopBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    })

    window.onscroll = function () {
      if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollToTopBtn.classList.remove('hidden')
      } else {
        scrollToTopBtn.classList.add('hidden')
      }
    }
  }
})

// Simple animated counters (elements with .counter and data-target)
document.addEventListener('DOMContentLoaded', () => {
  const counters = document.querySelectorAll('.counter')
  if (counters.length) {
    const runCounter = el => {
      const target = +el.getAttribute('data-target') || 0
      const duration = +el.getAttribute('data-duration') || 1200
      let start = 0
      const stepTime = Math.max(Math.floor(duration / Math.max(target, 1)), 12)
      const timer = setInterval(() => {
        start += Math.ceil(target / (duration / stepTime))
        if (start >= target) {
          el.textContent = target
          clearInterval(timer)
        } else {
          el.textContent = start
        }
      }, stepTime)
    }

    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          runCounter(entry.target)
          obs.unobserve(entry.target)
        }
      })
    }, { threshold: 0.4 })

    counters.forEach(c => io.observe(c))
  }
})

// Lightweight image lightbox for gallery thumbnails (data-lightbox)
document.addEventListener('click', (e) => {
  const tgt = e.target.closest('[data-lightbox]')
  if (!tgt) return
  e.preventDefault()
  const src = tgt.getAttribute('data-lightbox') || tgt.getAttribute('href')
  if (!src) return

  // create modal
  const modal = document.createElement('div')
  modal.className = 'noir-lightbox'
  modal.innerHTML = `\n    <div class="noir-lightbox-backdrop"></div>\n    <div class="noir-lightbox-content">\n      <button class="noir-lightbox-close" aria-label="Close">×</button>\n      <img src="${src}" alt="" />\n    </div>`
  document.body.appendChild(modal)

  const remove = () => modal.remove()
  modal.querySelector('.noir-lightbox-close').addEventListener('click', remove)
  modal.querySelector('.noir-lightbox-backdrop').addEventListener('click', remove)
})
